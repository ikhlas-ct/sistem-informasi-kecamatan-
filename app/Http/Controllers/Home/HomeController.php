<?php

namespace App\Http\Controllers\Home;

use App\Models\Konten;
use App\Models\Layanan_sop;
use App\Models\Reaksi;
use App\Models\Visitor;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Heroslide;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Kecamatansetting;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class HomeController extends Controller
{
    public function beranda()
    {
        $totalVisitors = Visitor::count();
        $todayVisitors = Visitor::whereDate('created_at', Carbon::today())->count();


        $heros = Heroslide::all();
        $berita = Konten::where('jenis_konten', 'berita')->withCount('komentar')->orderBy('tanggal_publikasi', 'desc')->limit(4)->get();



        $galeri = Konten::whereNotIn('jenis_konten', ['artikel', 'berita'])
            ->orderBy('tanggal_publikasi', 'desc')
            ->get();

        $jenis_konten = $galeri->pluck('jenis_konten')->unique();

        return view('pages.home.beranda', compact('heros', 'berita', 'galeri', 'jenis_konten', 'totalVisitors', 'todayVisitors'));
    }

    public function visi_misi()
    {
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();



        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.profil.visi-misi',compact('categories','recentPosts'));
    }

    public function sejarah(Request $request)
    {
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();
                    $recentPosts = Konten::latest()->take(5)->get();


        return view('pages.home.profil.sejarah', compact(  'categories', 'recentPosts'));
    }
    public function tugas_fungsi()
    {
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.profil.tugas_fungsi',compact('categories','recentPosts'));
    }
    public function geografis()
    {

        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.profil.geografis',compact('categories','recentPosts'));
    }

    public function struktur_ogranisasi()
    {
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.profil.struktur_organisasi',compact('categories','recentPosts'));
    }

    public function galer_foto()
    {
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();
        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.profil.struktur_organisasi', compact('categories', 'recentPosts'));
    }

    public function konten($jenis)
    {


            $posts = Konten::where('jenis_konten', $jenis)
            ->withCount('komentar')
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(5);


            $categories = Kategori::has('konten')
            ->withCount(['konten as kategori_count'])
            ->inRandomOrder()
            ->limit(6)
            ->get();

            $recentPosts = Konten::orderBy('tanggal_publikasi', 'desc')
            ->limit(5)
            ->get();


        $jenis_konten = ucfirst($jenis);

        return view('pages.home.berita', compact('posts', 'categories', 'recentPosts', 'jenis_konten'));
    }

    public function show($slug)
    {
        // 1. Ambil kategori berdasarkan slug
        $kategori = Kategori::where('slug', $slug)->firstOrFail();

        // 2. Query konten-nya via pivot
        $posts = Konten::whereHas('kategori', function($q) use ($kategori) {
                $q->where('kategori.id_kategori', $kategori->id_kategori);
            })
            ->with(['user.pegawai', 'user.masyarakat'])
            ->withCount('komentar')
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(5);

        // 3. Sidebar tetap kategori & recent posts
        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();


        $recentPosts = Konten::orderBy('tanggal_publikasi', 'desc')
        ->limit(5)
        ->get();



        // 4. Judul dinamis
        $jenis_konten = "Kategori " . $kategori->nama_kategori;

        return view('pages.home.berita', compact(  'posts',   'categories',  'recentPosts', 'jenis_konten' ));
    }






public function berita_detail(Request $request, $slug, $jenis_konten)
{
    // Ambil data blog berdasarkan slug
    $blog = Konten::where('slug', $slug)->firstOrFail();

    // Data pendukung lainnya
    $categories = Kategori::has('konten')
    ->withCount(['konten as kategori_count'])
    ->inRandomOrder()
    ->limit(6)
    ->get();

    $recentPosts = Konten::orderBy('tanggal_publikasi', 'desc')
            ->limit(5)
            ->get();




    $komentars = Komentar::with('balasan')->where('id_konten', $blog->id_konten)
                  ->orderBy('created_at')->get();

    // Hitung jumlah reaksi per jenis
    $reaksiCounts = Reaksi::where('id_konten', $blog->id_konten)
        ->select('jenis', \DB::raw('count(*) as total'))
        ->groupBy('jenis')
        ->pluck('total', 'jenis');

    // Ambil reaksi user (jika perlu untuk highlight)
    $userReaksi = Reaksi::where('id_konten', $blog->id_konten)
        ->when(auth()->id(), fn($q) => $q->where('id_user', auth()->id()))
        ->when(!auth()->id(), fn($q) => $q->where('ip_address', request()->ip()))
        ->first();

    // Memecah konten berdasarkan marker atau per 2000 karakter
    $fullContent = $blog->isi; // Pastikan field 'isi' berisi konten berita
    if (strpos($fullContent, '<!--pagebreak-->') !== false) {
        $pages = explode('<!--pagebreak-->', $fullContent);
    } else {
        $limit = 2000;  // Batasi per 2000 karakter
        $pages = str_split($fullContent, $limit);
    }
    // Dapatkan nomor halaman dari query parameter, default 1
    $currentPage = (int)$request->get('page', 1);
    $totalPages = count($pages);
    if ($currentPage < 1 || $currentPage > $totalPages) {
        $currentPage = 1;
    }
    $currentPageContent = $pages[$currentPage - 1];
    $jenis_konten_ = Str::title(Str::replace('_', ' ', $blog->jenis_konten));


    return view('pages.home.berita_detail', compact(
        'blog',
        'categories',
        'recentPosts',
        'komentars',
        'reaksiCounts',
        'userReaksi',
        'currentPageContent',
        'totalPages',
        'currentPage'
    ));
}




    public function potensi($jenis_konten)
    {


        $posts = Konten::where('jenis_konten', $jenis_konten)
        ->withCount('komentar')
        ->orderBy('tanggal_publikasi', 'desc')
        ->paginate(5);


        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();        $recentPosts = Konten::latest()->take(5)->get();

         $jenis_konten = ucfirst(str_replace('_', ' ', $jenis_konten));


        return view('pages.home.berita', compact('posts', 'categories', 'recentPosts', 'jenis_konten'));

    }

    public function potensi_detail($jenis_konten, $slug)
    {
        $blog = Konten::where('jenis_konten', $jenis_konten)
                      ->where('slug', $slug)
                      ->firstOrFail();

                      $categories = Kategori::has('konten')
                      ->withCount(['konten as kategori_count'])
                      ->inRandomOrder()
                      ->limit(6)
                      ->get();
                      $recentPosts = Konten::latest()->take(5)->get();

        $komentars = Komentar::with('user.pegawai', 'user.masyarakat', 'balasan.user.pegawai', 'balasan.user.masyarakat')
        ->where('id_konten', $blog->id_konten)
        ->orderBy('created_at')
        ->get();
        // Hitung jumlah reaksi per jenis
        $reaksiCounts = Reaksi::where('id_konten', $blog->id_konten)
            ->select('jenis', \DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        // Optional: ambil reaksi user (jika perlu untuk highlight)
        $userReaksi = Reaksi::where('id_konten', $blog->id_konten)
            ->when(auth()->id(), fn($q) => $q->where('id_user', auth()->id()))
            ->when(!auth()->id(), fn($q) => $q->where('ip_address', request()->ip()))
            ->first();

            $jenis_konten = ucfirst(str_replace('_', ' ', $jenis_konten));
        return view('pages.home.berita_detail', compact('blog', 'categories', 'recentPosts', 'komentars', 'reaksiCounts', 'userReaksi','jenis_konten'));
    }

    public function galeri_foto()
    {
        // Ambil semua data dari tabel Konten dengan pagination
        $posts = Konten::paginate(9);

        $posts->each(function ($post) {
            $jumlah_foto = 0;

            // Hitung gambar dari kolom 'gambar'
            if (!empty($post->gambar)) {
                $jumlah_foto++;
            }

            // Hitung jumlah gambar yang ada di dalam kolom 'isi' (HTML)
            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $post->isi, $matches);
            $jumlah_foto += count($matches[1]);

            // Minimal ada 1 foto (default jika tidak ada gambar)
            $post->jumlah_foto = $jumlah_foto > 0 ? $jumlah_foto : 1;
        });

        $categories = Kategori::withCount(['konten as posts_count'])->inRandomOrder()->limit(6)->get();

        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.galeri_foto', compact('posts', 'categories', 'recentPosts'));
    }

    public function galeri_foto_detail($slug)
    {
        $post = Konten::where('slug', $slug)->firstOrFail();

        // Ekstrak URL gambar dari kolom 'isi'
        preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $post->isi, $matches);
        $galeri = $matches[1]; // URL gambar

        $categories = Kategori::withCount(['konten as posts_count'])->inRandomOrder()->limit(6)->get();
        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.galeri_foto_detail', compact('post', 'galeri', 'categories', 'recentPosts'));
    }

    public function koleksi_video()
    {
        // Ambil semua data dari tabel Konten yang memiliki video dengan pagination
        $posts = Konten::where('isi', 'like', '%<iframe%')->paginate(9);

        $posts->each(function ($post) {
            $jumlah_video = 0;

            // Hitung jumlah video yang ada di dalam kolom 'isi' (HTML)
            preg_match_all('/<iframe.*?src=["\'](.*?)["\'].*?>/i', $post->isi, $matches);
            $jumlah_video += count($matches[1]);

            // Minimal ada 1 video (default jika tidak ada video)
            $post->jumlah_video = $jumlah_video > 0 ? $jumlah_video : 1;
        });

        $categories = Kategori::withCount(['konten as posts_count'])->inRandomOrder()->limit(6)->get();
        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.koleksi_video.koleksi_video', compact('posts', 'categories', 'recentPosts'));
    }

    public function koleksi_video_detail($slug)
    {
        $post = Konten::where('slug', $slug)->firstOrFail();

        // Ekstrak URL video dari kolom 'isi'
        preg_match_all('/<iframe.*?src=["\'](.*?)["\'].*?>/i', $post->isi, $matches);
        $videos = $matches[1]; // URL video

        $categories = Kategori::withCount(['konten as posts_count'])->inRandomOrder()->limit(6)->get();
        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.koleksi_video.koleksi_video_detail', compact('post', 'videos', 'categories', 'recentPosts'));
    }




       public function portal($slug)
    {
        $portal = Layanan_sop::where('slug', $slug)->firstOrFail();

        $categories = Kategori::has('konten')
        ->withCount(['konten as kategori_count'])
        ->inRandomOrder()
        ->limit(6)
        ->get();
        $recentPosts = Konten::latest()->take(5)->get();

        return view('pages.home.portal',compact('categories','recentPosts','portal'));
    }



    public function contact()
    {
        return view('pages.home.contact', );
    }
}
