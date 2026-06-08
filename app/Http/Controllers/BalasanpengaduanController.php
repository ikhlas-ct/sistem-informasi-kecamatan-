<?php

namespace App\Http\Controllers;

use App\Models\Balasanpengaduan;
use App\Models\Lampiran_balasan;
use App\Models\Pengaduan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BalasanpengaduanController extends Controller
{
    /**
     * Status yang valid — harus SAMA PERSIS dengan ENUM kolom status di tabel pengaduan.
     */
    private const STATUS_OPTIONS = [
        'pending'  => 'Pending',
        'diproses' => 'Diproses',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];

    private const STATUS_DEFAULT = 'diproses';

    // ─────────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Ambil Pegawai yang sedang login (beserta nagari-nya).
     */
    private function getPegawai(): ?Pegawai
    {
        return Pegawai::with('nagari')
            ->where('id_user', Auth::id())
            ->first();
    }

    /**
     * Apakah user yang login punya akses penuh (semua nagari)?
     * True untuk:
     *  - camat          → role = 'camat'
     *  - staf kecamatan → role = 'pegawai' DAN id_nagari IS NULL
     *
     * Sesuai logika isSuperAdmin() di User model.
     */
    private function hasFullAccess(): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        if ($user->role === 'camat') return true;

        // Staf kecamatan: pegawai yang tidak terikat ke nagari manapun
        if ($user->role === 'pegawai') {
            $pegawai = $this->getPegawai();
            return $pegawai && is_null($pegawai->id_nagari);
        }

        return false;
    }

    /**
     * Pastikan pegawai berhak menangani $pengaduan.
     * - Camat      → bisa semua nagari
     * - Pegawai    → hanya nagari yang sama dengan pengadu
     */
    private function getAuthorizedPegawai(Pengaduan $pengaduan): ?Pegawai
    {
        $pegawai = $this->getPegawai();
        if (! $pegawai) return null;

        // Camat & staf kecamatan bisa akses semua nagari
        if ($this->hasFullAccess()) return $pegawai;

        // Pegawai biasa: cocokkan nagari pengadu dengan nagari pegawai
        $idNagariPengadu = optional($pengaduan->masyarakat)->id_nagari;

        if ($pegawai->id_nagari && (int) $pegawai->id_nagari === (int) $idNagariPengadu) {
            return $pegawai;
        }

        return null;
    }

    /**
     * Tentukan tipe lampiran: 'gambar' | 'file'
     * (sesuai ENUM di tabel lampiran_balasan)
     *
     * FIX: pakai ekstensi, bukan getMimeType() — lebih konsisten
     * di semua server (getMimeType() butuh fileinfo extension
     * dan bisa mengembalikan null pada beberapa konfigurasi server).
     */
    private function tipeFile(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'gambar' : 'file';
    }

    // ─────────────────────────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $pegawai = $this->getPegawai();
        if (! $pegawai) abort(403, 'Data pegawai tidak ditemukan.');

        // ── Query utama: eager load semua relasi sekaligus (hapus N+1) ──
        $query = Pengaduan::with([
            'masyarakat.nagari',   // 1 query untuk semua masyarakat + nagari
            'lampiran_pengaduan',  // 1 query untuk semua lampiran pengaduan
            'balasanpengaduan',    // 1 query untuk semua balasan
        ]);

        // Scope nagari untuk pegawai nagari (bukan camat/staf kecamatan)
        if (! $this->hasFullAccess()) {
            if (! $pegawai->id_nagari) abort(403, 'Nagari pegawai tidak terdaftar.');

            $query->whereHas(
                'masyarakat',
                fn($q) =>
                $q->where('id_nagari', $pegawai->id_nagari)
            );
        }

        // ── Filter ──────────────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul_pengaduan', 'like', "%{$s}%")
                    ->orWhere('hal_pengaduan',  'like', "%{$s}%")
                    ->orWhereHas(
                        'masyarakat',
                        fn($m) =>
                        $m->where('nama_masyarakat', 'like', "%{$s}%")
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('balasan')) {
            match ($request->balasan) {
                'belum' => $query->doesntHave('balasanpengaduan'),
                'sudah' => $query->has('balasanpengaduan'),
                default => null,
            };
        }

        // Pengaduan belum dibalas tampil duluan, lalu urut tanggal terbaru
        $query->orderByRaw("
            CASE WHEN EXISTS (
                SELECT 1 FROM balasanpengaduan b
                WHERE b.id_pengaduan = pengaduan.id_pengaduan
            ) THEN 1 ELSE 0 END ASC
        ")->orderBy('tanggal_pengaduan', 'desc');

        $pengaduans = $query->paginate(15)->withQueryString();

        // ── Statistik: 2 query saja (bukan 5) ───────────────────────
        $statsBase = Pengaduan::query();
        if (! $this->hasFullAccess()) {
            $statsBase->whereHas(
                'masyarakat',
                fn($q) =>
                $q->where('id_nagari', $pegawai->id_nagari)
            );
        }

        // Satu query dengan conditional aggregate
        $agg = (clone $statsBase)
            ->selectRaw("
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) AS selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) AS ditolak
            ")->first();

        $sudahDibalas = (clone $statsBase)->has('balasanpengaduan')->count();

        $stats = [
            'total'         => (int) ($agg->total    ?? 0),
            'belum_dibalas' => (int) ($agg->total    ?? 0) - $sudahDibalas,
            'sudah_dibalas' => $sudahDibalas,
            'selesai'       => (int) ($agg->selesai  ?? 0),
            'ditolak'       => (int) ($agg->ditolak  ?? 0),
        ];

        $statusOptions = self::STATUS_OPTIONS;

        return view(
            'pages.balasan_pengaduan.index',
            compact('pengaduans', 'stats', 'pegawai', 'statusOptions')
        );
    }

    // ─────────────────────────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────────────────────────

    public function create($id_pengaduan)
    {
        $pengaduan = Pengaduan::with([
            'masyarakat',
            'lampiran_pengaduan',
            'balasanpengaduan',
        ])->findOrFail($id_pengaduan);

        $pegawai = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403, 'Anda tidak memiliki izin untuk membalas pengaduan ini.');

        // Jika sudah dibalas, arahkan ke edit
        if ($pengaduan->balasanpengaduan) {
            return redirect()
                ->route('balasanpengaduan.edit', $pengaduan->balasanpengaduan->id_balasanpengaduan)
                ->with('info', 'Pengaduan ini sudah pernah dibalas. Silakan edit balasan yang ada.');
        }

        $statusOptions = self::STATUS_OPTIONS;
        $statusDefault = self::STATUS_DEFAULT;

        return view(
            'pages.balasan_pengaduan.create',
            compact('pengaduan', 'pegawai', 'statusOptions', 'statusDefault')
        );
    }

    // ─────────────────────────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────────────────────────

    public function store(Request $request, $id_pengaduan)
    {
        $pengaduan = Pengaduan::with('masyarakat')->findOrFail($id_pengaduan);

        $pegawai = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403);

        $validated = $request->validate([
            'balasan'         => 'required|string|max:5000',
            'tanggal_balasan' => 'required|date',
            'status'          => 'required|in:' . implode(',', array_keys(self::STATUS_OPTIONS)),
            'lampiran'        => 'nullable|array|max:10',
            'lampiran.*'      => 'file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ], [
            'balasan.required'         => 'Isi balasan tidak boleh kosong.',
            'tanggal_balasan.required' => 'Tanggal balasan harus diisi.',
            'status.required'          => 'Status pengaduan harus dipilih.',
            'status.in'                => 'Status yang dipilih tidak valid.',
            'lampiran.max'             => 'Maksimal 10 file lampiran.',
            'lampiran.*.max'           => 'Ukuran tiap file maksimal 10 MB.',
            'lampiran.*.mimes'         => 'Format file tidak didukung (JPG, PNG, WEBP, PDF, DOC, DOCX).',
        ]);

        // Catat path file yang berhasil diupload secara fisik.
        // Jika transaksi DB gagal, file-file ini harus dihapus manual
        // karena Storage::put() tidak ikut di-rollback oleh DB::transaction().
        $uploadedPaths = [];

        try {
            DB::transaction(function () use ($validated, $request, $pengaduan, $pegawai, &$uploadedPaths) {

                // 1. Simpan balasan
                $balasan = Balasanpengaduan::create([
                    'id_pegawai'      => $pegawai->id_pegawai,
                    'id_pengaduan'    => $pengaduan->id_pengaduan,
                    'balasan'         => $validated['balasan'],
                    'tanggal_balasan' => $validated['tanggal_balasan'],
                ]);

                // 2. Simpan lampiran
                // File sudah tervalidasi oleh $request->validate() di atas,
                // jadi cukup loop langsung.
                if ($request->hasFile('lampiran')) {
                    foreach ($request->file('lampiran') as $file) {
                        // Simpan ke storage/app/public/lampiran_balasan/
                        // Akses via: asset('storage/lampiran_balasan/...')
                        $path = $file->store('lampiran_balasan', 'public');
                        $uploadedPaths[] = $path; // catat untuk rollback fisik jika perlu

                        Lampiran_balasan::create([
                            'id_balasanpengaduan' => $balasan->id_balasanpengaduan,
                            'tipe'                => $this->tipeFile($file),
                            'path'                => $path,
                        ]);
                    }
                }

                // 3. Update status pengaduan
                $pengaduan->update(['status' => $validated['status']]);
            });
        } catch (\Throwable $e) {
            // Transaksi DB gagal → hapus file fisik yang sudah terlanjur diupload
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e; // lempar ulang agar Laravel tampilkan pesan error
        }

        return redirect()
            ->route('pengaduan.show', $pengaduan->id_pengaduan)
            ->with('success', 'Balasan berhasil dikirim. Status diperbarui menjadi "' . self::STATUS_OPTIONS[$validated['status']] . '".');
    }

    // ─────────────────────────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $balasan = Balasanpengaduan::with([
            'pengaduan.masyarakat',
            'lampiran_balasan',        // eager load agar langsung tampil di view
        ])->findOrFail($id);

        $pengaduan = $balasan->pengaduan;

        $pegawai = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403, 'Anda tidak memiliki izin untuk mengedit balasan ini.');

        $statusOptions = self::STATUS_OPTIONS;

        return view(
            'pages.balasan_pengaduan.edit',
            compact('balasan', 'pengaduan', 'pegawai', 'statusOptions')
        );
    }

    // ─────────────────────────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $balasan = Balasanpengaduan::with([
            'pengaduan.masyarakat',
            'lampiran_balasan',
        ])->findOrFail($id);

        $pengaduan = $balasan->pengaduan;

        $pegawai = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403);

        $validated = $request->validate([
            'balasan'            => 'required|string|max:5000',
            'tanggal_balasan'    => 'required|date',
            'status'             => 'required|in:' . implode(',', array_keys(self::STATUS_OPTIONS)),
            'lampiran'           => 'nullable|array',
            'lampiran.*'         => 'file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
            'hapus_lampiran'     => 'nullable|array',
            'hapus_lampiran.*'   => 'integer|exists:lampiran_balasan,id',
        ], [
            'balasan.required'         => 'Isi balasan tidak boleh kosong.',
            'tanggal_balasan.required' => 'Tanggal balasan harus diisi.',
            'status.required'          => 'Status pengaduan harus dipilih.',
            'status.in'                => 'Status yang dipilih tidak valid.',
            'lampiran.*.max'           => 'Ukuran tiap file maksimal 10 MB.',
            'lampiran.*.mimes'         => 'Format file tidak didukung (JPG, PNG, WEBP, PDF, DOC, DOCX).',
        ]);

        $pathsToDelete   = []; // file lama yang akan dihapus dari disk setelah transaksi sukses
        $newUploadedPaths = []; // file baru yang diupload, untuk rollback jika transaksi gagal

        try {
            DB::transaction(function () use ($validated, $request, $balasan, $pengaduan, &$pathsToDelete, &$newUploadedPaths) {

                // 1. Update teks balasan
                $balasan->update([
                    'balasan'         => $validated['balasan'],
                    'tanggal_balasan' => $validated['tanggal_balasan'],
                ]);

                // 2. Tandai dan hapus lampiran lama yang dipilih
                if (! empty($validated['hapus_lampiran'])) {
                    $toDelete = Lampiran_balasan::whereIn('id', $validated['hapus_lampiran'])
                        ->where('id_balasanpengaduan', $balasan->id_balasanpengaduan) // validasi kepemilikan
                        ->get();

                    foreach ($toDelete as $lmp) {
                        $pathsToDelete[] = $lmp->path; // hapus file SETELAH transaksi sukses
                        $lmp->delete();
                    }
                }

                // 3. Hitung sisa lampiran setelah penghapusan (fresh dari DB)
                $sisaLampiran = Lampiran_balasan::where('id_balasanpengaduan', $balasan->id_balasanpengaduan)->count();
                $newFiles     = $request->hasFile('lampiran') ? count($request->file('lampiran')) : 0;

                if ($sisaLampiran + $newFiles > 10) {
                    throw new \Exception("Total lampiran tidak boleh lebih dari 10 file. Sisa: {$sisaLampiran}, Baru: {$newFiles}.");
                }

                // 4. Simpan lampiran baru
                if ($request->hasFile('lampiran')) {
                    foreach ($request->file('lampiran') as $file) {
                        $path = $file->store('lampiran_balasan', 'public');
                        $newUploadedPaths[] = $path; // catat untuk rollback fisik jika perlu

                        Lampiran_balasan::create([
                            'id_balasanpengaduan' => $balasan->id_balasanpengaduan,
                            'tipe'                => $this->tipeFile($file),
                            'path'                => $path,
                        ]);
                    }
                }

                // 5. Update status pengaduan
                $pengaduan->update(['status' => $validated['status']]);
            });
        } catch (\Throwable $e) {
            // Transaksi gagal → hapus file baru yang sudah terlanjur diupload secara fisik
            foreach ($newUploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }

        // Hapus file lama secara fisik SETELAH transaksi sukses
        foreach ($pathsToDelete as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('pengaduan.show', $pengaduan->id_pengaduan)
            ->with('success', 'Balasan berhasil diperbarui. Status: "' . self::STATUS_OPTIONS[$validated['status']] . '".');
    }
}
