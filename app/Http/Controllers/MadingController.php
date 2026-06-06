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

    private function getSekolahId()
    {
        $user = Auth::user();

        // Admin sekolah → ambil id_sekolah dari relasi sekolah (via id_user)
        if ($user->isAdminSekolah()) return $user->dataSekolah?->id_sekolah;

        // Siswa sekolah → ambil id_sekolah dari relasi siswa (via id_user)
        if ($user->isSiswaSekolah()) return $user->siswa?->id_sekolah;

        return null;
    }

    private function isSekolahAdmin(): bool
    {
        // role = 'masyarakat' + sekolah = 'admin'
        return Auth::user()->isAdminSekolah();
    }

    private function isSiswa(): bool
    {
        // role = 'masyarakat' + sekolah = 'siswa'
        return Auth::user()->isSiswaSekolah();
    }

    // ── INDEX ────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();

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
        if ($this->isSiswa()) $baseQuery->where('id_user', $user->id);

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
        // Siswa harus sudah verified
        if ($this->isSiswa()) {
            $siswa = Auth::user()->siswa;
            if (!$siswa || !$siswa->isApproved()) {
                return redirect()->back()->with('error', 'Akun Anda belum diverifikasi oleh sekolah.');
            }
        }

        return view('pages.mading.create');
    }

    // ── STORE ────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string',
            'jenis'   => 'required|in:karya,pengumuman,berita,cerpen,puisi,lainnya',
            'gambar'  => 'nullable|image|max:3072',
            'status'  => 'nullable|in:draft,publish',
            'lampiran.*' => 'nullable|file|max:5120',
        ]);

        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();
        $slug      = Str::slug($request->judul) . '-' . Str::random(6);

        // Sekolah: langsung approved | Siswa: pending
        $approvalStatus = $this->isSekolahAdmin() ? 'approved' : 'pending';
        $status         = $this->isSekolahAdmin() ? ($request->status ?? 'publish') : 'draft';

        // Upload gambar sampul
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('mading/gambar', 'public');
        }

        $mading = Mading::create([
            'id_user'         => $user->id,
            'id_sekolah'      => $sekolahId,
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'jenis'           => $request->jenis,
            'slug'            => $slug,
            'gambar'          => $gambarPath,
            'status'          => $status,
            'approval_status' => $approvalStatus,
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

        // Jika siswa edit → approval kembali ke pending
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
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'jenis'           => $request->jenis,
            'gambar'          => $gambarPath,
            'status'          => $status,
            'approval_status' => $approvalStatus,
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

    // ── DESTROY ──────────────────────────────────────────────────

    public function destroy($id_mading)
    {
        $mading = Mading::with('lampiran')->findOrFail($id_mading);
        $this->authorizeAccess($mading);

        // Hapus gambar sampul
        if ($mading->gambar) Storage::disk('public')->delete($mading->gambar);

        // Hapus semua lampiran
        foreach ($mading->lampiran as $lamp) {
            Storage::disk('public')->delete($lamp->path);
        }

        $mading->delete();

        return redirect()->route('mading.index')->with('success', 'Mading berhasil dihapus.');
    }

    // ── APPROVE (Sekolah approve mading siswa) ───────────────────

    public function approve($id_mading)
    {
        abort_unless($this->isSekolahAdmin(), 403);

        $mading = Mading::where('id_sekolah', $this->getSekolahId())->findOrFail($id_mading);

        $mading->update([
            'approval_status'   => 'approved',
            'alasan_penolakan'  => null,
            'status'            => 'publish',
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->back()->with('success', 'Mading berhasil disetujui dan dipublikasikan.');
    }

    // ── REJECT (Sekolah tolak mading siswa) ─────────────────────

    public function reject(Request $request, $id_mading)
    {
        abort_unless($this->isSekolahAdmin(), 403);

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $mading = Mading::where('id_sekolah', $this->getSekolahId())->findOrFail($id_mading);

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

    private function authorizeAccess(Mading $mading)
    {
        $user      = Auth::user();
        $sekolahId = $this->getSekolahId();

        // Sekolah bisa akses semua mading di sekolahnya
        if ($this->isSekolahAdmin() && $mading->id_sekolah === $sekolahId) return;

        // Siswa hanya akses mading miliknya
        if ($this->isSiswa() && $mading->id_user === $user->id) return;

        abort(403, 'Anda tidak memiliki akses ke mading ini.');
    }
}
