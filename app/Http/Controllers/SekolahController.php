<?php

namespace App\Http\Controllers;

use App\Models\Nagari;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    /**
     * Ambil nagari milik user yang sedang login.
     * Camat → null (bisa pilih semua nagari)
     * Pegawai nagari → id_nagari dari relasi pegawai
     */
    private function getNagariUser(): ?int
    {
        $user = Auth::user();

        if ($user->role === 'camat') {
            return null; // camat bisa pilih semua nagari
        }

        if ($user->role === 'pegawai') {
            $pegawai = $user->pegawai;
            return $pegawai?->id_nagari; // null jika staf camat, ada jika staf/kepala nagari
        }

        return null;
    }

    /**
     * Cek apakah user adalah camat atau staf camat (superadmin level kecamatan)
     */
    private function isSuperAdmin(): bool
    {
        return Auth::user()->isSuperAdmin();
    }

    /**
     * Cek apakah user adalah pegawai nagari (kepala/staf nagari)
     */
    private function isPegawaiNagari(): bool
    {
        $user = Auth::user();
        if ($user->role !== 'pegawai') return false;
        $pegawai = $user->pegawai;
        return $pegawai && !is_null($pegawai->id_nagari);
    }

    // ─────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $user       = Auth::user();
        $roleLabel  = $user->getRoleLabel();
        $nagariId   = $this->getNagariUser();

        $query = Sekolah::with(['nagari', 'user']);

        // Jika pegawai nagari → hanya tampilkan sekolah di nagarinya
        if ($this->isPegawaiNagari() && $nagariId) {
            $query->where('id_nagari', $nagariId);
        }

        // Filter pencarian
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter nagari (hanya camat yang bisa filter lintas nagari)
        if ($filterNagari = $request->get('id_nagari')) {
            $query->where('id_nagari', $filterNagari);
        }

        // Filter jenjang
        if ($jenjang = $request->get('jenjang')) {
            $query->where('jenjang', $jenjang);
        }

        // Filter status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sekolah = $query->latest()->paginate(10)->withQueryString();
        $nagaris  = Nagari::where('status', 'aktif')->orderBy('nama_nagari')->get();

        // Statistik
        $baseQuery = Sekolah::query();
        if ($this->isPegawaiNagari() && $nagariId) {
            $baseQuery->where('id_nagari', $nagariId);
        }

        $stats = [
            'total'  => (clone $baseQuery)->count(),
            'aktif'  => (clone $baseQuery)->where('status', 'aktif')->count(),
            'nonaktif' => (clone $baseQuery)->where('status', 'nonaktif')->count(),
        ];

        return view('pages.sekolah.index', compact(
            'sekolah',
            'nagaris',
            'stats',
            'roleLabel'
        ));
    }

    // ─────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────
    public function create()
    {
        $user      = Auth::user();
        $roleLabel = $user->getRoleLabel(); // 'camat' | 'staf_camat' | 'wali_nagari' | 'staf_nagari'
        $nagariId  = $this->getNagariUser();

        // Camat & staf camat → bisa pilih nagari bebas
        // Pegawai nagari (wali/staf nagari) → nagari sudah terkunci
        $bisaPilihNagari = in_array($roleLabel, ['camat', 'staf_camat']);

        $nagaris = $bisaPilihNagari
            ? Nagari::orderBy('nama_nagari')->get()  // semua nagari, tanpa filter status
            : collect();

        $nagariTerpilih = $nagariId
            ? Nagari::find($nagariId)
            : null;

        // Jika camat: userMasyarakat kosong dulu, akan dimuat via AJAX saat nagari dipilih
        // Jika pegawai nagari: langsung load masyarakat di nagarinya
        $userMasyarakat = $bisaPilihNagari
            ? collect()
            : $this->getUserKepalaSekolahOptions($nagariId);

        return view('pages.sekolah.create', compact(
            'nagaris',
            'nagariTerpilih',
            'userMasyarakat',
            'roleLabel',
            'bisaPilihNagari'
        ));
    }

    // ─────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $user     = Auth::user();
        $nagariId = $this->getNagariUser();

        // Tentukan nagari yang akan dipakai
        $idNagari = $this->isPegawaiNagari() && $nagariId
            ? $nagariId
            : $request->input('id_nagari');

        $request->validate([
            'id_nagari'    => $this->isPegawaiNagari() ? 'nullable' : 'required|exists:nagari,id',
            'id_user'      => 'required|exists:users,id',
            'nama_sekolah' => 'required|string|max:255',
            'npsn'         => 'nullable|string|max:20|unique:sekolah,npsn',
            'jenjang'      => 'required|in:SD,SMP,SMA,SMK,MI,MTs,MA,TK,PAUD',
            'alamat'       => 'nullable|string|max:500',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'       => 'required|in:aktif,nonaktif',
        ], [
            'id_nagari.required'    => 'Nagari wajib dipilih.',
            'id_user.required'      => 'Kepala Sekolah / Administrator wajib dipilih.',
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'jenjang.required'      => 'Jenjang pendidikan wajib dipilih.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('sekolah/logo', 'public');
        }

        Sekolah::create([
            'id_user'      => $request->id_user,
            'id_nagari'    => $idNagari,
            'nama_sekolah' => $request->nama_sekolah,
            'npsn'         => $request->npsn,
            'jenjang'      => $request->jenjang,
            'alamat'       => $request->alamat,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'logo'         => $logoPath,
            'status'       => $request->status,
        ]);

        return redirect()->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────
    public function show(Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);
        $sekolah->load(['nagari', 'user', 'siswa', 'mading']);
        $roleLabel = Auth::user()->getRoleLabel();

        return view('pages.sekolah.show', compact('sekolah', 'roleLabel'));
    }

    // ─────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────
    public function edit(Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);

        $user      = Auth::user();
        $roleLabel = $user->getRoleLabel();
        $nagariId  = $this->getNagariUser();

        $bisaPilihNagari = in_array($roleLabel, ['camat', 'staf_camat']);

        $nagaris = $bisaPilihNagari
            ? Nagari::orderBy('nama_nagari')->get()
            : collect();

        $nagariTerpilih = $nagariId
            ? Nagari::find($nagariId)
            : null;

        $userMasyarakat = $bisaPilihNagari
            ? collect()
            : $this->getUserKepalaSekolahOptions($nagariId, $sekolah->id_sekolah);

        return view('pages.sekolah.edit', compact(
            'sekolah',
            'nagaris',
            'nagariTerpilih',
            'userMasyarakat',
            'roleLabel',
            'bisaPilihNagari'
        ));
    }

    // ─────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);

        $nagariId = $this->getNagariUser();
        $idNagari = $this->isPegawaiNagari() && $nagariId
            ? $nagariId
            : $request->input('id_nagari');

        $request->validate([
            'id_nagari'    => $this->isPegawaiNagari() ? 'nullable' : 'required|exists:nagari,id',
            'id_user'      => 'required|exists:users,id',
            'nama_sekolah' => 'required|string|max:255',
            'npsn'         => 'nullable|string|max:20|unique:sekolah,npsn,' . $sekolah->id_sekolah . ',id_sekolah',
            'jenjang'      => 'required|in:SD,SMP,SMA,SMK,MI,MTs,MA,TK,PAUD',
            'alamat'       => 'nullable|string|max:500',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $logoPath = $sekolah->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath) Storage::disk('public')->delete($logoPath);
            $logoPath = $request->file('logo')->store('sekolah/logo', 'public');
        }

        // Hapus logo jika dicentang
        if ($request->boolean('hapus_logo') && $logoPath) {
            Storage::disk('public')->delete($logoPath);
            $logoPath = null;
        }

        $sekolah->update([
            'id_user'      => $request->id_user,
            'id_nagari'    => $idNagari,
            'nama_sekolah' => $request->nama_sekolah,
            'npsn'         => $request->npsn,
            'jenjang'      => $request->jenjang,
            'alamat'       => $request->alamat,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'logo'         => $logoPath,
            'status'       => $request->status,
        ]);

        return redirect()->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────
    public function destroy(Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);

        if ($sekolah->logo) {
            Storage::disk('public')->delete($sekolah->logo);
        }

        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }

    // ─────────────────────────────────────────────
    // TOGGLE STATUS (AJAX)
    // ─────────────────────────────────────────────
    public function toggleStatus(Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);

        $sekolah->update([
            'status' => $sekolah->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'status'  => $sekolah->status,
            'message' => 'Status berhasil diperbarui.',
        ]);
    }

    // ─────────────────────────────────────────────
    // AJAX: Load user masyarakat saat nagari dipilih (untuk camat)
    // ─────────────────────────────────────────────
    public function getUserByNagari(Request $request)
    {
        $idNagari   = $request->get('id_nagari');
        $idSekolah  = $request->get('id_sekolah'); // untuk edit, exclude sekolah sekarang

        $usedUserIds = Sekolah::when($idSekolah, fn($q) => $q->where('id_sekolah', '!=', $idSekolah))
            ->pluck('id_user')
            ->toArray();

        $users = User::where('role', 'masyarakat')
            ->whereNotIn('id', $usedUserIds)
            ->when($idNagari, function ($q) use ($idNagari) {
                $q->whereHas('masyarakat', fn($q2) => $q2->where('id_nagari', $idNagari));
            })
            ->with('masyarakat') // eager load agar nama_masyarakat tersedia
            ->get()
            ->map(fn($u) => [
                'id'              => $u->id,
                'nip_nik'         => $u->nip_nik,
                'nama_masyarakat' => $u->masyarakat?->nama_masyarakat ?? '',
            ]);

        return response()->json($users);
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    /**
     * Pastikan pegawai nagari hanya bisa akses sekolah di nagarinya.
     */
    private function authorizeNagari(Sekolah $sekolah): void
    {
        if ($this->isPegawaiNagari()) {
            $nagariId = $this->getNagariUser();
            if ($nagariId && $sekolah->id_nagari !== $nagariId) {
                abort(403, 'Anda tidak memiliki akses ke data sekolah ini.');
            }
        }
    }

    /**
     * Ambil daftar user masyarakat yang bisa menjadi kepala/admin sekolah.
     * Exclude user yang sudah punya sekolah lain (kecuali sekolah yang sedang diedit).
     */
    private function getUserKepalaSekolahOptions(?int $nagariId, ?int $excludeSekolahId = null)
    {
        $usedUserIds = Sekolah::when($excludeSekolahId, fn($q) => $q->where('id_sekolah', '!=', $excludeSekolahId))
            ->pluck('id_user')
            ->toArray();

        return User::where('role', 'masyarakat')
            ->whereNotIn('id', $usedUserIds)
            ->when($nagariId, function ($q) use ($nagariId) {
                $q->whereHas('masyarakat', fn($q2) => $q2->where('id_nagari', $nagariId));
            })
            ->with('masyarakat') // eager load agar nama_masyarakat tersedia di blade
            ->get();
    }
}
