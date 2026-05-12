<?php

namespace App\Http\Controllers;

use App\Models\Balasanpengaduan;
use App\Models\Lampiran_balasan;
use App\Models\Pengaduan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BalasanpengaduanController extends Controller
{
    /**
     * Status yang valid untuk pengaduan.
     * ⚠️  Pastikan nilai-nilai ini SAMA PERSIS dengan ENUM di migrasi/tabel pengaduan.
     *     Cek dengan: SHOW COLUMNS FROM pengaduan LIKE 'status';
     */
    private const STATUS_OPTIONS = [
        'pending'   => 'Pending',
        'diproses'  => 'Diproses',
        'selesai'   => 'Selesai',
        'ditolak'   => 'Ditolak',
    ];

    /** Status default saat pertama kali membalas */
    private const STATUS_DEFAULT = 'diproses';

    /**
     * Role yang bisa membalas semua pengaduan (lintas nagari).
     */
    private const ROLES_SEMUA  = ['camat', 'staf_camat'];

    /**
     * Role yang hanya bisa membalas pengaduan dari nagari yang sama.
     */
    private const ROLES_NAGARI = ['kepala_nagari', 'pegawai_nagari'];

    /**
     * Ambil Pegawai yang sedang login.
     */
    private function getPegawai(): ?Pegawai
    {
        $user = Auth::user();
        if (! $user) return null;

        return Pegawai::with('nagari')->where('id_user', $user->id)->first();
    }

    /**
     * Validasi apakah pegawai berhak menangani $pengaduan tertentu.
     */
    private function getAuthorizedPegawai(Pengaduan $pengaduan): ?Pegawai
    {
        $pegawai = $this->getPegawai();
        if (! $pegawai) return null;

        if (in_array($pegawai->role, self::ROLES_SEMUA)) {
            return $pegawai;
        }

        if (in_array($pegawai->role, self::ROLES_NAGARI)) {
            $idNagariPengadu = optional($pengaduan->masyarakat)->id_nagari;
            if ($pegawai->id_nagari && $pegawai->id_nagari == $idNagariPengadu) {
                return $pegawai;
            }
        }

        return null;
    }

    private function isCamat(Pegawai $pegawai): bool
    {
        return in_array($pegawai->role, self::ROLES_SEMUA);
    }

    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $pegawai = $this->getPegawai();
        if (! $pegawai) abort(403, 'Akses ditolak.');

        $query = Pengaduan::with([
            'masyarakat.nagari',
            'lampiran_pengaduan',
            'balasanpengaduan',
        ]);

        if (! $this->isCamat($pegawai)) {
            if (! $pegawai->id_nagari) abort(403, 'Nagari pegawai tidak ditemukan.');
            $query->whereHas('masyarakat', function ($q) use ($pegawai) {
                $q->where('id_nagari', $pegawai->id_nagari);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_pengaduan', 'like', "%{$search}%")
                    ->orWhere('hal_pengaduan',  'like', "%{$search}%")
                    ->orWhereHas('masyarakat', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
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

        $query->orderByRaw("
            CASE WHEN EXISTS (
                SELECT 1 FROM balasanpengaduan b
                WHERE b.id_pengaduan = pengaduan.id_pengaduan
            ) THEN 1 ELSE 0 END ASC
        ")->orderBy('tanggal_pengaduan', 'desc');

        $pengaduans = $query->paginate(15)->withQueryString();

        $statsBase = Pengaduan::query();
        if (! $this->isCamat($pegawai)) {
            $statsBase->whereHas('masyarakat', fn($q) => $q->where('id_nagari', $pegawai->id_nagari));
        }

        $stats = [
            'total'         => (clone $statsBase)->count(),
            'belum_dibalas' => (clone $statsBase)->doesntHave('balasanpengaduan')->count(),
            'sudah_dibalas' => (clone $statsBase)->has('balasanpengaduan')->count(),
            'selesai'       => (clone $statsBase)->where('status', 'selesai')->count(),
            'ditolak'       => (clone $statsBase)->where('status', 'ditolak')->count(),
        ];

        $statusOptions = self::STATUS_OPTIONS;

        return view('pages.balasan_pengaduan.index', compact('pengaduans', 'stats', 'pegawai', 'statusOptions'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create($id_pengaduan)
    {
        $pengaduan = Pengaduan::with([
            'masyarakat',
            'lampiran_pengaduan',
            'balasanpengaduan',
        ])->findOrFail($id_pengaduan);

        $pegawai = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) {
            abort(403, 'Anda tidak memiliki izin untuk membalas pengaduan ini.');
        }

        if ($pengaduan->balasanpengaduan) {
            return redirect()
                ->route('balasanpengaduan.edit', $pengaduan->balasanpengaduan->id_balasanpengaduan)
                ->with('info', 'Pengaduan ini sudah pernah dibalas. Anda dapat mengedit balasan di bawah.');
        }

        $statusOptions = self::STATUS_OPTIONS;
        $statusDefault = self::STATUS_DEFAULT;

        return view('pages.balasan_pengaduan.create', compact('pengaduan', 'pegawai', 'statusOptions', 'statusDefault'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request, $id_pengaduan)
    {
        $pengaduan = Pengaduan::with('masyarakat')->findOrFail($id_pengaduan);
        $pegawai   = $this->getAuthorizedPegawai($pengaduan);
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
            'lampiran.*.max'           => 'Ukuran tiap file maksimal 10 MB.',
            'lampiran.*.mimes'         => 'Format file tidak didukung.',
        ]);

        $balasan = Balasanpengaduan::create([
            'id_pegawai'      => $pegawai->id_pegawai,
            'id_pengaduan'    => $pengaduan->id_pengaduan,
            'balasan'         => $validated['balasan'],
            'tanggal_balasan' => $validated['tanggal_balasan'],
        ]);

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $tipe = Str::startsWith($file->getMimeType(), 'image/') ? 'gambar' : 'file';
                $path = $file->store('lampiran_balasan', 'public');
                Lampiran_balasan::create([
                    'id_balasanpengaduan' => $balasan->id_balasanpengaduan,
                    'tipe'                => $tipe,
                    'path'                => $path,
                ]);
            }
        }

        // Update status pengaduan sesuai pilihan (default: diproses)
        $pengaduan->update(['status' => $validated['status']]);

        return redirect()
            ->route('pengaduan.show', $pengaduan->id_pengaduan)
            ->with('success', 'Balasan berhasil dikirim dan status pengaduan diperbarui menjadi "' . self::STATUS_OPTIONS[$validated['status']] . '".');
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit($id)
    {
        $balasan = Balasanpengaduan::with([
            'pengaduan.masyarakat',
            'lampiran_balasan',
        ])->findOrFail($id);

        $pengaduan = $balasan->pengaduan;
        $pegawai   = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403, 'Anda tidak memiliki izin untuk mengedit balasan ini.');

        $statusOptions = self::STATUS_OPTIONS;

        return view('pages.balasan_pengaduan.edit', compact('balasan', 'pengaduan', 'pegawai', 'statusOptions'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $balasan = Balasanpengaduan::with([
            'pengaduan.masyarakat',
            'lampiran_balasan',
        ])->findOrFail($id);

        $pengaduan = $balasan->pengaduan;
        $pegawai   = $this->getAuthorizedPegawai($pengaduan);
        if (! $pegawai) abort(403);

        $validated = $request->validate([
            'balasan'          => 'required|string|max:5000',
            'tanggal_balasan'  => 'required|date',
            'status'           => 'required|in:' . implode(',', array_keys(self::STATUS_OPTIONS)),
            'lampiran'         => 'nullable|array',
            'lampiran.*'       => 'file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
            'hapus_lampiran'   => 'nullable|array',
            'hapus_lampiran.*' => 'integer|exists:lampiran_balasan,id',
        ], [
            'balasan.required'         => 'Isi balasan tidak boleh kosong.',
            'tanggal_balasan.required' => 'Tanggal balasan harus diisi.',
            'status.required'          => 'Status pengaduan harus dipilih.',
            'status.in'                => 'Status yang dipilih tidak valid.',
            'lampiran.*.max'           => 'Ukuran tiap file maksimal 10 MB.',
            'lampiran.*.mimes'         => 'Format file tidak didukung.',
        ]);

        $balasan->update([
            'balasan'         => $validated['balasan'],
            'tanggal_balasan' => $validated['tanggal_balasan'],
        ]);

        if (! empty($validated['hapus_lampiran'])) {
            foreach ($validated['hapus_lampiran'] as $lid) {
                $lmp = Lampiran_balasan::find($lid);
                if ($lmp && $lmp->id_balasanpengaduan == $balasan->id_balasanpengaduan) {
                    Storage::disk('public')->delete($lmp->path);
                    $lmp->delete();
                }
            }
        }

        if ($request->hasFile('lampiran')) {
            $sisaLampiran = $balasan->lampiran_balasan()->count();
            foreach ($request->file('lampiran') as $file) {
                if ($sisaLampiran >= 10) break;
                $tipe = Str::startsWith($file->getMimeType(), 'image/') ? 'gambar' : 'file';
                $path = $file->store('lampiran_balasan', 'public');
                Lampiran_balasan::create([
                    'id_balasanpengaduan' => $balasan->id_balasanpengaduan,
                    'tipe'                => $tipe,
                    'path'                => $path,
                ]);
                $sisaLampiran++;
            }
        }

        // Update status pengaduan sesuai pilihan
        $pengaduan->update(['status' => $validated['status']]);

        return redirect()
            ->route('pengaduan.show', $pengaduan->id_pengaduan)
            ->with('success', 'Balasan berhasil diperbarui. Status pengaduan: "' . self::STATUS_OPTIONS[$validated['status']] . '".');
    }
}
