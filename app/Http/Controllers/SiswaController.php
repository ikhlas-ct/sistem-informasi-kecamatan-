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
    //
    //   camat / staf_camat        → isSuperAdmin
    //   wali_nagari / staf_nagari → isNagari
    //   admin_sekolah             → isAdminSekolah
    //                               (role=masyarakat, sekolah='admin')
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

        // Scope data per role
        if ($isAdminSekolah) {
            $query->where('id_sekolah', $sekolahAdmin?->id_sekolah);
        } elseif ($isNagari) {
            $ids = Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah');
            $query->whereIn('id_sekolah', $ids);
        }

        // Filter pencarian
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

        // Statistik
        $statQ = Siswa::query();
        if ($isAdminSekolah) {
            $statQ->where('id_sekolah', $sekolahAdmin?->id_sekolah);
        } elseif ($isNagari) {
            $ids = Sekolah::where('id_nagari', $idNagariUser)->pluck('id_sekolah');
            $statQ->whereIn('id_sekolah', $ids);
        }
        $totalSiswa   = $statQ->count();
        $totalPerKelas = (clone $statQ)
            ->selectRaw('kelas, count(*) as total')
            ->whereNotNull('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas');

        // Dropdown filter
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

        $masyarakatList = $this->getMasyarakatTersedia();
        $nagariAllList  = Nagari::orderBy('nama_nagari')->get(); // untuk dropdown nagari di form buat baru

        return view('pages.siswa.create', compact(
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah',
            'nagariList',
            'sekolahLocked',
            'nagariLocked',
            'masyarakatList',
            'nagariAllList'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $allowedSekolahIds = $this->allowedSekolahIds($info);
        $mode = $request->input('mode_akun', 'pilih'); // 'pilih' | 'baru'

        // ── Aturan validasi umum ──
        $rules = [
            'mode_akun'  => 'required|in:pilih,baru',
            'id_sekolah' => ['required', Rule::in($allowedSekolahIds)],
            'nis'        => 'nullable|string|max:20|unique:siswa,nis',
            'kelas'      => 'nullable|string|max:20',
        ];

        if ($mode === 'pilih') {
            // Pilih masyarakat yang sudah ada
            $rules['id_user_masyarakat'] = [
                'required',
                'exists:users,id',
                // Pastikan user dipilih adalah masyarakat bukan admin sekolah
                Rule::exists('users', 'id')->where(
                    fn($q) =>
                    $q->where('role', 'masyarakat')
                        ->where(fn($q2) => $q2->whereNull('sekolah')->orWhere('sekolah', 'siswa'))
                ),
            ];
        } else {
            // Buat akun + data masyarakat baru
            $rules['nik']              = 'required|string|size:16|unique:users,nip_nik|unique:masyarakat,nik';
            $rules['password']         = 'required|string|min:6|confirmed';
            $rules['nama_masyarakat']  = 'required|string|max:100';
            $rules['kk']               = 'required|string|max:16';
            $rules['no_hp']            = 'nullable|string|max:20';
            $rules['jenis_kelamin']    = 'nullable|in:laki-laki,perempuan';
            $rules['id_nagari_masy']   = 'nullable|exists:nagari,id';
            $rules['foto_profil']      = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request, $mode) {
            if ($mode === 'pilih') {
                // ── Gunakan user masyarakat yang ada ──
                $idUser = (int) $validated['id_user_masyarakat'];
                User::where('id', $idUser)->update(['sekolah' => 'siswa']);
            } else {
                // ── Buat user baru ──
                $newUser = User::create([
                    'nip_nik'  => $validated['nik'],
                    'password' => Hash::make($validated['password']),
                    'role'     => 'masyarakat',
                    'sekolah'  => 'siswa',
                    'status'   => 'aktif',
                ]);
                $idUser = $newUser->id;

                // ── Buat data masyarakat ──
                $foto = null;
                if ($request->hasFile('foto_profil')) {
                    $foto = $request->file('foto_profil')
                        ->store('foto_profil/masyarakat', 'public');
                }

                Masyarakat::create([
                    'id_user'         => $idUser,
                    'nik'             => $validated['nik'],
                    'kk'              => $validated['kk'],
                    'nama_masyarakat' => $validated['nama_masyarakat'],
                    'no_hp'           => $validated['no_hp'] ?? null,
                    'jenis_kelamin'   => $validated['jenis_kelamin'] ?? null,
                    'id_nagari'       => $validated['id_nagari_masy'] ?? null,
                    'foto_profil'     => $foto,
                ]);
            }

            // ── Buat record siswa ──
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

        $masyarakatList = $this->getMasyarakatTersedia($siswa->id_user);
        $nagariAllList  = Nagari::orderBy('nama_nagari')->get();

        return view('pages.siswa.edit', compact(
            'siswa',
            'roleLabel',
            'isSuperAdmin',
            'isNagari',
            'isAdminSekolah',
            'nagariList',
            'sekolahLocked',
            'nagariLocked',
            'masyarakatList',
            'nagariAllList'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────
    public function update(Request $request, Siswa $siswa)
    {
        $info = $this->getRoleInfo();
        extract($info);

        $this->gate($siswa, $info);

        $allowedSekolahIds = $this->allowedSekolahIds($info);

        $validated = $request->validate([
            'id_sekolah'     => ['required', Rule::in($allowedSekolahIds)],
            'nis'            => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')->ignore($siswa->id_siswa, 'id_siswa')
            ],
            'kelas'          => 'nullable|string|max:20',
            // Update data masyarakat (opsional, bisa dikosongkan)
            'nama_masyarakat' => 'nullable|string|max:100',
            'no_hp'          => 'nullable|string|max:20',
            'jenis_kelamin'  => 'nullable|in:laki-laki,perempuan',
            'id_nagari_masy' => 'nullable|exists:nagari,id',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto'     => 'nullable|boolean',
            'password'       => 'nullable|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($validated, $request, $siswa) {
            // Update password jika diisi
            if (! empty($validated['password'])) {
                $siswa->user->update(['password' => Hash::make($validated['password'])]);
            }

            // Update data masyarakat
            $masyarakat = $siswa->user?->masyarakat;
            if ($masyarakat) {
                $foto = $masyarakat->foto_profil;

                if ($request->boolean('hapus_foto') && $foto) {
                    Storage::disk('public')->delete($foto);
                    $foto = null;
                }
                if ($request->hasFile('foto_profil')) {
                    if ($foto) Storage::disk('public')->delete($foto);
                    $foto = $request->file('foto_profil')
                        ->store('foto_profil/masyarakat', 'public');
                }

                $masyarakatData = array_filter([
                    'nama_masyarakat' => $validated['nama_masyarakat'] ?? $masyarakat->nama_masyarakat,
                    'no_hp'           => $validated['no_hp'] ?? $masyarakat->no_hp,
                    'jenis_kelamin'   => $validated['jenis_kelamin'] ?? $masyarakat->jenis_kelamin,
                    'id_nagari'       => $validated['id_nagari_masy'] ?? $masyarakat->id_nagari,
                    'foto_profil'     => $foto,
                ], fn($v) => ! is_null($v));

                $masyarakat->update($masyarakatData);
            }

            // Update data siswa
            $siswa->update([
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
                // Jika tidak ada data masyarakat (akun dibuat khusus saat daftar siswa), hapus user
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
    // AJAX – cari masyarakat tersedia (belum jadi siswa)
    // ─────────────────────────────────────────────────────────
    public function ajaxMasyarakat(Request $request)
    {
        $info = $this->getRoleInfo();
        extract($info);

        abort_if(! $isSuperAdmin && ! $isNagari && ! $isAdminSekolah, 403);

        $q = $request->get('q', '');

        // Hanya user dengan:
        //   role = masyarakat
        //   sekolah IS NULL  → belum jadi admin ('admin') maupun siswa ('siswa')
        //   belum punya record di tabel siswa
        $query = User::where('role', 'masyarakat')
            ->whereNull('sekolah')
            ->whereDoesntHave('siswa')
            ->with('masyarakat');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nip_nik', 'like', "%$q%")
                    ->orWhereHas(
                        'masyarakat',
                        fn($m) =>
                        $m->where('nama_masyarakat', 'like', "%$q%")
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

    /**
     * Daftar user masyarakat yang tersedia untuk dijadikan siswa.
     *
     * Syarat agar user BISA dipilih:
     *   1. role = 'masyarakat'
     *   2. kolom sekolah IS NULL
     *        → sekolah = 'admin' berarti sudah jadi admin sekolah   → TIDAK boleh dipilih
     *        → sekolah = 'siswa' berarti sudah jadi siswa aktif     → TIDAK boleh dipilih
     *        → sekolah IS NULL   berarti masyarakat biasa           → BOLEH dipilih
     *   3. Belum punya record di tabel siswa (double check)
     *
     * $excludeUserId → khusus untuk halaman EDIT:
     *   user yang sedang terhubung ke siswa yang diedit tetap
     *   dimunculkan di dropdown meskipun sekolah-nya = 'siswa'.
     */
    private function getMasyarakatTersedia(?int $excludeUserId = null)
    {
        return User::where('role', 'masyarakat')
            ->where(function ($q) use ($excludeUserId) {
                // User yang masih "bebas" (sekolah IS NULL & belum punya siswa)
                $q->where(function ($free) {
                    $free->whereNull('sekolah')
                        ->whereDoesntHave('siswa');
                });

                // Khusus edit: sertakan juga user yang sedang terhubung ke siswa ini
                if ($excludeUserId) {
                    $q->orWhere('id', $excludeUserId);
                }
            })
            ->with('masyarakat')
            ->orderBy('id')
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'nama' => ($u->masyarakat?->nama_masyarakat ?? $u->nip_nik)
                    . ' (' . $u->nip_nik . ')',
            ]);
    }
}
