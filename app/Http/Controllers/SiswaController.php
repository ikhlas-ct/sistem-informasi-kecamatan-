<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Nagari;
use App\Models\User;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // HELPER: deteksi role user yang login
    // ─────────────────────────────────────────────────────────
    private function getRoleInfo(): array
    {
        $user      = Auth::user();
        $roleLabel = $user->getRoleLabel();
        $pegawai   = $user->pegawai;

        $sekolahAdmin = null;
        if ($user->isAdminSekolah()) {
            $sekolahAdmin = Sekolah::where('id_user', $user->id)->first();
        }

        $idNagariUser = in_array($roleLabel, ['wali_nagari', 'staf_nagari'])
            ? $pegawai?->id_nagari
            : null;

        $isSuperAdmin   = in_array($roleLabel, ['camat', 'staf_camat']);
        $isNagari       = in_array($roleLabel, ['wali_nagari', 'staf_nagari']);
        $isAdminSekolah = $user->isAdminSekolah();

        return compact(
            'user',
            'roleLabel',
            'pegawai',
            'sekolahAdmin',
            'idNagariUser',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah'
        );
    }

    // ─────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $query = Siswa::with(['sekolah.nagari', 'user.masyarakat'])->latest();

        if ($isAdminSekolah) {
            $query->where('id_sekolah', $sekolahAdmin?->id_sekolah);
        } elseif ($isNagari) {
            $ids = Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah');
            $query->whereIn('id_sekolah', $ids);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nis', 'like', "%$s%")
                    ->orWhere('kelas', 'like', "%$s%")
                    ->orWhereHas('user', fn($u) => $u->where('nip_nik', 'like', "%$s%"))
                    ->orWhereHas('user.masyarakat', fn($m) => $m->where('nama_masyarakat', 'like', "%$s%"));
            });
        }

        if (! $isAdminSekolah && $request->filled('id_sekolah')) {
            $query->where('id_sekolah', $request->id_sekolah);
        }

        if ($isSuperAdmin && $request->filled('id_nagari')) {
            $ids = Sekolah::where('id_nagari', $request->id_nagari)->pluck('id_sekolah');
            $query->whereIn('id_sekolah', $ids);
        }

        $siswa = $query->paginate(15)->withQueryString();

        $statQ = Siswa::query();
        if ($isAdminSekolah) {
            $statQ->where('id_sekolah', $sekolahAdmin?->id_sekolah);
        } elseif ($isNagari) {
            $ids = Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah');
            $statQ->whereIn('id_sekolah', $ids);
        }
        $totalSiswa    = $statQ->count();
        $totalPerKelas = (clone $statQ)
            ->selectRaw('kelas, count(*) as total')
            ->whereNotNull('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas');

        $nagariList  = $isSuperAdmin ? Nagari::orderBy('nama_nagari')->get() : collect();
        $sekolahList = collect();
        if ($isSuperAdmin) {
            $sekolahList = $request->filled('id_nagari')
                ? Sekolah::where('id_nagari', $request->id_nagari)->orderBy('nama_sekolah')->get()
                : Sekolah::orderBy('nama_sekolah')->get();
        } elseif ($isNagari) {
            $sekolahList = Sekolah::where('id_nagari', $idNagariUser)->orderBy('nama_sekolah')->get();
        }

        return view('pages.siswa.index', compact(
            'siswa',
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah',
            'nagariList',
            'sekolahList',
            'totalSiswa',
            'totalPerKelas'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────
    public function create()
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $nagariList    = collect();
        $sekolahLocked = null;
        $nagariLocked  = null;

        if ($isSuperAdmin) {
            $nagariList = Nagari::orderBy('nama_nagari')->get();
        } elseif ($isNagari) {
            $nagariLocked = Nagari::find($idNagariUser);
        } elseif ($isAdminSekolah) {
            $sekolahLocked = $sekolahAdmin;
            $nagariLocked  = $sekolahAdmin?->nagari;
        }

        return view('pages.siswa.create', compact(
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah',
            'nagariList',
            'sekolahLocked',
            'nagariLocked'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // STORE  (hanya mode "pilih dari masyarakat")
    // ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $allowedSekolahIds = $this->allowedSekolahIds($info);

        $validated = $request->validate([
            'id_sekolah' => ['required', Rule::in($allowedSekolahIds)],
            'nis'        => 'nullable|string|max:20|unique:siswa,nis',
            'kelas'      => 'nullable|string|max:20',

            // Wajib pilih masyarakat yang:
            //   - role = masyarakat
            //   - sekolah IS NULL (belum jadi siswa/admin manapun)
            //   - belum punya record siswa
            'id_user_masyarakat' => [
                'required',
                Rule::exists('users', 'id')->where(
                    fn($q) => $q->where('role', 'masyarakat')->whereNull('sekolah')
                ),
                // Extra: pastikan belum punya record siswa
                function ($attribute, $value, $fail) {
                    if (Siswa::where('id_user', $value)->exists()) {
                        $fail('Masyarakat ini sudah terdaftar sebagai siswa.');
                    }
                },
            ],
        ], [
            'id_user_masyarakat.required' => 'Pilih masyarakat terlebih dahulu.',
            'id_user_masyarakat.exists'   => 'Masyarakat tidak valid atau sudah menjadi siswa/admin sekolah.',
            'id_sekolah.required'         => 'Pilih sekolah terlebih dahulu.',
            'id_sekolah.in'               => 'Sekolah tidak valid.',
        ]);

        DB::transaction(function () use ($validated) {
            $idUser = (int) $validated['id_user_masyarakat'];

            // Tandai user sebagai siswa sekolah
            User::where('id', $idUser)->update(['sekolah' => 'siswa']);

            Siswa::create([
                'id_user'    => $idUser,
                'id_sekolah' => $validated['id_sekolah'],
                'nis'        => $validated['nis'] ?? null,
                'kelas'      => $validated['kelas'] ?? null,
            ]);
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────
    public function show(Siswa $siswa)
    {
        $info = $this->getRoleInfo();
        extract($info);

        $this->gate($siswa, $info);
        $siswa->load(['sekolah.nagari', 'user.masyarakat']);

        return view('pages.siswa.show', compact(
            'siswa',
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────
    public function edit(Siswa $siswa)
    {
        $info = $this->getRoleInfo();
        extract($info);

        $this->gate($siswa, $info);
        $siswa->load(['sekolah.nagari', 'user.masyarakat']);

        $nagariList    = collect();
        $sekolahLocked = null;
        $nagariLocked  = null;

        if ($isSuperAdmin) {
            $nagariList = Nagari::orderBy('nama_nagari')->get();
        } elseif ($isNagari) {
            $nagariLocked = Nagari::find($idNagariUser);
        } elseif ($isAdminSekolah) {
            $sekolahLocked = $sekolahAdmin;
            $nagariLocked  = $sekolahAdmin?->nagari;
        }

        // Teks awal untuk Select2 (pre-populate pilihan saat ini)
        $currentMasyarakatText = ($siswa->user?->masyarakat?->nama_masyarakat ?? $siswa->user?->nip_nik ?? '-')
            . ' — ' . ($siswa->user?->nip_nik ?? '-');

        return view('pages.siswa.edit', compact(
            'siswa',
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah',
            'nagariList',
            'sekolahLocked',
            'nagariLocked',
            'currentMasyarakatText'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE  (hanya mode "pilih dari masyarakat")
    // ─────────────────────────────────────────────────────────
    public function update(Request $request, Siswa $siswa)
    {
        $info = $this->getRoleInfo();
        extract($info);

        $this->gate($siswa, $info);

        $allowedSekolahIds = $this->allowedSekolahIds($info);

        // Simpan id_user lama agar bisa dipakai closure di bawah
        $oldUserId = $siswa->id_user;

        $validated = $request->validate([
            'id_sekolah' => ['required', Rule::in($allowedSekolahIds)],
            'nis'        => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')->ignore($siswa->id_siswa, 'id_siswa'),
            ],
            'kelas' => 'nullable|string|max:20',

            // Boleh pilih:
            //   a) User yang sama (siswa ini sendiri) → sekolah='siswa'
            //   b) Masyarakat bebas → sekolah IS NULL
            // Tidak boleh: admin sekolah (sekolah='admin') atau siswa lain
            'id_user_masyarakat' => [
                'required',
                Rule::exists('users', 'id')->where(function ($q) use ($oldUserId) {
                    $q->where('role', 'masyarakat')
                        ->where(function ($q2) use ($oldUserId) {
                            // user saat ini (boleh tetap sama) ATAU masyarakat bebas
                            $q2->where('id', $oldUserId)
                                ->orWhereNull('sekolah');
                        });
                }),
                // Extra: kalau bukan user yang sama, pastikan belum punya record siswa lain
                function ($attribute, $value, $fail) use ($oldUserId) {
                    $newId = (int) $value;
                    if ($newId !== $oldUserId && Siswa::where('id_user', $newId)->exists()) {
                        $fail('Masyarakat ini sudah terdaftar sebagai siswa di sekolah lain.');
                    }
                },
            ],
        ], [
            'id_user_masyarakat.required' => 'Pilih masyarakat terlebih dahulu.',
            'id_user_masyarakat.exists'   => 'Masyarakat tidak valid atau sudah menjadi admin sekolah.',
            'id_sekolah.required'         => 'Pilih sekolah terlebih dahulu.',
            'id_sekolah.in'               => 'Sekolah tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $siswa, $oldUserId) {
            $newUserId = (int) $validated['id_user_masyarakat'];

            if ($newUserId !== $oldUserId) {
                // Lepaskan user lama → kembalikan ke masyarakat biasa
                User::where('id', $oldUserId)->update(['sekolah' => null]);
                // Tandai user baru sebagai siswa
                User::where('id', $newUserId)->update(['sekolah' => 'siswa']);
            }

            $siswa->update([
                'id_user'    => $newUserId,
                'id_sekolah' => $validated['id_sekolah'],
                'nis'        => $validated['nis'] ?? null,
                'kelas'      => $validated['kelas'] ?? null,
            ]);
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────
    public function destroy(Siswa $siswa)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);
        $this->gate($siswa, $info);

        DB::transaction(function () use ($siswa) {
            $userId = $siswa->id_user;
            $siswa->delete();

            $user = User::find($userId);
            if ($user) {
                // Kembalikan ke masyarakat biasa
                if (! $user->masyarakat) {
                    $user->delete();
                } else {
                    $user->update(['sekolah' => null]);
                }
            }
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────
    // AJAX – sekolah by nagari
    // ─────────────────────────────────────────────────────────
    public function ajaxSekolahByNagari(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        $idNagari = (int) $request->id_nagari;

        if ($isNagari && $idNagari !== (int) $idNagariUser) {
            return response()->json([]);
        }

        return response()->json(
            Sekolah::where('id_nagari', $idNagari)
                ->where('status', 'aktif')
                ->orderBy('nama_sekolah')
                ->get(['id_sekolah', 'nama_sekolah', 'jenjang'])
        );
    }

    // ─────────────────────────────────────────────────────────
    // AJAX – cari masyarakat tersedia (Select2)
    //
    // Filter:
    //   - role = masyarakat
    //   - sekolah IS NULL  (bukan admin/siswa manapun)
    //   - belum punya record siswa
    //
    // Parameter GET opsional: ?exclude_user_id=X
    //   → digunakan di halaman EDIT agar user saat ini
    //     tetap muncul sebagai pilihan walaupun sekolah='siswa'
    // ─────────────────────────────────────────────────────────
    public function ajaxMasyarakat(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $q             = $request->get('q', '');
        $excludeUserId = (int) $request->get('exclude_user_id', 0);

        $query = User::where('role', 'masyarakat')
            ->where(function ($sub) use ($excludeUserId) {
                // Masyarakat bebas
                $sub->where(function ($free) {
                    $free->whereNull('sekolah')
                        ->whereDoesntHave('siswa');
                });
                // Khusus edit: sertakan user yang sedang terhubung ke siswa ini
                if ($excludeUserId) {
                    $sub->orWhere('id', $excludeUserId);
                }
            })
            ->with('masyarakat');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nip_nik', 'like', "%$q%")
                    ->orWhereHas(
                        'masyarakat',
                        fn($m) => $m->where('nama_masyarakat', 'like', "%$q%")
                    );
            });
        }

        return response()->json(
            $query->limit(30)->get()->map(fn($u) => [
                'id'   => $u->id,
                'text' => ($u->masyarakat?->nama_masyarakat ?? $u->nip_nik)
                    . ' — ' . $u->nip_nik,
            ])
        );
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────

    private function allowedSekolahIds(array $info): array
    {
        [
            'isSuperAdmin'   => $isSuperAdmin,
            'isNagari'       => $isNagari,
            'isAdminSekolah' => $isAdminSekolah,
            'idNagariUser'   => $idNagariUser,
            'sekolahAdmin'   => $sekolahAdmin,
        ] = $info;

        if ($isSuperAdmin)   return Sekolah::pluck('id_sekolah')->toArray();
        if ($isNagari)       return Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah')->toArray();
        if ($isAdminSekolah) return [(int) $sekolahAdmin?->id_sekolah];
        return [];
    }

    private function gate(Siswa $siswa, array $info): void
    {
        [
            'isSuperAdmin'   => $isSuperAdmin,
            'isNagari'       => $isNagari,
            'isAdminSekolah' => $isAdminSekolah,
            'idNagariUser'   => $idNagariUser,
            'sekolahAdmin'   => $sekolahAdmin,
        ] = $info;

        if ($isSuperAdmin) return;

        if ($isNagari) {
            $ids = Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah')->toArray();
            abort_unless(in_array($siswa->id_sekolah, $ids), 403);
            return;
        }

        if ($isAdminSekolah) {
            abort_unless((int) $siswa->id_sekolah === (int) $sekolahAdmin?->id_sekolah, 403);
            return;
        }

        abort(403);
    }
}
