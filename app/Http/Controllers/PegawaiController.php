<?php

namespace App\Http\Controllers;

use App\Models\Nagari;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // HELPER: resolve "tipe" (dari form) → kolom DB
    // ─────────────────────────────────────────────────────────────
    //
    // Tipe di form          | role (users)  | jabatan_nagari (pegawai) | id_nagari
    // ----------------------|---------------|--------------------------|----------
    // camat                 | camat         | NULL                     | NULL
    // staf_camat            | pegawai       | NULL                     | NULL
    // kepala_nagari         | pegawai       | kepala_nagari            | <id>
    // pegawai_nagari        | pegawai       | pegawai_nagari           | <id>
    //
    // Catatan: enum DB adalah ('kepala_nagari','pegawai_nagari') — BUKAN 'staf_nagari'

    private function resolveDbValues(string $tipe, ?int $idNagari): array
    {
        return match ($tipe) {
            'camat'          => ['role' => 'camat',   'jabatan_nagari' => null,             'id_nagari' => null],
            'staf_camat'     => ['role' => 'pegawai', 'jabatan_nagari' => null,             'id_nagari' => null],
            'kepala_nagari'  => ['role' => 'pegawai', 'jabatan_nagari' => 'kepala_nagari',  'id_nagari' => $idNagari],
            'pegawai_nagari' => ['role' => 'pegawai', 'jabatan_nagari' => 'pegawai_nagari', 'id_nagari' => $idNagari],
            default          => ['role' => 'pegawai', 'jabatan_nagari' => null,             'id_nagari' => null],
        };
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER: tipe dari record pegawai yang ada
    // ─────────────────────────────────────────────────────────────
    private function tipeFromRecord(Pegawai $p): string
    {
        if ($p->role === 'camat')                             return 'camat';
        if ($p->role === 'pegawai' && is_null($p->id_nagari)) return 'staf_camat';
        if ($p->jabatan_nagari === 'kepala_nagari')           return 'kepala_nagari';
        return 'pegawai_nagari';
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER: tipe-tipe yang boleh dibuat oleh actor yang login
    // ─────────────────────────────────────────────────────────────
    private function allowedTipes(string $rl): array
    {
        return match ($rl) {
            'camat'      => ['camat', 'staf_camat', 'kepala_nagari', 'pegawai_nagari'],
            'staf_camat' => ['kepala_nagari', 'pegawai_nagari'],
            'wali_nagari' => ['pegawai_nagari'],   // hanya pegawai di nagarinya sendiri
            default      => [],
        };
    }

    // ─────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        /** @var User $auth */
        $auth = Auth::user();
        $rl   = $auth->getRoleLabel();
        $ap   = $auth->pegawai;

        $query = Pegawai::with(['user', 'nagari'])->orderBy('nama_pegawai');

        // Batasi scope berdasarkan role
        if ($rl === 'wali_nagari') {
            // Kepala nagari hanya melihat pegawai di nagarinya sendiri
            $query->where('id_nagari', $ap->id_nagari);
        } elseif ($rl === 'staf_camat') {
            // Staf camat hanya melihat pegawai nagari (bukan camat/staf_camat)
            $query->whereNotNull('id_nagari');
        }
        // camat → melihat semua

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pegawai', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tipe
        if ($request->filled('tipe')) {
            $tipe = $request->tipe;
            if ($tipe === 'camat') {
                $query->where('role', 'camat');
            } elseif ($tipe === 'staf_camat') {
                $query->where('role', 'pegawai')->whereNull('id_nagari');
            } elseif ($tipe === 'kepala_nagari') {
                $query->where('jabatan_nagari', 'kepala_nagari');
            } elseif ($tipe === 'pegawai_nagari') {
                $query->where('jabatan_nagari', 'pegawai_nagari');
            }
        }

        $pegawais     = $query->paginate(15)->withQueryString();
        $allowedTipes = $this->allowedTipes($rl);

        // Hitung statistik — camat tidak di-count (hanya 1, bukan kumpulan)
        $totalPegawaiKecamatan = Pegawai::where('role', 'pegawai')->whereNull('id_nagari')->count();
        $totalKepala           = Pegawai::where('jabatan_nagari', 'kepala_nagari')->count();
        $totalPegawaiNagari    = Pegawai::where('jabatan_nagari', 'pegawai_nagari')->count();

        // Nagari list untuk filter (hanya dibutuhkan camat & staf_camat)
        $nagaris = in_array($rl, ['camat', 'staf_camat'])
            ? Nagari::where('status', 1)->orderBy('nama_nagari')->get()
            : collect();

        return view('pages.camat.pegawai.index', compact(
            'pegawais',
            'rl',
            'ap',
            'allowedTipes',
            'totalPegawaiKecamatan',
            'totalKepala',
            'totalPegawaiNagari',
            'nagaris'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        /** @var User $auth */
        $auth = Auth::user();
        $rl   = $auth->getRoleLabel();
        $ap   = $auth->pegawai;

        $allowedTipes = $this->allowedTipes($rl);
        if (empty($allowedTipes)) {
            abort(403, 'Anda tidak berhak menambahkan pegawai.');
        }

        $nagaris        = Nagari::where('status', 1)->orderBy('nama_nagari')->get();
        $camatExists    = Pegawai::where('role', 'camat')->exists();
        $nagariWithKepala = Pegawai::where('jabatan_nagari', 'kepala_nagari')
            ->pluck('id_nagari')
            ->filter()
            ->toArray();

        // Kepala nagari: nagari dikunci ke nagarinya sendiri
        $lockedNagari = ($rl === 'wali_nagari') ? $ap->id_nagari : null;

        return view('pages.camat.pegawai.create', compact(
            'rl',
            'ap',
            'allowedTipes',
            'nagaris',
            'camatExists',
            'nagariWithKepala',
            'lockedNagari'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        /** @var User $auth */
        $auth = Auth::user();
        $rl   = $auth->getRoleLabel();

        $allowedTipes = $this->allowedTipes($rl);
        if (empty($allowedTipes)) abort(403);

        // wali_nagari: tipe & nagari dipaksa otomatis — tidak perlu dari form
        $isWali   = $rl === 'wali_nagari';
        $tipe     = $isWali ? 'pegawai_nagari' : $request->tipe;
        $idNagari = $isWali ? $auth->pegawai->id_nagari : $request->id_nagari;

        $rules = [
            'nama_pegawai'    => 'required|string|max:255',
            // NIP harus unik di KEDUA tabel: users.nip_nik dan pegawai.nip
            'nip'             => 'required|string|max:20|unique:users,nip_nik|unique:pegawai,nip',
            'nik'             => 'required|string|size:16|unique:pegawai,nik',
            'jenis_kelamin'   => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'alamat_pegawai'  => 'nullable|string',
            'nohp_pegawai'    => 'nullable|string|max:20',
            'email_pegawai'   => 'nullable|email|max:100',
            'deskripsi'       => 'nullable|string',
            'instagram'       => 'nullable|url|max:255',
            'twitter'         => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'foto_profil'     => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'password'        => 'required|string|min:8|confirmed',
        ];

        // wali_nagari tidak perlu kirim tipe & id_nagari dari form
        if (!$isWali) {
            $rules['tipe']     = ['required', Rule::in($allowedTipes)];
            $rules['id_nagari'] = in_array($tipe, ['kepala_nagari', 'pegawai_nagari'])
                ? ['required', 'exists:nagari,id']
                : ['nullable'];
        }

        $request->validate($rules);

        $dbValues = $this->resolveDbValues($tipe, $idNagari);

        // Cegah duplikat camat
        if ($tipe === 'camat' && Pegawai::where('role', 'camat')->exists()) {
            return back()->withErrors(['tipe' => 'Sudah ada camat. Hapus camat lama terlebih dahulu.'])->withInput();
        }

        // Cegah duplikat kepala_nagari di nagari yang sama
        if ($tipe === 'kepala_nagari' && $idNagari) {
            $existing = Pegawai::where('jabatan_nagari', 'kepala_nagari')->where('id_nagari', $idNagari)->exists();
            if ($existing) {
                return back()->withErrors(['id_nagari' => 'Nagari ini sudah memiliki Kepala Nagari.'])->withInput();
            }
        }

        DB::transaction(function () use ($request, $dbValues) {
            // 1. Buat user
            $user = User::create([
                'nip_nik'  => $request->nip,
                'password' => Hash::make($request->password),
                'role'     => $dbValues['role'],
                'status'   => 'aktif',
            ]);

            // 2. Upload foto
            $fotoPath = 'pegawai/default.jpg';
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('pegawai', 'public');
            }

            // 3. Buat pegawai
            Pegawai::create([
                'id_user'         => $user->id,
                'role'            => $dbValues['role'],
                'nama_pegawai'    => $request->nama_pegawai,
                'nip'             => $request->nip,
                'nik'             => $request->nik,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan'         => $request->jabatan,
                'alamat_pegawai'  => $request->alamat_pegawai,
                'nohp_pegawai'    => $request->nohp_pegawai,
                'email_pegawai'   => $request->email_pegawai,
                'deskripsi'       => $request->deskripsi,
                'instagram'       => $request->instagram,
                'twitter'         => $request->twitter,
                'facebook'        => $request->facebook,
                'foto_profil'     => $fotoPath,
                'id_nagari'       => $dbValues['id_nagari'],
                'jabatan_nagari'  => $dbValues['jabatan_nagari'],
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────
    public function show($id)
    {
        $pegawai = Pegawai::with([
            'user',
            'nagari',
            'konten',
            'balasanpengaduan.pengaduan',
            'pelayananadministrasi.masyarakat',
        ])->findOrFail($id);

        return view('pages.camat.pegawai.show', compact('pegawai'));
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────
    public function edit($id)
    {
        /** @var User $auth */
        $auth    = Auth::user();
        $rl      = $auth->getRoleLabel();
        $ap      = $auth->pegawai;
        $pegawai = Pegawai::with(['user', 'nagari'])->findOrFail($id);

        // Cek izin edit
        $canEdit = match ($rl) {
            'camat'        => true,
            'staf_camat'   => !is_null($pegawai->id_nagari),
            'wali_nagari'  => $pegawai->id_nagari == $ap->id_nagari
                && $pegawai->jabatan_nagari === 'pegawai_nagari',
            default        => false,
        };
        if (!$canEdit) abort(403);

        $currentTipe    = $this->tipeFromRecord($pegawai);
        $nagaris        = Nagari::where('status', 1)->orderBy('nama_nagari')->get();
        $nagariWithKepala = Pegawai::where('jabatan_nagari', 'kepala_nagari')
            ->where('id_pegawai', '!=', $pegawai->id_pegawai)
            ->pluck('id_nagari')
            ->filter()
            ->toArray();

        $lockedNagari = ($rl === 'wali_nagari') ? $ap->id_nagari : null;

        return view('pages.camat.pegawai.edit', compact(
            'pegawai',
            'rl',
            'ap',
            'currentTipe',
            'nagaris',
            'nagariWithKepala',
            'lockedNagari'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        /** @var User $auth */
        $auth    = Auth::user();
        $rl      = $auth->getRoleLabel();
        $ap      = $auth->pegawai;
        $pegawai = Pegawai::with('user')->findOrFail($id);

        // Cek izin
        $canEdit = match ($rl) {
            'camat'       => true,
            'staf_camat'  => !is_null($pegawai->id_nagari),
            'wali_nagari' => $pegawai->id_nagari == $ap->id_nagari
                && $pegawai->jabatan_nagari === 'pegawai_nagari',
            default       => false,
        };
        if (!$canEdit) abort(403);

        $currentTipe  = $this->tipeFromRecord($pegawai);
        $allowedTipes = $this->allowedTipes($rl);

        // Tipe yang tersedia untuk update (jika tidak diubah, gunakan currentTipe)
        $newTipe = $request->input('tipe', $currentTipe);

        // Validasi tipe hanya jika ada perubahan dan actor boleh mengubahnya
        if ($newTipe !== $currentTipe && !in_array($newTipe, $allowedTipes)) {
            return back()->withErrors(['tipe' => 'Tipe tidak diizinkan.'])->withInput();
        }

        $request->validate([
            'nama_pegawai'    => 'required|string|max:255',
            'nip'             => ['required', 'string', 'max:20', Rule::unique('users', 'nip_nik')->ignore($pegawai->user->id)],
            'nik'             => ['required', 'string', 'size:16', Rule::unique('pegawai', 'nik')->ignore($pegawai->id_pegawai, 'id_pegawai')],
            'jenis_kelamin'   => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'alamat_pegawai'  => 'nullable|string',
            'nohp_pegawai'    => 'nullable|string|max:20',
            'email_pegawai'   => 'nullable|email|max:100',
            'deskripsi'       => 'nullable|string',
            'instagram'       => 'nullable|url|max:255',
            'twitter'         => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'foto_profil'     => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'password'        => 'nullable|string|min:8|confirmed',
            'status'          => ['required', Rule::in(['aktif', 'nonaktif'])],
            'id_nagari'       => [
                Rule::requiredIf(in_array($newTipe, ['kepala_nagari', 'pegawai_nagari'])),
                'nullable',
                'exists:nagari,id',
            ],
        ]);

        $idNagari = $request->id_nagari;
        if ($rl === 'wali_nagari') {
            $idNagari = $ap->id_nagari;
        }

        $dbValues = $this->resolveDbValues($newTipe, $idNagari);

        // Cegah duplikat kepala_nagari (kecuali pegawai ini sendiri)
        if ($newTipe === 'kepala_nagari' && $idNagari) {
            $exists = Pegawai::where('jabatan_nagari', 'kepala_nagari')
                ->where('id_nagari', $idNagari)
                ->where('id_pegawai', '!=', $pegawai->id_pegawai)
                ->exists();
            if ($exists) {
                return back()->withErrors(['id_nagari' => 'Nagari ini sudah memiliki Kepala Nagari.'])->withInput();
            }
        }

        DB::transaction(function () use ($request, $pegawai, $dbValues) {
            // Update user
            $userData = [
                'nip_nik' => $request->nip,
                'role'    => $dbValues['role'],
                'status'  => $request->status,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $pegawai->user->update($userData);

            // Upload foto baru jika ada
            if ($request->hasFile('foto_profil')) {
                if ($pegawai->foto_profil && $pegawai->foto_profil !== 'pegawai/default.jpg') {
                    Storage::disk('public')->delete($pegawai->foto_profil);
                }
                $fotoPath = $request->file('foto_profil')->store('pegawai', 'public');
            } else {
                $fotoPath = $pegawai->foto_profil;
            }

            // Update pegawai
            $pegawai->update([
                'role'            => $dbValues['role'],
                'nama_pegawai'    => $request->nama_pegawai,
                'nip'             => $request->nip,
                'nik'             => $request->nik,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan'         => $request->jabatan,
                'alamat_pegawai'  => $request->alamat_pegawai,
                'nohp_pegawai'    => $request->nohp_pegawai,
                'email_pegawai'   => $request->email_pegawai,
                'deskripsi'       => $request->deskripsi,
                'instagram'       => $request->instagram,
                'twitter'         => $request->twitter,
                'facebook'        => $request->facebook,
                'foto_profil'     => $fotoPath,
                'id_nagari'       => $dbValues['id_nagari'],
                'jabatan_nagari'  => $dbValues['jabatan_nagari'],
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        /** @var User $auth */
        $auth    = Auth::user();
        $rl      = $auth->getRoleLabel();
        $ap      = $auth->pegawai;
        $pegawai = Pegawai::with('user')->findOrFail($id);

        $canDelete = match ($rl) {
            'camat'       => $pegawai->id_pegawai !== $ap?->id_pegawai, // camat tidak bisa hapus dirinya sendiri
            'staf_camat'  => !is_null($pegawai->id_nagari),
            'wali_nagari' => $pegawai->id_nagari == $ap->id_nagari
                && $pegawai->jabatan_nagari === 'pegawai_nagari',
            default       => false,
        };
        if (!$canDelete) abort(403);

        DB::transaction(function () use ($pegawai) {
            if ($pegawai->foto_profil && $pegawai->foto_profil !== 'pegawai/default.jpg') {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            $pegawai->user->delete(); // cascade ke pegawai via FK
        });

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
