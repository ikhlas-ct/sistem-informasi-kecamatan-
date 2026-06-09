<?php

namespace App\Http\Controllers;

use App\Models\Mading;
use App\Models\Lampiran_mading;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MadingController extends Controller
{
    // ── Helper ──────────────────────────────────────────────────

    /**
     * Ambil id_sekolah berdasarkan role user yang sedang login.
     *
     * Admin sekolah → dari tabel sekolah (sekolah.id_user = user.id)
     * Siswa sekolah → dari tabel siswa  (siswa.id_user   = user.id)
     *
     * Mengembalikan null jika user tidak terhubung ke sekolah manapun,
     * sehingga semua query WHERE id_sekolah = null akan mengembalikan 0 baris
     * (lebih aman daripada menampilkan semua data).
     */
    private function getSekolahId(): ?int
    {
        $user = Auth::user();

        if ($user->isAdminSekolah()) {
            return $user->dataSekolah?->id_sekolah
                ? (int) $user->dataSekolah->id_sekolah
                : null;
        }

        if ($user->isSiswaSekolah()) {
            return $user->siswa?->id_sekolah
                ? (int) $user->siswa->id_sekolah
                : null;
        }

        return null;
    }

    private function isSekolahAdmin(): bool
    {
        return Auth::user()->isAdminSekolah();
    }

    private function isSiswa(): bool
    {
        return Auth::user()->isSiswaSekolah();
    }

    /**
     * Pastikan admin sekolah benar-benar terhubung ke sekolah.
     * Abort 403 jika admin tidak punya data sekolah (akun belum lengkap).
     */
    private function requireSekolahAdmin(): int
    {
        abort_unless($this->isSekolahAdmin(), 403, 'Hanya admin sekolah yang dapat melakukan aksi ini.');

        $sekolahId = $this->getSekolahId();
        abort_if(is_null($sekolahId), 403, 'Akun admin belum terhubung ke data sekolah.');

        return $sekolahId;
    }

    /**
     * Pastikan siswa sudah terverifikasi dan terhubung ke sekolah.
     * Mengembalikan objek Siswa jika valid, redirect jika tidak.
     */
    private function requireSiswaVerified()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan. Hubungi admin sekolah.');
        }

        if (!$siswa->id_sekolah) {
            return redirect()->back()->with('error', 'Akun Anda belum terdaftar di sekolah manapun.');
        }

        if (!$siswa->isApproved()) {
            return redirect()->back()->with('error', 'Akun Anda belum diverifikasi oleh sekolah.');
        }

        return $siswa;
    }

    // ── INDEX ────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();

        // Jika sekolahId null → user tidak terhubung ke sekolah, tampilkan kosong
        if (is_null($sekolahId)) {
            return view('pages.mading.index', [
                'mading' => collect()->paginate(12),
                'stats'  => ['total' => 0, 'publish' => 0, 'pending' => 0, 'rejected' => 0],
            ]);
        }

        $query = Mading::with(['user', 'sekolah'])
            ->where('id_sekolah', $sekolahId);

        // Siswa hanya lihat mading miliknya sendiri
        if ($this->isSiswa()) {
            $query->where('id_user', $user->id);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter approval_status
        if ($request->filled('approval')) {
            $query->where('approval_status', $request->approval);
        }

        $mading = $query->latest()->paginate(12)->withQueryString();

        // Stat cards
        $baseQuery = Mading::where('id_sekolah', $sekolahId);
        if ($this->isSiswa()) {
            $baseQuery->where('id_user', $user->id);
        }

        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'publish'  => (clone $baseQuery)->where('status', 'publish')->where('approval_status', 'approved')->count(),
            'pending'  => (clone $baseQuery)->where('approval_status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('approval_status', 'rejected')->count(),
        ];

        return view('pages.mading.index', compact('mading', 'stats'));
    }

    // ── CREATE ───────────────────────────────────────────────────

    public function create()
    {
        if ($this->isSiswa()) {
            $result = $this->requireSiswaVerified();
            // Jika requireSiswaVerified mengembalikan redirect, teruskan
            if ($result instanceof \Illuminate\Http\RedirectResponse) {
                return $result;
            }
        }

        return view('pages.mading.create');
    }

    // ── STORE ────────────────────────────────────────────────────

    public function store(Request $request)
    {
        // Validasi akses sebelum menyimpan
        if ($this->isSiswa()) {
            $result = $this->requireSiswaVerified();
            if ($result instanceof \Illuminate\Http\RedirectResponse) {
                return $result;
            }
        }

        $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'jenis'      => 'required|in:karya,pengumuman,berita,cerpen,puisi,lainnya',
            'gambar'     => 'nullable|image|max:3072',
            'status'     => 'nullable|in:draft,publish',
            'lampiran.*' => 'nullable|file|max:5120',
        ]);

        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();

        // Pastikan sekolahId ada (double-check)
        abort_if(is_null($sekolahId), 403, 'Tidak dapat menentukan sekolah Anda.');

        $slug = Str::slug($request->judul) . '-' . Str::random(6);

        // Admin sekolah → langsung approved | Siswa → pending, selalu draft dulu
        $approvalStatus = $this->isSekolahAdmin() ? 'approved' : 'pending';
        $status         = $this->isSekolahAdmin() ? ($request->status ?? 'publish') : 'draft';

        // Upload gambar sampul
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('mading/gambar', 'public');
        }

        $mading = Mading::create([
            'id_user'           => $user->id,
            'id_sekolah'        => $sekolahId,
            'judul'             => $request->judul,
            'isi'               => $request->isi,
            'jenis'             => $request->jenis,
            'slug'              => $slug,
            'gambar'            => $gambarPath,
            'status'            => $status,
            'approval_status'   => $approvalStatus,
            'tanggal_publikasi' => $status === 'publish' ? now() : null,
        ]);

        // Upload lampiran tambahan
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $ext  = $file->getClientOriginalExtension();
                $tipe = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image'
                    : (in_array($ext, ['mp4', 'mov']) ? 'video'
                        : ($ext === 'pdf' ? 'pdf' : 'lainnya'));

                Lampiran_mading::create([
                    'id_mading' => $mading->id_mading,
                    'tipe'      => $tipe,
                    'path'      => $file->store('mading/lampiran', 'public'),
                ]);
            }
        }

        $msg = $this->isSekolahAdmin()
            ? 'Mading berhasil dipublikasikan.'
            : 'Mading berhasil dikirim dan menunggu persetujuan sekolah.';

        return redirect()->route('mading.index')->with('success', $msg);
    }

    // ── EDIT ─────────────────────────────────────────────────────

    public function edit($id_mading)
    {
        $mading = Mading::with('lampiran')->findOrFail($id_mading);
        $this->authorizeAccess($mading);

        return view('pages.mading.edit', compact('mading'));
    }

    // ── UPDATE ───────────────────────────────────────────────────

    public function update(Request $request, $id_mading)
    {
        $mading = Mading::findOrFail($id_mading);
        $this->authorizeAccess($mading);

        $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'jenis'      => 'required|in:karya,pengumuman,berita,cerpen,puisi,lainnya',
            'gambar'     => 'nullable|image|max:3072',
            'status'     => 'nullable|in:draft,publish',
            'lampiran.*' => 'nullable|file|max:5120',
        ]);

        // Jika siswa edit → approval kembali ke pending, status kembali ke draft
        $approvalStatus = $this->isSekolahAdmin()
            ? $mading->approval_status
            : 'pending';

        $status = $this->isSekolahAdmin()
            ? ($request->status ?? $mading->status)
            : 'draft';

        // Update gambar jika ada upload baru
        $gambarPath = $mading->gambar;
        if ($request->hasFile('gambar')) {
            if ($gambarPath) Storage::disk('public')->delete($gambarPath);
            $gambarPath = $request->file('gambar')->store('mading/gambar', 'public');
        }

        $mading->update([
            'judul'             => $request->judul,
            'isi'               => $request->isi,
            'jenis'             => $request->jenis,
            'gambar'            => $gambarPath,
            'status'            => $status,
            'approval_status'   => $approvalStatus,
            'tanggal_publikasi' => ($status === 'publish' && $mading->status !== 'publish')
                ? now()
                : $mading->tanggal_publikasi,
        ]);

        // Upload lampiran baru
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $ext  = $file->getClientOriginalExtension();
                $tipe = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image'
                    : (in_array($ext, ['mp4', 'mov']) ? 'video'
                        : ($ext === 'pdf' ? 'pdf' : 'lainnya'));

                Lampiran_mading::create([
                    'id_mading' => $mading->id_mading,
                    'tipe'      => $tipe,
                    'path'      => $file->store('mading/lampiran', 'public'),
                ]);
            }
        }

        $msg = $this->isSekolahAdmin()
            ? 'Mading berhasil diperbarui.'
            : 'Mading berhasil diperbarui dan menunggu persetujuan ulang.';

        return redirect()->route('mading.index')->with('success', $msg);
    }

    // ── SHOW → redirect ke halaman publik ────────────────────────
    // Tidak perlu view terpisah; cukup arahkan ke route home.mading.detail
    // yang sudah ada dan menggunakan slug sebagai parameter.

    public function show($id_mading)
    {
        $mading = Mading::findOrFail($id_mading);
        $this->authorizeAccess($mading);

        return redirect()->route('home.mading.detail', ['slug' => $mading->slug]);
    }

    // ── TOGGLE PUBLISH / DRAFT ────────────────────────────────────

    public function toggle($id_mading)
    {
        // Hanya admin sekolah yang boleh toggle
        $sekolahId = $this->requireSekolahAdmin();

        $mading = Mading::where('id_sekolah', $sekolahId)->findOrFail($id_mading);

        // Hanya mading yang sudah approved boleh di-toggle
        abort_if($mading->approval_status !== 'approved', 403, 'Hanya mading yang sudah disetujui dapat diaktifkan/dinonaktifkan.');

        $newStatus = $mading->status === 'publish' ? 'draft' : 'publish';

        $mading->update([
            'status'            => $newStatus,
            'tanggal_publikasi' => $newStatus === 'publish' ? now() : $mading->tanggal_publikasi,
        ]);

        $msg = $newStatus === 'publish'
            ? 'Mading berhasil diaktifkan (publish).'
            : 'Mading berhasil dinonaktifkan (draft).';

        return redirect()->back()->with('success', $msg);
    }

    // ── DESTROY ──────────────────────────────────────────────────

    public function destroy($id_mading)
    {
        $mading = Mading::with('lampiran')->findOrFail($id_mading);
        $this->authorizeAccess($mading);

        if ($mading->gambar) Storage::disk('public')->delete($mading->gambar);

        foreach ($mading->lampiran as $lamp) {
            Storage::disk('public')->delete($lamp->path);
        }

        $mading->delete();

        return redirect()->route('mading.index')->with('success', 'Mading berhasil dihapus.');
    }

    // ── APPROVE ──────────────────────────────────────────────────

    public function approve($id_mading)
    {
        // Pastikan admin sekolah & punya data sekolah
        $sekolahId = $this->requireSekolahAdmin();

        // Mading harus milik sekolah yang sama dengan admin yang login
        $mading = Mading::where('id_sekolah', $sekolahId)->findOrFail($id_mading);

        $mading->update([
            'approval_status'   => 'approved',
            'alasan_penolakan'  => null,
            'status'            => 'publish',
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->back()->with('success', 'Mading berhasil disetujui dan dipublikasikan.');
    }

    // ── REJECT ───────────────────────────────────────────────────

    public function reject(Request $request, $id_mading)
    {
        // Pastikan admin sekolah & punya data sekolah
        $sekolahId = $this->requireSekolahAdmin();

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        // Mading harus milik sekolah yang sama dengan admin yang login
        $mading = Mading::where('id_sekolah', $sekolahId)->findOrFail($id_mading);

        $mading->update([
            'approval_status'  => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'status'           => 'draft',
        ]);

        return redirect()->back()->with('success', 'Mading ditolak.');
    }

    // ── HAPUS LAMPIRAN ───────────────────────────────────────────

    public function destroyLampiran($id)
    {
        $lampiran = Lampiran_mading::findOrFail($id);
        $mading   = $lampiran->mading;
        $this->authorizeAccess($mading);

        Storage::disk('public')->delete($lampiran->path);
        $lampiran->delete();

        return response()->json(['success' => true]);
    }

    // ── UPLOAD GAMBAR INLINE SUMMERNOTE ─────────────────────────

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:3072',
        ]);

        $path = $request->file('image')->store('mading/isi', 'public');

        return response()->json(['url' => asset('storage/' . $path)]);
    }

    // ── HAPUS GAMBAR INLINE SUMMERNOTE ───────────────────────────

    public function deleteImage(Request $request)
    {
        $src  = $request->input('src', '');
        $path = str_replace(asset('storage') . '/', '', $src);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json(['success' => true]);
    }

    // ── PRIVATE: Cek akses ───────────────────────────────────────

    /**
     * Aturan akses:
     * - Admin sekolah: bisa akses semua mading di sekolahnya SENDIRI
     *   (id_sekolah mading == id_sekolah dari dataSekolah admin)
     * - Siswa: hanya bisa akses mading miliknya sendiri
     *   (id_user mading == id user yang login)
     *
     * Pengecekan ganda (sekolah + user) mencegah admin sekolah A
     * mengakses mading di sekolah B.
     */
    private function authorizeAccess(Mading $mading): void
    {
        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();

        if ($this->isSekolahAdmin()) {
            // Admin harus punya data sekolah dan sekolah harus cocok
            abort_if(
                is_null($sekolahId) || (int) $mading->id_sekolah !== $sekolahId,
                403,
                'Anda tidak memiliki akses ke mading ini.'
            );
            return;
        }

        if ($this->isSiswa()) {
            // Siswa hanya bisa akses mading miliknya
            // Tambahan: pastikan mading berada di sekolah yang sama dengan siswa
            abort_if(
                (int) $mading->id_user !== (int) $user->id
                    || (int) $mading->id_sekolah !== (int) $sekolahId,
                403,
                'Anda tidak memiliki akses ke mading ini.'
            );
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke mading ini.');
    }
}
