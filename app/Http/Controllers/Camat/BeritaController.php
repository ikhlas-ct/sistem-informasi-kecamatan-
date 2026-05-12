<?php

namespace App\Http\Controllers\Camat;

use App\Models\User;
use App\Models\Konten;
use App\Models\Pegawai;
use App\Models\Kategori;
use App\Models\Masyarakat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\konten\PegawaiKonten;
use App\Notifications\konten\MasyarakatKonten;


class BeritaController extends Controller
{

    public function dashboard()
    {

        return view('pages.camat.dashboard', );
    }


    public function index(Request $request)
{
    $user   = Auth::user();
    $search = $request->input('search');
    // tambahkan wildcard untuk boolean mode
    $boolean = $search ? $search . '*' : null;

    // 1) Query dasar: hanya konten bertipe 'berita'
    $query = Konten::where('jenis_konten', 'berita');

    // 2) Jika ada kata kunci, cari di konten ATAU di penulis
    if ($search) {
        // a) MATCH di konten
        $query->where(function($q) use($boolean){
            $q->whereRaw(
                "MATCH(judul, isi) AGAINST(? IN BOOLEAN MODE)",
                [$boolean]
            );
        });

        // b) cari id_user dari pegawai yang cocok
        $pegawaiIds = Pegawai::whereRaw(
            "MATCH(nama_pegawai, nip) AGAINST(? IN BOOLEAN MODE)",
            [$boolean]
        )->pluck('id_user')->toArray();

        // c) cari id_user dari masyarakat yang cocok
        $masyarakatIds = Masyarakat::whereRaw(
            "MATCH(nama_masyarakat, nik) AGAINST(? IN BOOLEAN MODE)",
            [$boolean]
        )->pluck('id_user')->toArray();

        $authorIds = array_merge($pegawaiIds, $masyarakatIds);

        // d) tambahkan OR penulis
        if (count($authorIds)) {
            $query->orWhereIn('id_user', $authorIds);
        }
    }

    // 3) Batasi hanya milik sendiri jika bukan camat
    if ($user->role !== 'camat') {
        $query->where('id_user', $user->id);
    }

    // 4) urut terbaru & paginate, sertakan kembali 'search'
    $konten = $query
    ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
    ->orderByDesc('created_at')
    ->paginate(10)
    ->appends(['search' => $search]);


    $kategori = Kategori::all();

    // 5) ke view, kirim juga $search untuk refill input
    return view('pages.berita.berita', compact('konten','kategori','search'));
}



    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori,id_kategori',
            'gambar' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($user->role === 'camat') {
            $rules['status'] = 'required|in:aktif,nonaktif';
        }

        $request->validate($rules);

        $gambarPath = $request->file('gambar')->store('berita', 'public');


        $originalSlug = Str::slug($request->judul);
        $slug = $originalSlug;
        $counter = 1;
        while (Konten::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $originalJudul = $request->judul;
        $judul = $originalJudul;
        $counter = 1;
        while (Konten::where('judul', $judul)->exists()) {
            $judul = $originalJudul . '-' . $counter;
            $counter++;
        }

        $konten = Konten::create([
            'judul' => $judul,
            'isi' => $request->isi,
            'id_user' => $user->id,
            'slug' => $slug,
            'jenis_konten' => 'berita', // atau bisa ambil dari request
            'gambar' => $gambarPath,
            'status' => $user->role === 'camat' ? $request->status : 'pending',
        ]);
        $konten->kategori()->attach($request->kategori);


        $jenis_konten = 'berita';

        $countKontenBaru = Konten::where('jenis_konten', $jenis_konten)
        ->where('status', 'pending')
        ->whereDate('created_at', now()->toDateString())
        ->count();

    // Kirim notifikasi agregat ke Pegawai
    $pegawaiUsers = User::where('role', 'pegawai')->get();
    foreach ($pegawaiUsers as $pegawaiUser) {
        $existing = $pegawaiUser->unreadNotifications->first(function($notif) use ($jenis_konten) {
            return isset($notif->data['type'], $notif->data['jenis_konten']) &&
                   $notif->data['type'] === 'konten' &&
                   $notif->data['jenis_konten'] === $jenis_konten;
        });

        if ($existing) {
            $data = $existing->data;
            $data['message'] = "Ada {$countKontenBaru} konten baru dalam kategori {$jenis_konten} yang perlu diperiksa.";
            $data['count'] = $countKontenBaru;
            $existing->update(['data' => $data]);
        } else {
            $pegawaiUser->notify(new PegawaiKonten($jenis_konten, $countKontenBaru, [
                'type'         => 'konten',
                'jenis_konten' => $jenis_konten,
            ]));
        }
    }

    // Kirim notifikasi agregat ke Camat
    $camatUsers = User::where('role', 'camat')->get();
    foreach ($camatUsers as $camatUser) {
        $existing = $camatUser->unreadNotifications->first(function($notif) use ($jenis_konten) {
            return isset($notif->data['type'], $notif->data['jenis_konten']) &&
                   $notif->data['type'] === 'konten' &&
                   $notif->data['jenis_konten'] === $jenis_konten;
        });

        if ($existing) {
            $data = $existing->data;
            $data['message'] = "Ada {$countKontenBaru} konten baru dalam kategori {$jenis_konten} yang perlu diperiksa.";
            $data['count'] = $countKontenBaru;
            $existing->update(['data' => $data]);
        } else {
            $camatUser->notify(new PegawaiKonten($jenis_konten, $countKontenBaru, [
                'type'         => 'konten',
                'jenis_konten' => $jenis_konten,
            ]));
        }
    }




        return redirect()->back()->with('success', 'Berita berhasil ditambahkan!');
    }



    public function edit($slug)
    {
        $kategori = Kategori::all();
        $berita = Konten::where('slug', $slug)->firstOrFail();
        return view('pages.berita.berita_edit', compact('berita','kategori'));
    }
    public function update(Request $request, $id_konten)
    {
        $user = Auth::user();

        // Validasi input
        $rules = [
            'judul'    => 'required|string|max:255',
            'isi'      => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori,id_kategori',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        // Validasi tambahan untuk 'camat' role
        if ($user->role === 'camat') {
            $rules['status'] = 'required|in:aktif,nonaktif';
        }

        $request->validate($rules);

        // Temukan konten berdasarkan id
        $berita = Konten::findOrFail($id_konten);

        // Jika ada gambar baru, simpan dan perbarui; jika tidak, gunakan gambar yang lama
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        } else {
            $gambarPath = $berita->gambar;
        }

        // Membuat slug unik
        $slug = Str::slug($request->judul);
        $slugCount = Konten::where('slug', 'LIKE', "{$slug}%")
            ->where('id_konten', '!=', $id_konten)
            ->count();
        if ($slugCount) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Membuat judul unik
        $judul = $request->judul;
        $judulCount = Konten::where('judul', 'LIKE', "{$judul}%")
            ->where('id_konten', '!=', $id_konten)
            ->count();
        if ($judulCount) {
            $judul .= '-' . ($judulCount + 1);
        }

        // Data update yang akan disimpan
        $dataUpdate = [
            'judul'      => $judul,
            'isi'        => $request->isi,
            'slug'       => $slug,
            'gambar'     => $gambarPath,
        ];

        // Jika user adalah camat, perbarui status aktif
        if ($user->role === 'camat') {
            $dataUpdate['aktif'] = $request->status;
        }

        // Update konten
        $berita->update($dataUpdate);

        $berita->kategori()->sync($request->kategori);

        // Redirect setelah berhasil
        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui!');
    }


public function destroy($id)
{
    $berita = Konten::findOrFail($id);

    // Hapus gambar utama jika ada
    if ($berita->gambar && \Storage::disk('public')->exists($berita->gambar)) {
        \Storage::disk('public')->delete($berita->gambar);
    }

    // Cari dan hapus semua gambar di dalam isi konten (misal: <img src="...">)
    if ($berita->isi) {
        // Ambil semua src gambar dari tag <img>
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $berita->isi, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $imgUrl) {
                // Ambil path relatif dari url (misal: storage/upload/xxx.jpg)
                $urlPath = parse_url($imgUrl, PHP_URL_PATH);
                $relative = ltrim($urlPath, '/storage/');
                if (\Storage::disk('public')->exists($relative)) {
                    \Storage::disk('public')->delete($relative);
                }
            }
        }
    }

    // Hapus relasi kategori (pivot)
    $berita->kategori()->detach();

    // Hapus data berita
    $berita->delete();

    return redirect()->route('berita.index')->with('success', 'Berita beserta seluruh gambar berhasil dihapus');
}





    public function toggleStatus(Request $request, $id)
    {
        // Cari data konten berdasarkan ID
        $konten = Konten::findOrFail($id);

        // Ambil nilai status dari request
        $status = $request->input('status');

        // Validasi bahwa nilai status hanya "aktif" atau "nonaktif"
        if (!in_array($status, ['aktif', 'nonaktif'])) {
            return redirect()->back()->with('error', 'Status tidak valid!');
        }

        // Update status
        $konten->status = $status;
        $konten->save();
        $konten->refresh();

        // Notifikasi (jika diperlukan)
        $statusText = $konten->status === 'aktif' ? 'disetujui' : 'ditolak';
        if ($konten->user) {
            $konten->user->notify(new MasyarakatKonten($konten, $statusText));
        }

        return redirect()->back()->with('success', 'Status potensi berhasil diperbarui!');
    }






    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $path = $request->file('image')
                        ->store('upload', 'public');

        // bangun URL: "/storage/upload/abc123.jpg"
        $url = asset("storage/{$path}");

        return response()->json(['url' => $url]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'src' => 'required|string',
        ]);

        // ambil path saja, misalnya "/storage/upload/xxx.png"
        $urlPath = parse_url($request->src, PHP_URL_PATH);

        // buang prefix "/storage/" → "upload/xxx.png"
        $relative = ltrim($urlPath, '/storage/');


        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }






}
