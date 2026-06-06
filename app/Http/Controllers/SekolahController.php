<?php

namespace App\Http\Controllers;

use App\Models\Nagari;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    // ─────────────────────────────────────────────
    // PRIVATE HELPERS ROLE
    // ─────────────────────────────────────────────

    /**
     * Ambil id_nagari milik user yang sedang login.
     *
     *  - Camat / Pegawai Kecamatan  → null  (akses semua nagari)
     *  - Pegawai Nagari              → id_nagari dari tabel pegawai
     *  - Admin Sekolah               → id_nagari dari tabel masyarakat miliknya
     */
    private function getNagariUser(): ?int
    {
        $user = Auth::user();

        if ($user->role === 'pegawai') {
            // null  = staf kecamatan (superadmin)
            // ada   = pegawai nagari
            return $user->pegawai?->id_nagari;
        }

        if ($user->isAdminSekolah()) {
            return $user->masyarakat?->id_nagari;
        }

        return null; // camat
    }

    /**
     * Apakah user adalah Camat atau Pegawai Kecamatan (superadmin)?
     *   Camat              → role = 'camat'
     *   Pegawai Kecamatan  → role = 'pegawai' && id_nagari IS NULL
     */
    private function isSuperAdmin(): bool
    {
        return Auth::user()->isSuperAdmin();
    }

    /**
     * Apakah user adalah Pegawai Nagari?
     *   → role = 'pegawai' dan memiliki id_nagari (bukan pegawai kecamatan)
     */
    private function isPegawaiNagari(): bool
    {
        $user = Auth::user();

        return $user->role === 'pegawai'
            && !is_null($user->pegawai?->id_nagari);
    }

    /**
     * Apakah user adalah Admin Sekolah?
     *   → role = 'masyarakat' && kolom sekolah = 'admin'
     *   (dulu role = 'sekolah', sekarang dipindah ke kolom sekolah)
     */
    private function isAdminSekolah(): bool
    {
        return Auth::user()->isAdminSekolah();
    }

    /**
     * Pastikan user boleh mengakses sekolah tertentu.
     *
     *  - Admin Sekolah  → hanya sekolah yang ia administrasikan (id_user = auth id)
     *  - Pegawai Nagari → hanya sekolah di nagarinya (id_nagari match)
     *  - Superadmin     → bebas (tidak dicek di sini)
     */
    private function authorizeNagari(Sekolah $sekolah): void
    {
        $user = Auth::user();

        // Admin sekolah: hanya boleh akses sekolah miliknya
        if ($this->isAdminSekolah()) {
            if ($sekolah->id_user !== $user->id) {
                abort(403, 'Anda hanya bisa mengakses sekolah Anda sendiri.');
            }
            return;
        }

        // Pegawai nagari: hanya sekolah di nagarinya
        if ($this->isPegawaiNagari()) {
            $nagariId = $this->getNagariUser();
            if ($nagariId && $sekolah->id_nagari !== $nagariId) {
                abort(403, 'Anda tidak memiliki akses ke data sekolah ini.');
            }
        }
    }

    /**
     * Daftar user masyarakat yang tersedia sebagai calon admin sekolah.
     *
     *  Syarat:
     *  - role = 'masyarakat'
     *  - kolom sekolah IS NULL  (belum jadi admin/siswa sekolah manapun)
     *  - id tidak ada di tabel sekolah (kecuali sekolah yang sedang diedit)
     *  - jika $nagariId diisi → filter sesuai nagari masyarakat
     */
    private function getUserKepalaSekolahOptions(?int $nagariId, ?int $excludeSekolahId = null)
    {
        // id_user yang sudah dipakai sekolah LAIN
        $usedUserIds = Sekolah::when(
            $excludeSekolahId,
            fn($q) => $q->where('id_sekolah', '!=', $excludeSekolahId)
        )->pluck('id_user')->toArray();

        return User::where('role', 'masyarakat')
            ->whereNull('sekolah')                    // belum berstatus admin/siswa sekolah
            ->whereNotIn('id', $usedUserIds)
            ->when($nagariId, function ($q) use ($nagariId) {
                $q->whereHas('masyarakat', fn($q2) => $q2->where('id_nagari', $nagariId));
            })
            ->with('masyarakat')
            ->get();
    }

    // ─────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $user      = Auth::user();
        $roleLabel = $user->getRoleLabel();
        $nagariId  = $this->getNagariUser();

        $query = Sekolah::with(['nagari', 'user.masyarakat']);

        // Batasi tampilan sesuai role:
        if ($this->isAdminSekolah()) {
            // Admin sekolah: hanya sekolah miliknya
            $query->where('id_user', $user->id);
        } elseif ($this->isPegawaiNagari() && $nagariId) {
            // Pegawai nagari: hanya sekolah di nagarinya
            $query->where('id_nagari', $nagariId);
        }
        // Superadmin (camat / pegawai kecamatan): semua sekolah → tidak difilter

        // ── Filter pencarian ──
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ── Filter nagari (hanya superadmin) ──
        if ($this->isSuperAdmin() && $filterNagari = $request->get('id_nagari')) {
            $query->where('id_nagari', $filterNagari);
        }

        // ── Filter jenjang ──
        if ($jenjang = $request->get('jenjang')) {
            $query->where('jenjang', $jenjang);
        }

        // ── Filter status ──
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sekolah = $query->latest()->paginate(10)->withQueryString();
        $nagaris = Nagari::orderBy('nama_nagari')->get();

        // ── Statistik (scope mengikuti batasan role) ──
        $baseQuery = Sekolah::query();
        if ($this->isAdminSekolah()) {
            $baseQuery->where('id_user', $user->id);
        } elseif ($this->isPegawaiNagari() && $nagariId) {
            $baseQuery->where('id_nagari', $nagariId);
        }

        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'aktif'    => (clone $baseQuery)->where('status', 'aktif')->count(),
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
    // CREATE  ✗ Admin Sekolah tidak boleh
    // ─────────────────────────────────────────────

    public function create()
    {
        // Admin sekolah tidak boleh membuat sekolah baru
        if ($this->isAdminSekolah()) {
            abort(403, 'Anda tidak memiliki izin untuk menambahkan sekolah baru.');
        }

        $user      = Auth::user();
        $roleLabel = $user->getRoleLabel();
        $nagariId  = $this->getNagariUser();

        // Superadmin (camat/pegawai kecamatan) bisa pilih nagari bebas
        $bisaPilihNagari = $this->isSuperAdmin();

        $nagaris = $bisaPilihNagari
            ? Nagari::orderBy('nama_nagari')->get()
            : collect();

        // Nagari terkunci untuk pegawai nagari
        $nagariTerpilih = $nagariId ? Nagari::find($nagariId) : null;

        // Superadmin: kosong dulu, muat via AJAX setelah nagari dipilih
        // Pegawai nagari: langsung muat sesuai nagarinya
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
    // STORE  ✗ Admin Sekolah tidak boleh
    // ─────────────────────────────────────────────

    public function store(Request $request)
    {
        // Admin sekolah tidak boleh menyimpan sekolah baru
        if ($this->isAdminSekolah()) {
            abort(403, 'Anda tidak memiliki izin untuk menambahkan sekolah.');
        }

        $nagariId = $this->getNagariUser();

        // Pegawai nagari: nagari dikunci ke nagarinya sendiri
        $idNagari = ($this->isPegawaiNagari() && $nagariId)
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

        $selectedUser = User::findOrFail($request->id_user);

        // Pastikan user yang dipilih adalah masyarakat biasa
        if ($selectedUser->role !== 'masyarakat') {
            return back()->withInput()
                ->withErrors(['id_user' => 'User yang dipilih bukan masyarakat.']);
        }

        // Pastikan user belum berstatus admin/siswa sekolah lain
        if (!is_null($selectedUser->sekolah)) {
            return back()->withInput()
                ->withErrors(['id_user' => 'User ini sudah terdaftar sebagai admin atau siswa sekolah lain.']);
        }

        // Pastikan user belum terdaftar sebagai id_user di sekolah manapun
        if (Sekolah::where('id_user', $request->id_user)->exists()) {
            return back()->withInput()
                ->withErrors(['id_user' => 'User ini sudah menjadi administrator sekolah lain.']);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('sekolah/logo', 'public');
        }

        DB::transaction(function () use ($request, $idNagari, $logoPath, $selectedUser) {
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

            // Tandai user sebagai admin sekolah:
            // role tetap 'masyarakat', kolom sekolah diisi 'admin'
            $selectedUser->update(['sekolah' => 'admin']);
        });

        return redirect()->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────

    public function show(Sekolah $sekolah)
    {
        $this->authorizeNagari($sekolah);

        $sekolah->load(['nagari', 'user.masyarakat', 'siswa', 'siswaAktif', 'siswaPending', 'mading']);

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
        $isAdmin   = $this->isAdminSekolah();

        $bisaPilihNagari = $this->isSuperAdmin();

        // Daftar nagari hanya untuk superadmin
        $nagaris = $bisaPilihNagari
            ? Nagari::orderBy('nama_nagari')->get()
            : collect();

        // Nagari yang ditampilkan sebagai "terkunci" di form:
        //  - Pegawai nagari  → nagari mereka
        //  - Admin sekolah   → nagari dari record sekolah yang diedit (karena mereka tidak
        //                      bisa mengubah nagari; kita tampilkan nagari sekolah bukan nagari user)
        $nagariTerpilih = match (true) {
            $isAdmin                      => $sekolah->nagari ?? Nagari::find($sekolah->id_nagari),
            $nagariId !== null             => Nagari::find($nagariId),
            default                        => null,
        };

        $userMasyarakat = collect();

        if (!$bisaPilihNagari) {
            // Tentukan nagari untuk filter calon admin:
            //  - Admin sekolah  → nagari dari data masyarakat si admin
            //                     (ia hanya boleh pilih pengganti dari nagarinya sendiri)
            //  - Pegawai nagari → nagari mereka
            $nagariFilter = $isAdmin
                ? ($user->masyarakat?->id_nagari)
                : $nagariId;

            $userMasyarakat = $this->getUserKepalaSekolahOptions($nagariFilter, $sekolah->id_sekolah);

            // Pastikan admin aktif sekolah ini selalu tampil di opsi (ia sudah sekolah='admin')
            $currentAdmin = User::with('masyarakat')->find($sekolah->id_user);
            if ($currentAdmin && !$userMasyarakat->contains('id', $currentAdmin->id)) {
                $userMasyarakat->prepend($currentAdmin);
            }
        }

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

        $user            = Auth::user();
        $nagariId        = $this->getNagariUser();
        $isAdmin         = $this->isAdminSekolah();
        $bisaPilihNagari = $this->isSuperAdmin();

        // Tentukan id_nagari yang akan disimpan:
        //  - Superadmin      → dari request (bebas pilih)
        //  - Pegawai nagari  → dikunci ke nagari mereka
        //  - Admin sekolah   → dikunci ke nagari sekolah yang ada (tidak boleh pindah nagari)
        $idNagari = match (true) {
            $bisaPilihNagari                           => $request->input('id_nagari'),
            $this->isPegawaiNagari() && $nagariId !== null => $nagariId,
            default                                    => $sekolah->id_nagari, // admin sekolah & pegawai tanpa nagari
        };

        $request->validate([
            'id_nagari'    => $bisaPilihNagari ? 'required|exists:nagari,id' : 'nullable',
            'id_user'      => 'required|exists:users,id',
            'nama_sekolah' => 'required|string|max:255',
            'npsn'         => 'nullable|string|max:20|unique:sekolah,npsn,' . $sekolah->id_sekolah . ',id_sekolah',
            'jenjang'      => 'required|in:SD,SMP,SMA,SMK,MI,MTs,MA,TK,PAUD',
            'alamat'       => 'nullable|string|max:500',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Admin sekolah tidak bisa mengubah status
            'status'       => $isAdmin ? 'nullable|in:aktif,nonaktif' : 'required|in:aktif,nonaktif',
        ], [
            'id_nagari.required'    => 'Nagari wajib dipilih.',
            'id_user.required'      => 'Administrator wajib dipilih.',
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
        ]);

        $newUserId = (int) $request->id_user;
        $oldUserId = (int) $sekolah->id_user;

        // ── Validasi jika admin diganti ──
        if ($newUserId !== $oldUserId) {

            // User baru tidak boleh sudah jadi admin sekolah lain
            $conflict = Sekolah::where('id_user', $newUserId)
                ->where('id_sekolah', '!=', $sekolah->id_sekolah)
                ->exists();
            if ($conflict) {
                return back()->withInput()
                    ->withErrors(['id_user' => 'User ini sudah menjadi administrator sekolah lain.']);
            }

            $newUser = User::findOrFail($newUserId);

            // User baru harus masyarakat biasa (role='masyarakat', sekolah IS NULL)
            if ($newUser->role !== 'masyarakat' || !is_null($newUser->sekolah)) {
                return back()->withInput()
                    ->withErrors(['id_user' => 'User ini tidak tersedia sebagai administrator.']);
            }

            // Admin sekolah hanya boleh memilih pengganti dari nagarinya sendiri
            if ($isAdmin) {
                $adminNagari   = $user->masyarakat?->id_nagari;
                $newUserNagari = $newUser->masyarakat?->id_nagari;

                if ($adminNagari !== $newUserNagari) {
                    return back()->withInput()
                        ->withErrors(['id_user' => 'Administrator pengganti harus berasal dari nagari yang sama.']);
                }
            }
        }

        // ── Handle logo ──
        $logoPath = $sekolah->logo;

        if ($request->hasFile('logo')) {
            if ($logoPath) Storage::disk('public')->delete($logoPath);
            $logoPath = $request->file('logo')->store('sekolah/logo', 'public');
        }

        if ($request->boolean('hapus_logo') && $logoPath) {
            Storage::disk('public')->delete($logoPath);
            $logoPath = null;
        }

        DB::transaction(function () use ($request, $sekolah, $idNagari, $logoPath, $newUserId, $oldUserId, $isAdmin) {

            $updateData = [
                'id_user'      => $newUserId,
                'id_nagari'    => $idNagari,
                'nama_sekolah' => $request->nama_sekolah,
                'npsn'         => $request->npsn,
                'jenjang'      => $request->jenjang,
                'alamat'       => $request->alamat,
                'no_hp'        => $request->no_hp,
                'email'        => $request->email,
                'logo'         => $logoPath,
            ];

            // Hanya superadmin & pegawai nagari yang boleh mengubah status
            if (!$isAdmin && $request->filled('status')) {
                $updateData['status'] = $request->status;
            }

            $sekolah->update($updateData);

            // ── Jika admin sekolah diganti ──
            if ($newUserId !== $oldUserId) {
                // Admin lama: kosongkan kolom sekolah → kembali jadi masyarakat biasa
                User::where('id', $oldUserId)->update(['sekolah' => null]);
                // Admin baru: tandai sebagai admin sekolah
                User::where('id', $newUserId)->update(['sekolah' => 'admin']);
            }
        });

        return redirect()->route('sekolah.show', $sekolah->id_sekolah)
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────
    // DESTROY  ✗ Admin Sekolah tidak boleh
    // ─────────────────────────────────────────────

    public function destroy(Sekolah $sekolah)
    {
        // Admin sekolah tidak boleh menghapus sekolah
        if ($this->isAdminSekolah()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus sekolah.');
        }

        $this->authorizeNagari($sekolah);

        DB::transaction(function () use ($sekolah) {
            // Kembalikan admin sekolah ke masyarakat biasa: kosongkan kolom sekolah
            User::where('id', $sekolah->id_user)->update(['sekolah' => null]);

            if ($sekolah->logo) {
                Storage::disk('public')->delete($sekolah->logo);
            }

            $sekolah->delete();
        });

        return redirect()->route('sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }

    // ─────────────────────────────────────────────
    // TOGGLE STATUS  ✗ Admin Sekolah tidak boleh
    // ─────────────────────────────────────────────

    public function toggleStatus(Sekolah $sekolah)
    {
        // Admin sekolah tidak boleh toggle status
        if ($this->isAdminSekolah()) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengubah status sekolah.',
            ], 403);
        }

        $this->authorizeNagari($sekolah);

        $sekolah->update([
            'status' => $sekolah->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'status'  => $sekolah->status,
            'message' => 'Status sekolah berhasil diperbarui.',
        ]);
    }

    // ─────────────────────────────────────────────
    // AJAX: Muat user masyarakat berdasarkan nagari
    // Dipanggil dari form create/edit oleh superadmin
    // ─────────────────────────────────────────────

    public function getUserByNagari(Request $request)
    {
        $user      = Auth::user();
        $idNagari  = $request->get('id_nagari');
        $idSekolah = $request->get('id_sekolah');

        // Admin sekolah: paksa nagari dari data masyarakat mereka sendiri
        if ($this->isAdminSekolah()) {
            $idNagari = $user->masyarakat?->id_nagari;
        }
        // Pegawai nagari: paksa nagari dari data pegawai mereka
        elseif ($this->isPegawaiNagari()) {
            $idNagari = $this->getNagariUser();
        }
        // Superadmin: gunakan nagari dari request (sudah di-set dari parameter URL)

        // id_user yang sudah jadi admin sekolah LAIN (mode edit: abaikan sekolah saat ini)
        $usedUserIds = Sekolah::when(
            $idSekolah,
            fn($q) => $q->where('id_sekolah', '!=', $idSekolah)
        )->pluck('id_user')->toArray();

        // Calon admin: masyarakat biasa (sekolah IS NULL), belum dipakai, sesuai nagari
        $candidates = User::where('role', 'masyarakat')
            ->whereNull('sekolah')
            ->whereNotIn('id', $usedUserIds)
            ->when($idNagari, function ($q) use ($idNagari) {
                $q->whereHas('masyarakat', fn($q2) => $q2->where('id_nagari', $idNagari));
            })
            ->with('masyarakat')
            ->get()
            ->map(fn($u) => [
                'id'              => $u->id,
                'nip_nik'         => $u->nip_nik,
                'nama_masyarakat' => $u->masyarakat?->nama_masyarakat ?? '',
            ]);

        // Mode edit: tambahkan admin aktif sekolah ini meski sudah sekolah='admin'
        if ($idSekolah) {
            $sekolahRecord = Sekolah::find($idSekolah);
            if ($sekolahRecord && !$candidates->contains('id', $sekolahRecord->id_user)) {
                $adminAktif = User::with('masyarakat')->find($sekolahRecord->id_user);
                if ($adminAktif) {
                    $candidates->prepend([
                        'id'              => $adminAktif->id,
                        'nip_nik'         => $adminAktif->nip_nik,
                        'nama_masyarakat' => $adminAktif->masyarakat?->nama_masyarakat ?? '',
                    ]);
                }
            }
        }

        return response()->json($candidates->values());
    }
}
