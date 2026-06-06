<?php

namespace App\Http\Controllers;

use App\Models\DokumenBersama;
use App\Models\DokumenLampiran;
use App\Models\DokumenLink;
use App\Models\DokumenPenerima;
use App\Models\Nagari;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokumenBersamaController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // KOTAK MASUK – dokumen yang DITERIMA user yang login
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DokumenBersama::query()
            ->diterimaOleh($user->id)
            ->with(['pengirim', 'penerimas', 'lampiran', 'links'])
            ->latest();

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $dokumens   = $query->paginate(15)->withQueryString();
        $roleLabel  = $user->getRoleLabel();
        $isSuperAdmin = $user->isSuperAdmin();

        // Hitung berapa yang belum dibaca (untuk badge)
        $belumDibaca = DokumenPenerima::where('id_user', $user->id)
            ->where('sudah_dibaca', false)
            ->count();

        return view('pages.dokumen.index', compact(
            'dokumens', 'roleLabel', 'isSuperAdmin', 'belumDibaca'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // TERKIRIM – dokumen yang DIKIRIM user yang login
    // ──────────────────────────────────────────────────────────────
    public function terkirim(Request $request)
    {
        $user = Auth::user();

        $query = DokumenBersama::query()
            ->dikirimOleh($user->id)
            ->with(['penerimas.user', 'lampiran', 'links'])
            ->latest();

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dokumens   = $query->paginate(15)->withQueryString();
        $roleLabel  = $user->getRoleLabel();
        $isSuperAdmin = $user->isSuperAdmin();

        return view('pages.dokumen.terkirim', compact(
            'dokumens', 'roleLabel', 'isSuperAdmin'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // FORM BUAT
    // ──────────────────────────────────────────────────────────────
    public function create()
    {
        $user      = Auth::user();
        $nagaris   = Nagari::where('status', 1)->orderBy('nama_nagari')->get();
        $roleLabel = $user->getRoleLabel();

        return view('pages.dokumen.create', compact('user', 'nagaris', 'roleLabel'));
    }

    // ──────────────────────────────────────────────────────────────
    // SIMPAN DOKUMEN BARU
    // ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'judul'                      => 'required|string|max:255',
            'deskripsi'                  => 'nullable|string',
            'penerima'                   => 'required|array|min:1',
            'penerima.*.id_user'         => 'required|exists:users,id',
            'penerima.*.izin_download'   => 'nullable|boolean',
            'penerima.*.izin_lihat'      => 'nullable|boolean',
            'lampiran.*'                 => 'nullable|file|max:20480',   // maks 20 MB per file
            'foto.*'                     => 'nullable|image|max:5120',   // maks 5 MB per foto
            'links.*.url'                => 'nullable|url|max:2000',
            'links.*.judul'              => 'nullable|string|max:255',
        ], [
            'penerima.required'          => 'Pilih minimal satu penerima.',
            'penerima.*.id_user.exists'  => 'Salah satu penerima tidak valid.',
            'links.*.url.url'            => 'Format URL link tidak valid.',
        ]);

        DB::transaction(function () use ($request) {

            // 1. Buat dokumen utama
            $dokumen = DokumenBersama::create([
                'id_user'   => Auth::id(),
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status'    => 'aktif',
            ]);

            // 2. Simpan penerima + izin
            foreach ($request->penerima as $data) {
                DokumenPenerima::create([
                    'id_dokumen'    => $dokumen->id,
                    'id_user'       => $data['id_user'],
                    'izin_download' => isset($data['izin_download']) ? (bool)$data['izin_download'] : true,
                    'izin_lihat'    => isset($data['izin_lihat'])    ? (bool)$data['izin_lihat']    : true,
                    'sudah_dibaca'  => false,
                ]);
            }

            // 3. Upload lampiran file
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    if (! $file->isValid()) continue;

                    $namaAsli   = $file->getClientOriginalName();
                    $namaSimpan = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path       = $file->storeAs('dokumen_bersama/file', $namaSimpan, 'public');

                    DokumenLampiran::create([
                        'id_dokumen'  => $dokumen->id,
                        'tipe'        => 'file',
                        'nama_asli'   => $namaAsli,
                        'nama_simpan' => $namaSimpan,
                        'path'        => $path,
                        'mime_type'   => $file->getMimeType(),
                        'ukuran_kb'   => (int) ceil($file->getSize() / 1024),
                    ]);
                }
            }

            // 4. Upload foto
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    if (! $file->isValid()) continue;

                    $namaAsli   = $file->getClientOriginalName();
                    $namaSimpan = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path       = $file->storeAs('dokumen_bersama/foto', $namaSimpan, 'public');

                    DokumenLampiran::create([
                        'id_dokumen'  => $dokumen->id,
                        'tipe'        => 'foto',
                        'nama_asli'   => $namaAsli,
                        'nama_simpan' => $namaSimpan,
                        'path'        => $path,
                        'mime_type'   => $file->getMimeType(),
                        'ukuran_kb'   => (int) ceil($file->getSize() / 1024),
                    ]);
                }
            }

            // 5. Simpan link-link eksternal
            if ($request->filled('links')) {
                foreach ($request->links as $link) {
                    if (empty($link['url'])) continue;
                    DokumenLink::create([
                        'id_dokumen' => $dokumen->id,
                        'judul'      => $link['judul'] ?? null,
                        'url'        => $link['url'],
                    ]);
                }
            }
        });

        return redirect()->route('dokumen.terkirim')
            ->with('success', 'Dokumen berhasil dikirim.');
    }

    // ──────────────────────────────────────────────────────────────
    // DETAIL / SHOW
    // ──────────────────────────────────────────────────────────────
    public function show(int $id)
    {
        $user    = Auth::user();
        $dokumen = DokumenBersama::with([
            'pengirim',
            'penerimas.user.pegawai.nagari',
            'penerimas.user.masyarakat',
            'lampiran',
            'links',
        ])->findOrFail($id);

        // Hanya pengirim atau penerima yang boleh melihat
        $isPengirim = $dokumen->id_user === $user->id;
        $penerima   = $dokumen->getPenerima($user->id);

        if (! $isPengirim && ! $penerima) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Jika penerima, cek izin lihat
        if (! $isPengirim && $penerima && ! $penerima->bolehLihat()) {
            abort(403, 'Anda tidak memiliki izin untuk melihat dokumen ini.');
        }

        // Tandai sudah dibaca (hanya untuk penerima)
        if ($penerima) {
            $penerima->tandaiDibaca();
        }

        $roleLabel = $user->getRoleLabel();

        return view('pages.dokumen.show', compact(
            'dokumen', 'isPengirim', 'penerima', 'roleLabel'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // FORM EDIT
    // ──────────────────────────────────────────────────────────────
    public function edit(int $id)
    {
        $user    = Auth::user();
        $dokumen = DokumenBersama::with(['penerimas.user', 'lampiran', 'links'])->findOrFail($id);

        // Hanya pengirim yang boleh edit
        if ($dokumen->id_user !== $user->id) {
            abort(403, 'Hanya pengirim yang dapat mengedit dokumen ini.');
        }

        $nagaris   = Nagari::where('status', 1)->orderBy('nama_nagari')->get();
        $roleLabel = $user->getRoleLabel();

        return view('pages.dokumen.edit', compact('dokumen', 'nagaris', 'roleLabel'));
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $user    = Auth::user();
        $dokumen = DokumenBersama::findOrFail($id);

        if ($dokumen->id_user !== $user->id) {
            abort(403);
        }

        $request->validate([
            'judul'                    => 'required|string|max:255',
            'deskripsi'                => 'nullable|string',
            'status'                   => 'required|in:aktif,arsip',
            'penerima'                 => 'required|array|min:1',
            'penerima.*.id_user'       => 'required|exists:users,id',
            'penerima.*.izin_download' => 'nullable|boolean',
            'penerima.*.izin_lihat'    => 'nullable|boolean',
            'lampiran.*'               => 'nullable|file|max:20480',
            'foto.*'                   => 'nullable|image|max:5120',
            'links.*.url'              => 'nullable|url|max:2000',
            'links.*.judul'            => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $dokumen) {

            // Update dokumen utama
            $dokumen->update([
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status'    => $request->status,
            ]);

            // Sync penerima: hapus yang sudah tidak ada, tambah yang baru
            $idUserBaru = collect($request->penerima)->pluck('id_user')->map('intval');

            // Hapus penerima yang dihilangkan
            $dokumen->penerimas()->whereNotIn('id_user', $idUserBaru)->delete();

            // Upsert penerima
            foreach ($request->penerima as $data) {
                $dokumen->penerimas()->updateOrCreate(
                    ['id_user' => (int)$data['id_user']],
                    [
                        'izin_download' => isset($data['izin_download']) ? (bool)$data['izin_download'] : true,
                        'izin_lihat'    => isset($data['izin_lihat'])    ? (bool)$data['izin_lihat']    : true,
                    ]
                );
            }

            // Tambah lampiran file baru (yang sudah ada tidak disentuh kecuali dihapus manual via AJAX)
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    if (! $file->isValid()) continue;
                    $namaAsli   = $file->getClientOriginalName();
                    $namaSimpan = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path       = $file->storeAs('dokumen_bersama/file', $namaSimpan, 'public');
                    DokumenLampiran::create([
                        'id_dokumen'  => $dokumen->id,
                        'tipe'        => 'file',
                        'nama_asli'   => $namaAsli,
                        'nama_simpan' => $namaSimpan,
                        'path'        => $path,
                        'mime_type'   => $file->getMimeType(),
                        'ukuran_kb'   => (int) ceil($file->getSize() / 1024),
                    ]);
                }
            }

            // Tambah foto baru
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    if (! $file->isValid()) continue;
                    $namaAsli   = $file->getClientOriginalName();
                    $namaSimpan = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path       = $file->storeAs('dokumen_bersama/foto', $namaSimpan, 'public');
                    DokumenLampiran::create([
                        'id_dokumen'  => $dokumen->id,
                        'tipe'        => 'foto',
                        'nama_asli'   => $namaAsli,
                        'nama_simpan' => $namaSimpan,
                        'path'        => $path,
                        'mime_type'   => $file->getMimeType(),
                        'ukuran_kb'   => (int) ceil($file->getSize() / 1024),
                    ]);
                }
            }

            // Sync link: hapus semua lalu buat ulang (lebih simpel)
            $dokumen->links()->delete();
            if ($request->filled('links')) {
                foreach ($request->links as $link) {
                    if (empty($link['url'])) continue;
                    DokumenLink::create([
                        'id_dokumen' => $dokumen->id,
                        'judul'      => $link['judul'] ?? null,
                        'url'        => $link['url'],
                    ]);
                }
            }
        });

        return redirect()->route('dokumen.show', $id)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────────────────────
    // HAPUS DOKUMEN
    // ──────────────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $user    = Auth::user();
        $dokumen = DokumenBersama::with('lampiran')->findOrFail($id);

        if ($dokumen->id_user !== $user->id && ! $user->isSuperAdmin()) {
            abort(403);
        }

        // Hapus semua file fisik
        foreach ($dokumen->lampiran as $lampiran) {
            Storage::disk('public')->delete($lampiran->path);
        }

        $dokumen->delete(); // cascades via DB

        return redirect()->route('dokumen.terkirim')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    // ──────────────────────────────────────────────────────────────
    // AJAX – ambil daftar user berdasarkan tipe grup
    // ──────────────────────────────────────────────────────────────
    public function ajaxUsers(Request $request)
    {
        $tipe     = $request->tipe;          // kecamatan | nagari | masyarakat
        $idNagari = $request->id_nagari;     // wajib jika tipe = nagari / masyarakat
        $q        = $request->q;             // opsional: filter nama

        $users = collect();

        switch ($tipe) {

            // Semua pegawai kecamatan (id_nagari = null) + camat
            case 'kecamatan':
                $users = User::query()
                    ->whereIn('role', ['camat', 'pegawai'])
                    ->whereHas('pegawai', fn ($q) => $q->whereNull('id_nagari'))
                    ->orWhere('role', 'camat')
                    ->with('pegawai')
                    ->where('id', '!=', Auth::id())
                    ->where('status', 'aktif')
                    ->get()
                    ->map(fn ($u) => [
                        'id'    => $u->id,
                        'nama'  => $u->namaTampil(),
                        'label' => $u->getRoleLabel(),
                        'sub'   => 'Kantor Camat',
                    ]);
                break;

            // Semua pegawai nagari tertentu
            case 'nagari':
                abort_if(! $idNagari, 422, 'id_nagari wajib diisi.');
                $users = User::query()
                    ->where('role', 'pegawai')
                    ->whereHas('pegawai', fn ($q) => $q->where('id_nagari', $idNagari))
                    ->with(['pegawai.nagari'])
                    ->where('id', '!=', Auth::id())
                    ->where('status', 'aktif')
                    ->get()
                    ->map(fn ($u) => [
                        'id'    => $u->id,
                        'nama'  => $u->namaTampil(),
                        'label' => $u->getRoleLabel(),
                        'sub'   => $u->pegawai?->nagari?->nama_nagari ?? 'Nagari',
                    ]);
                break;

            // Masyarakat satu nagari
            case 'masyarakat':
                abort_if(! $idNagari, 422, 'id_nagari wajib diisi.');
                $users = User::query()
                    ->where('role', 'masyarakat')
                    ->whereHas('masyarakat', fn ($q) => $q->where('id_nagari', $idNagari))
                    ->with(['masyarakat.nagari'])
                    ->where('id', '!=', Auth::id())
                    ->where('status', 'aktif')
                    ->get()
                    ->map(fn ($u) => [
                        'id'    => $u->id,
                        'nama'  => $u->namaTampil(),
                        'label' => 'Masyarakat',
                        'sub'   => $u->masyarakat?->nagari?->nama_nagari ?? 'Nagari',
                    ]);
                break;

            default:
                return response()->json(['error' => 'Tipe tidak dikenal'], 422);
        }

        // Filter nama jika ada query
        if ($q) {
            $users = $users->filter(
                fn ($u) => Str::contains(Str::lower($u['nama']), Str::lower($q))
            )->values();
        }

        return response()->json($users);
    }

    // ──────────────────────────────────────────────────────────────
    // DOWNLOAD LAMPIRAN
    // ──────────────────────────────────────────────────────────────
    public function downloadLampiran(int $id): StreamedResponse
    {
        $user     = Auth::user();
        $lampiran = DokumenLampiran::with('dokumen')->findOrFail($id);
        $dokumen  = $lampiran->dokumen;

        $isPengirim = $dokumen->id_user === $user->id;
        $penerima   = $dokumen->getPenerima($user->id);

        if (! $isPengirim && ! $penerima) {
            abort(403);
        }

        if (! $isPengirim && $penerima && ! $penerima->bolehDownload()) {
            abort(403, 'Anda tidak memiliki izin untuk mendownload lampiran ini.');
        }

        if (! Storage::disk('public')->exists($lampiran->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($lampiran->path, $lampiran->nama_asli);
    }

    // ──────────────────────────────────────────────────────────────
    // HAPUS SATU LAMPIRAN (via AJAX saat edit)
    // ──────────────────────────────────────────────────────────────
    public function destroyLampiran(int $id)
    {
        $user     = Auth::user();
        $lampiran = DokumenLampiran::with('dokumen')->findOrFail($id);

        if ($lampiran->dokumen->id_user !== $user->id) {
            abort(403);
        }

        Storage::disk('public')->delete($lampiran->path);
        $lampiran->delete();

        return response()->json(['ok' => true]);
    }
}
