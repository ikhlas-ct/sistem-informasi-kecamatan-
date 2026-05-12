<?php

namespace App\Http\Controllers\Konten;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Konten;
use App\Models\Masyarakat;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KontenController extends Controller
{
    /* ─────────────────────────────────────────────────────────── */
    /* Konstanta                                                   */
    /* ─────────────────────────────────────────────────────────── */

    const JENIS_LIST = [
        'berita', 'artikel', 'seni_tari', 'makanan_daerah',
        'kerajinan_daerah', 'seni_musik', 'seni_budaya', 'pariwisata', 'pertanian',
    ];

    /* ─────────────────────────────────────────────────────────── */
    /* Helper – validasi jenis                                    */
    /* ─────────────────────────────────────────────────────────── */

    private function validateJenis(string $jenis): void
    {
        abort_if(! in_array($jenis, self::JENIS_LIST), 404);
    }

    /* ─────────────────────────────────────────────────────────── */
    /* Helper – deteksi peran                                     */
    /*                                                             */
    /*  Camat      → users.role = 'camat'                        */
    /*  Staf Camat → users.role = 'pegawai' & id_nagari NULL     */
    /*  Wali Nagari→ users.role = 'pegawai' & jabatan=kepala_nagari */
    /*  Staf Nagari→ users.role = 'pegawai' & jabatan=pegawai_nagari */
    /*  Masyarakat → users.role = 'masyarakat'                   */
    /* ─────────────────────────────────────────────────────────── */

    private function isSuperAdmin(): bool
    {
        $user = Auth::user();
        if ($user->role === 'camat') return true;

        if ($user->role === 'pegawai') {
            $pegawai = $user->pegawai; // relasi ke tabel pegawai
            return ! $pegawai || is_null($pegawai->id_nagari);
        }
        return false;
    }

    private function getRoleLabel(): string
    {
        $user = Auth::user();
        if ($user->role === 'camat') return 'camat';
        if ($user->role === 'pegawai') {
            $pegawai = $user->pegawai;
            if (! $pegawai || is_null($pegawai->id_nagari)) return 'staf_camat';
            return $pegawai->jabatan_nagari === 'kepala_nagari' ? 'wali_nagari' : 'staf_nagari';
        }
        return 'masyarakat';
    }

    private function getNagariId(): ?int
    {
        $user = Auth::user();
        if ($user->role === 'pegawai')    return $user->pegawai?->id_nagari;
        if ($user->role === 'masyarakat') return $user->masyarakat?->id_nagari;
        return null;
    }

    /* ─────────────────────────────────────────────────────────── */
    /* Helper – terapkan scope akses ke query                     */
    /* ─────────────────────────────────────────────────────────── */

    private function scopeByAccess($query): void
    {
        if ($this->isSuperAdmin()) return; // superadmin lihat semua

        $user     = Auth::user();
        $nagariId = $this->getNagariId();

        if ($user->role === 'masyarakat') {
            // Masyarakat hanya lihat konten milik sendiri
            $query->where('id_user', $user->id);
            return;
        }

        // Wali / Staf Nagari: lihat konten sendiri + konten user se-nagari
        if ($nagariId) {
            $allowedUserIds = collect()
                ->merge(Pegawai::where('id_nagari', $nagariId)->pluck('id_user'))
                ->merge(Masyarakat::where('id_nagari', $nagariId)->pluck('id_user'))
                ->push($user->id)
                ->unique();

            $query->whereIn('id_user', $allowedUserIds);
        } else {
            $query->where('id_user', $user->id);
        }
    }

    /* ─────────────────────────────────────────────────────────── */
    /* Helper – cek apakah user boleh edit/hapus konten tertentu  */
    /* ─────────────────────────────────────────────────────────── */

    public function canModify(Konten $konten): bool
    {
        if ($this->isSuperAdmin()) return true;
        return $konten->id_user === Auth::id();
    }

    /* ─────────────────────────────────────────────────────────── */
    /* INDEX                                                       */
    /* ─────────────────────────────────────────────────────────── */

    public function index(Request $request, string $jenis)
    {
        $this->validateJenis($jenis);

        $roleLabel = $this->getRoleLabel();
        $isSuperAdmin = $this->isSuperAdmin();

        $query = Konten::with(['user', 'kategori'])->where('jenis_konten', $jenis);
        $this->scopeByAccess($query);

        // ── Filter pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        // ── Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $konten = $query->latest('tanggal_publikasi')->paginate(10)->withQueryString();

        // ── Stats
        $baseStats = Konten::where('jenis_konten', $jenis);
        $this->scopeByAccess($baseStats);
        $stats = [
            'total'    => (clone $baseStats)->count(),
            'aktif'    => (clone $baseStats)->where('status', 'aktif')->count(),
            'pending'  => (clone $baseStats)->where('status', 'pending')->count(),
            'nonaktif' => (clone $baseStats)->where('status', 'nonaktif')->count(),
        ];

        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('pages.konten.index', compact(
            'konten', 'jenis', 'stats', 'kategoriList', 'roleLabel', 'isSuperAdmin'
        ));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* CREATE                                                      */
    /* ─────────────────────────────────────────────────────────── */

    public function create(string $jenis)
    {
        $this->validateJenis($jenis);

        $kategori     = Kategori::orderBy('nama_kategori')->get();
        $jenisList    = self::JENIS_LIST;
        $isSuperAdmin = $this->isSuperAdmin();

        return view('pages.konten.create', compact('jenis', 'kategori', 'jenisList', 'isSuperAdmin'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* STORE                                                       */
    /* ─────────────────────────────────────────────────────────── */

    public function store(Request $request, string $jenis)
    {
        $this->validateJenis($jenis);

        $rules = [
            'judul'       => 'required|string|max:255|unique:konten,judul',
            'isi'         => 'required|string',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
        ];

        if ($this->isSuperAdmin()) {
            $rules['status'] = 'required|in:aktif,nonaktif,pending';
        }

        $validated = $request->validate($rules, [
            'judul.required'  => 'Judul wajib diisi.',
            'judul.unique'    => 'Judul sudah digunakan, coba judul lain.',
            'isi.required'    => 'Isi konten wajib diisi.',
            'gambar.required' => 'Gambar sampul wajib diunggah.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 3 MB.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $gambarPath = $request->file('gambar')->store('konten/gambar', 'public');

        // Superadmin bebas pilih status; lainnya otomatis pending (menunggu persetujuan)
        $status = $this->isSuperAdmin()
            ? ($validated['status'] ?? 'aktif')
            : 'pending';

        $konten = Konten::create([
            'judul'             => $validated['judul'],
            'isi'               => $validated['isi'],
            'id_user'           => Auth::id(),
            'slug'              => Str::slug($validated['judul']) . '-' . time(),
            'tanggal_publikasi' => now(),
            'jenis_konten'      => $jenis,
            'gambar'            => $gambarPath,
            'status'            => $status,
        ]);

        // Sync kategori ke tabel pivot kategori_konten
        $konten->kategori()->sync(
            !empty($validated['id_kategori']) ? [$validated['id_kategori']] : []
        );

        $jenisLabel = ucfirst(str_replace('_', ' ', $jenis));
        $pesan = $this->isSuperAdmin()
            ? "{$jenisLabel} \"{$validated['judul']}\" berhasil ditambahkan."
            : "{$jenisLabel} \"{$validated['judul']}\" berhasil dikirim dan menunggu persetujuan.";

        return redirect()->route('konten.index', $jenis)->with('success', $pesan);
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SHOW                                                        */
    /* ─────────────────────────────────────────────────────────── */

    public function show(string $jenis, string $slug)
    {
        $this->validateJenis($jenis);

        $konten = Konten::where('jenis_konten', $jenis)
            ->where('slug', $slug)
            ->with(['user', 'kategori'])
            ->firstOrFail();

        // Cek akses baca
        if (! $this->isSuperAdmin()) {
            $user     = Auth::user();
            $nagariId = $this->getNagariId();

            $allowed = collect([$user->id]);
            if ($nagariId && $user->role !== 'masyarakat') {
                $allowed = $allowed
                    ->merge(Pegawai::where('id_nagari', $nagariId)->pluck('id_user'))
                    ->merge(Masyarakat::where('id_nagari', $nagariId)->pluck('id_user'));
            }

            abort_if(! $allowed->contains($konten->id_user), 403, 'Konten ini bukan milik nagari Anda.');
        }

        $isSuperAdmin = $this->isSuperAdmin();
        $canModify    = $this->canModify($konten);

        return view('pages.konten.show', compact('konten', 'jenis', 'isSuperAdmin', 'canModify'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* EDIT                                                        */
    /* ─────────────────────────────────────────────────────────── */

    public function edit(string $jenis, string $slug)
    {
        $this->validateJenis($jenis);

        $konten = Konten::where('jenis_konten', $jenis)->where('slug', $slug)->firstOrFail();

        // Non-superadmin hanya boleh edit konten milik sendiri
        abort_if(! $this->canModify($konten), 403, 'Anda tidak berhak mengedit konten ini.');

        $kategori     = Kategori::orderBy('nama_kategori')->get();
        $jenisList    = self::JENIS_LIST;
        $isSuperAdmin = $this->isSuperAdmin();

        return view('pages.konten.edit', compact('konten', 'jenis', 'kategori', 'jenisList', 'isSuperAdmin'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* UPDATE                                                      */
    /* ─────────────────────────────────────────────────────────── */

    public function update(Request $request, string $jenis, int $id_konten)
    {
        $this->validateJenis($jenis);

        $konten = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);
        abort_if(! $this->canModify($konten), 403);

        $rules = [
            'judul'       => 'required|string|max:255|unique:konten,judul,' . $id_konten . ',id_konten',
            'isi'         => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
        ];

        // Hanya superadmin yang bisa ubah status
        if ($this->isSuperAdmin()) {
            $rules['status'] = 'required|in:aktif,nonaktif,pending';
        }

        $validated = $request->validate($rules, [
            'judul.required' => 'Judul wajib diisi.',
            'judul.unique'   => 'Judul sudah digunakan.',
            'isi.required'   => 'Isi konten wajib diisi.',
        ]);

        $data = collect($validated)->except(['gambar', 'id_kategori'])->toArray();

        // ── Update gambar jika ada yang baru
        if ($request->hasFile('gambar')) {
            if ($konten->gambar && Storage::disk('public')->exists($konten->gambar)) {
                Storage::disk('public')->delete($konten->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('konten/gambar', 'public');
        }

        // ── Update slug jika judul berubah
        if ($validated['judul'] !== $konten->judul) {
            $data['slug'] = Str::slug($validated['judul']) . '-' . time();
        }

        // Non-superadmin yang edit konten aktif → kembalikan ke pending
        if (! $this->isSuperAdmin() && $konten->status === 'aktif') {
            $data['status'] = 'pending';
        }

        $konten->update($data);
        // Sync kategori
        $konten->kategori()->sync(
            !empty($validated['id_kategori']) ? [$validated['id_kategori']] : []
        );

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst(str_replace('_', ' ', $jenis)) . ' berhasil diperbarui.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* DESTROY                                                     */
    /* ─────────────────────────────────────────────────────────── */

    public function destroy(string $jenis, int $id_konten)
    {
        $this->validateJenis($jenis);

        $konten = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);
        abort_if(! $this->canModify($konten), 403);

        if ($konten->gambar && Storage::disk('public')->exists($konten->gambar)) {
            Storage::disk('public')->delete($konten->gambar);
        }

        $judul = $konten->judul;
        $konten->delete();

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst(str_replace('_', ' ', $jenis)) . ' "' . $judul . '" berhasil dihapus.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* APPROVE – Superadmin aktifkan konten pending               */
    /* ─────────────────────────────────────────────────────────── */

    public function approve(string $jenis, int $id_konten)
    {
        abort_if(! $this->isSuperAdmin(), 403, 'Hanya superadmin yang dapat menyetujui konten.');
        $this->validateJenis($jenis);

        $konten = Konten::findOrFail($id_konten);
        $konten->update(['status' => 'aktif']);

        return redirect()->back()
            ->with('success', 'Konten "' . $konten->judul . '" telah disetujui dan diaktifkan.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SUMMERNOTE – Upload gambar inline                          */
    /* ─────────────────────────────────────────────────────────── */

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:3072']);
        $path = $request->file('image')->store('konten/isi', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SUMMERNOTE – Hapus gambar inline                           */
    /* ─────────────────────────────────────────────────────────── */

    public function deleteImage(Request $request)
    {
        $src  = $request->input('src', '');
        $path = str_replace(asset('storage') . '/', '', $src);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json(['success' => true]);
    }
}
