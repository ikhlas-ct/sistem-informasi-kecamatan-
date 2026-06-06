<?php

namespace App\Http\Controllers;

use App\Models\Lampiran_pengaduan;
use App\Models\Pegawai;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Ambil data masyarakat milik user yang login.
     */
    private function getMasyarakat()
    {
        return Auth::user()->masyarakat;
    }

    /**
     * INDEX – Daftar pengaduan milik masyarakat yang login.
     */
    public function index(Request $request)
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->back()->with('error', 'Data masyarakat tidak ditemukan.');
        }

        $query = Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)
            ->with('lampiran_pengaduan')
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_pengaduan', 'like', '%' . $request->search . '%')
                    ->orWhere('hal_pengaduan', 'like', '%' . $request->search . '%');
            });
        }

        $pengaduans = $query->paginate(10)->withQueryString();

        // Stat counts
        $stats = [
            'total'    => Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)->count(),
            'pending'  => Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)->where('status', 'pending')->count(),
            'diproses' => Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)->where('status', 'diproses')->count(),
            'selesai'  => Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)->where('status', 'selesai')->count(),
            'ditolak'  => Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)->where('status', 'ditolak')->count(),
        ];

        return view('pages.pengaduan.index', compact('pengaduans', 'stats'));
    }

    /**
     * CREATE – Form tambah pengaduan.
     */
    public function create()
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->route('pengaduan.index')->with('error', 'Data masyarakat tidak ditemukan.');
        }

        return view('pages.pengaduan.create');
    }

    /**
     * STORE – Simpan pengaduan baru beserta lampiran.
     */
    public function store(Request $request)
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->route('pengaduan.index')->with('error', 'Data masyarakat tidak ditemukan.');
        }

        $request->validate([
            'judul_pengaduan'   => 'required|string|max:255',
            'hal_pengaduan'     => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'alamat'            => 'nullable|string|max:500',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'tanggal_pengaduan' => 'required|date',
            // 'lampiran' = array file, maks 10 elemen
            'lampiran'          => 'nullable|array|max:10',
            // tiap elemen: wajib file, format & ukuran dibatasi
            'lampiran.*'        => 'file|mimes:jpg,jpeg,webp,png,pdf,doc,docx|max:10240',
        ], [
            'judul_pengaduan.required'   => 'Judul pengaduan wajib diisi.',
            'hal_pengaduan.required'     => 'Hal pengaduan wajib diisi.',
            'deskripsi.required'         => 'Deskripsi wajib diisi.',
            'tanggal_pengaduan.required' => 'Tanggal pengaduan wajib diisi.',
            'lampiran.max'               => 'Maksimal 10 file lampiran.',
            'lampiran.*.mimes'           => 'Format file harus jpg, jpeg, webp, png, pdf, doc, atau docx.',
            'lampiran.*.max'             => 'Ukuran tiap file maksimal 10 MB.',
        ]);

        $pengaduan = Pengaduan::create([
            'id_masyarakat'     => $masyarakat->id_masyarakat,
            'judul_pengaduan'   => $request->judul_pengaduan,
            'hal_pengaduan'     => $request->hal_pengaduan,
            'deskripsi'         => $request->deskripsi,
            'alamat'            => $request->alamat,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'tanggal_pengaduan' => $request->tanggal_pengaduan,
            'status'            => 'pending',
        ]);

        // Simpan lampiran — path disimpan relatif ke disk 'public'
        // Untuk ditampilkan: asset('storage/' . $lmp->path)
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $ext  = strtolower($file->getClientOriginalExtension());
                $tipe = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'gambar' : 'file';

                // Simpan ke storage/app/public/pengaduan/lampiran/
                $path = $file->store('pengaduan/lampiran', 'public');

                Lampiran_pengaduan::create([
                    'id_pengaduan' => $pengaduan->id_pengaduan,
                    'tipe'         => $tipe,
                    'path'         => $path,   // contoh: "pengaduan/lampiran/abc123.jpg"
                ]);
            }
        }

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim dan sedang menunggu proses.');
    }

    /**
     * SHOW – Detail pengaduan.
     *
     * FIX: role check menggunakan Auth::user()->getRoleLabel()
     *      bukan $pegawai->role (field berbeda).
     */
    public function show($id)
    {
        $user       = Auth::user();
        $masyarakat = $this->getMasyarakat();

        $query = Pengaduan::with([
            'lampiran_pengaduan',
            'balasanpengaduan.pegawai',
            'balasanpengaduan.lampiran_balasan',
        ]);

        if ($masyarakat) {
            // ── Masyarakat: hanya milik sendiri ──────────────────────
            $query->where('id_masyarakat', $masyarakat->id_masyarakat);
        } else {
            // ── Pegawai / Camat ───────────────────────────────────────
            $pegawai = Pegawai::where('id_user', $user->id)->first();

            if (!$pegawai) {
                abort(403, 'Akses ditolak.');
            }

            // Gunakan getRoleLabel() dari User, bukan field pegawai->role
            $roleLabel    = $user->getRoleLabel();
            $bolehatau  = ['camat', 'staf_camat'];

            if (!in_array($roleLabel, $bolehatau)) {
                // Wali nagari / staf nagari → hanya pengaduan dari nagari sendiri
                $query->whereHas('masyarakat', function ($q) use ($pegawai) {
                    $q->where('id_nagari', $pegawai->id_nagari);
                });
            }
            // camat / staf_camat → bisa lihat semua, tidak perlu filter
        }

        $pengaduan = $query->findOrFail($id);

        return view('pages.pengaduan.show', compact('pengaduan'));
    }

    /**
     * EDIT – Form edit pengaduan (hanya milik sendiri & status pending).
     */
    public function edit($id)
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Data masyarakat tidak ditemukan.');
        }

        $pengaduan = Pengaduan::with('lampiran_pengaduan')
            ->where('id_masyarakat', $masyarakat->id_masyarakat)
            ->findOrFail($id);

        if ($pengaduan->status !== 'pending') {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit.');
        }

        return view('pages.pengaduan.edit', compact('pengaduan'));
    }

    /**
     * UPDATE – Simpan perubahan pengaduan.
     */
    public function update(Request $request, $id)
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Data masyarakat tidak ditemukan.');
        }

        $pengaduan = Pengaduan::where('id_masyarakat', $masyarakat->id_masyarakat)
            ->findOrFail($id);

        if ($pengaduan->status !== 'pending') {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit.');
        }

        // Hitung total lampiran setelah update untuk validasi batas 10
        $jumlahAda      = $pengaduan->lampiran_pengaduan()->count();
        $jumlahDihapus  = count($request->input('hapus_lampiran', []));
        $jumlahBaru     = $request->hasFile('lampiran') ? count($request->file('lampiran')) : 0;
        $totalAkhir     = ($jumlahAda - $jumlahDihapus) + $jumlahBaru;

        $request->validate([
            'judul_pengaduan'   => 'required|string|max:255',
            'hal_pengaduan'     => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'alamat'            => 'nullable|string|max:500',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'tanggal_pengaduan' => 'required|date',
            'lampiran'          => 'nullable|array',
            'lampiran.*'        => 'file|mimes:jpg,jpeg,webp,png,pdf,doc,docx|max:10240',
        ], [
            'judul_pengaduan.required'   => 'Judul pengaduan wajib diisi.',
            'hal_pengaduan.required'     => 'Hal pengaduan wajib diisi.',
            'deskripsi.required'         => 'Deskripsi wajib diisi.',
            'tanggal_pengaduan.required' => 'Tanggal pengaduan wajib diisi.',
            'lampiran.*.mimes'           => 'Format file harus jpg, jpeg, webp, png, pdf, doc, atau docx.',
            'lampiran.*.max'             => 'Ukuran tiap file maksimal 10 MB.',
        ]);

        if ($totalAkhir > 10) {
            return back()
                ->withErrors(['lampiran' => 'Total lampiran tidak boleh lebih dari 10 file.'])
                ->withInput();
        }

        $pengaduan->update([
            'judul_pengaduan'   => $request->judul_pengaduan,
            'hal_pengaduan'     => $request->hal_pengaduan,
            'deskripsi'         => $request->deskripsi,
            'alamat'            => $request->alamat,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'tanggal_pengaduan' => $request->tanggal_pengaduan,
        ]);

        // Hapus lampiran yang dicentang user (hapus_lampiran[])
        if ($request->filled('hapus_lampiran')) {
            $lampiranHapus = Lampiran_pengaduan::whereIn('id', $request->hapus_lampiran)
                ->where('id_pengaduan', $pengaduan->id_pengaduan)
                ->get();

            foreach ($lampiranHapus as $lmp) {
                Storage::disk('public')->delete($lmp->path);
                $lmp->delete();
            }
        }

        // Simpan lampiran baru
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $ext  = strtolower($file->getClientOriginalExtension());
                $tipe = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'gambar' : 'file';
                $path = $file->store('pengaduan/lampiran', 'public');

                Lampiran_pengaduan::create([
                    'id_pengaduan' => $pengaduan->id_pengaduan,
                    'tipe'         => $tipe,
                    'path'         => $path,
                ]);
            }
        }

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * DESTROY – Hapus pengaduan (hanya milik sendiri & status pending).
     */
    public function destroy($id)
    {
        $masyarakat = $this->getMasyarakat();

        if (!$masyarakat) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Data masyarakat tidak ditemukan.');
        }

        $pengaduan = Pengaduan::with('lampiran_pengaduan')
            ->where('id_masyarakat', $masyarakat->id_masyarakat)
            ->findOrFail($id);

        if ($pengaduan->status !== 'pending') {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus.');
        }

        // Hapus semua file lampiran dari storage
        foreach ($pengaduan->lampiran_pengaduan as $lmp) {
            Storage::disk('public')->delete($lmp->path);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
