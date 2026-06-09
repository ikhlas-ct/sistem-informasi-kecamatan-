<?php

use App\Http\Controllers\BalasanpengaduanController;
use App\Http\Controllers\Camat\CamatController;
use App\Http\Controllers\Camat\CamatsuratketeranganmiskinController;
use App\Http\Controllers\Camat\HeroslideController;
use App\Http\Controllers\Camat\KategoriController;
use App\Http\Controllers\Camat\SettingController;
use App\Http\Controllers\DokumenBersamaController;
use App\Http\Controllers\Home\BalasanController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\KecamatanSettingController;
use App\Http\Controllers\Konten\KontenController;
use App\Http\Controllers\Login\AuthController;
use App\Http\Controllers\MadingController;
use App\Http\Controllers\Masyarakat\MasyarakatController;
use App\Http\Controllers\Masyarakat\MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\ProfilmasyarakatController;
use App\Http\Controllers\Masyarakat\SuratketeranganmiskinController;
use App\Http\Controllers\NagariController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Pegawai\PengaturanmasyarakatController;
use App\Http\Controllers\Pegawai\ProfilpegawaiController;
use App\Http\Controllers\Pegawai\UrussuratketeranganmiskinController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;


// =================== Public Routes ===================
Route::get('/', [HomeController::class, 'beranda'])->name('home');
Route::get('/visi-misi', [HomeController::class, 'visi_misi'])->name('home.visi_misi');
Route::get('/sejarah', [HomeController::class, 'sejarah'])->name('home.sejarah');
Route::get('/tugas-dan-fungsi', [HomeController::class, 'tugas_fungsi'])->name('home.tugas_fungsi');
Route::get('/geografis', [HomeController::class, 'geografis'])->name('home.geografis');
Route::get('/struktur-organisasi', [HomeController::class, 'struktur_ogranisasi'])->name('home.struktur_organisasi');
Route::get('/konten/{jenis}', [HomeController::class, 'konten'])->name('home.konten');
Route::get('/konten/{jenis_konten}/{slug}', [HomeController::class, 'potensi_detail'])->name('konten.detail');
Route::get('/categories/{slug}', [HomeController::class, 'show'])->name('category.show');
Route::get('/portal-pelayanan/{slug}', [HomeController::class, 'portal'])->name('portal.show');
Route::get('/galeri-foto', [HomeController::class, 'galeri_foto'])->name('home.galeri_foto');
Route::get('/galeri-foto/{slug}', [HomeController::class, 'galeri_foto_detail'])->name('home.galeri_foto.detail');
Route::get('/koleksi-video', [HomeController::class, 'koleksi_video'])->name('home.koleksi_video');
Route::get('/koleksi-video/{slug}', [HomeController::class, 'koleksi_video_detail'])->name('home.koleksi_video.detail');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');

// Reaksi & Komentar
Route::post('/reaksi/{id_konten}', [BalasanController::class, 'storeReaksi'])->name('reaksi.store');
Route::post('/komentar/{id_konten}', [BalasanController::class, 'storeKomentar'])->name('komentar.store');
Route::patch('/komentar/{komentar}', [BalasanController::class, 'updateKomentar'])->name('komentar.update');
Route::delete('/komentar/{komentar}', [BalasanController::class, 'deleteKomentar'])->name('komentar.destroy');
Route::post('/balasan/store/{id_konten}', [BalasanController::class, 'storebalasan'])->name('balasan.store');


// =================== Auth Routes ===================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [MasyarakatController::class, 'create'])->name('register');
Route::post('/register', [MasyarakatController::class, 'store'])->name('register.post');
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');



Route::get('/mading',               [HomeController::class,  'mading_list'])->name('home.mading');
Route::get('/mading/jenis/{jenis}', [HomeController::class,  'mading_list'])->name('home.mading.jenis');
Route::get('/mading/{slug}',        [HomeController::class,  'mading_detail'])->name('home.mading.detail');

// Interaksi (semua ke BalasanController)
Route::post('/mading/reaksi/{id_mading}',      [BalasanController::class, 'storeMadingReaksi'])->name('mading.reaksi.toggle');
Route::post('/mading/{id_mading}/komentar',    [BalasanController::class, 'storeMadingKomentar'])->name('mading.komentar.store');
Route::post('/mading/{id_mading}/balasan',     [BalasanController::class, 'storeMadingBalasan'])->name('mading.balasan.store');



// =================== Authenticated Routes (semua role) ===================
Route::middleware('auth')->group(function () {

    // Konten
    Route::get('kelola/konten/{jenis}', [KontenController::class, 'index'])->name('konten.index');
    Route::get('kelola/konten/{jenis}/create', [KontenController::class, 'create'])->name('konten.create');
    Route::post('kelola/konten/{jenis}', [KontenController::class, 'store'])->name('konten.store');
    Route::get('kelola/konten/{jenis}/{slug}', [KontenController::class, 'show'])->name('konten.show');
    Route::get('kelola/konten/{jenis}/{slug}/edit', [KontenController::class, 'edit'])->name('konten.edit');
    Route::put('kelola/konten/{jenis}/{id_konten}', [KontenController::class, 'update'])->name('konten.update');
    Route::delete('kelola/konten/{jenis}/{id_konten}', [KontenController::class, 'destroy'])->name('konten.destroy');
    Route::post('kelola/konten/{jenis}/{id_konten}/approve', [KontenController::class, 'approve'])->name('konten.approve');
    Route::post('blog/upload-image', [KontenController::class, 'uploadImage'])->name('blog.upload.image');
    Route::post('blog/delete-image', [KontenController::class, 'deleteImage'])->name('blog.delete.image');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca_semua');
    Route::get('/notifikasi/baca/{id}', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');

    // Detail pengaduan
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');

    // Dokumen Bersama
    Route::prefix('dokumen-bersama')->name('dokumen.')->group(function () {
        Route::get('/',                [DokumenBersamaController::class, 'index'])->name('index');
        Route::get('/terkirim',        [DokumenBersamaController::class, 'terkirim'])->name('terkirim');
        Route::get('/buat',            [DokumenBersamaController::class, 'create'])->name('create');
        Route::post('/',               [DokumenBersamaController::class, 'store'])->name('store');
        Route::get('/{id}',            [DokumenBersamaController::class, 'show'])->name('show');
        Route::get('/{id}/edit',       [DokumenBersamaController::class, 'edit'])->name('edit');
        Route::put('/{id}',            [DokumenBersamaController::class, 'update'])->name('update');
        Route::delete('/{id}',         [DokumenBersamaController::class, 'destroy'])->name('destroy');
        Route::get('/ajax/users',      [DokumenBersamaController::class, 'ajaxUsers'])->name('ajax.users');
        Route::get('/lampiran/{id}/download', [DokumenBersamaController::class, 'downloadLampiran'])->name('lampiran.download');
        Route::delete('/lampiran/{id}',       [DokumenBersamaController::class, 'destroyLampiran'])->name('lampiran.destroy');
    });
});


// =================== Camat Routes ===================
Route::prefix('camat')->middleware(['auth', 'role:camat'])->group(function () {

    Route::get('/website/setting', [KecamatanSettingController::class, 'edit'])->name('kecamatan.setting.edit');
    Route::put('/website/setting', [KecamatanSettingController::class, 'update'])->name('kecamatan.setting.update');
    Route::post('/website/setting/upload-image', [KecamatanSettingController::class, 'uploadImage'])->name('kecamatan.setting.upload-image');
    Route::post('/website/setting/delete-image', [KecamatanSettingController::class, 'deleteImage'])->name('kecamatan.setting.delete-image');

    Route::prefix('setting')->group(function () {
        Route::get('heroslide', [HeroslideController::class, 'index'])->name('camat.settings.heroslide');
        Route::post('heroslide', [HeroslideController::class, 'store'])->name('camat.settings.heroslide.store');
        Route::get('heroslide/{id}/edit', [HeroslideController::class, 'edit'])->name('camat.settings.heroslide.edit');
        Route::put('heroslide/{id}', [HeroslideController::class, 'update'])->name('camat.settings.heroslide.update');
        Route::delete('heroslide/{id}', [HeroslideController::class, 'destroy'])->name('camat.settings.heroslide.destroy');
        Route::get('/', [SettingController::class, 'edit'])->name('camat.settings.edit');
        Route::put('/', [SettingController::class, 'update'])->name('camat.settings.update');
        Route::get('pengantar', [SettingController::class, 'pengantar'])->name('camat.pengantar');
        Route::put('pengantar/update', [SettingController::class, 'pengatar_update'])->name('camat.pengantar.update');
    });

    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::patch('kategori/{kategori}/toggle-status', [PegawaiController::class, 'toggleStatus'])->name('camat.pegawai.toggleStatus');

    Route::resource('masyarakat', MasyarakatController::class)->names('masyarakat');

    Route::get('/nagari', [NagariController::class, 'index'])->name('camat.nagari.index');
    Route::post('/nagari/store', [NagariController::class, 'store'])->name('camat.nagari.store');
    Route::patch('/nagari/update/{id}', [NagariController::class, 'update'])->name('camat.nagari.update');

    Route::get('surat-keterangan-kurang-mampu', [CamatsuratketeranganmiskinController::class, 'camat_surat_keterangan_miskin'])->name('camat.surat_keterangan_miskin');
    Route::get('surat-keterangan-kurang-mampu/arsip', [CamatsuratketeranganmiskinController::class, 'arsip'])->name('camat.surat_keterangan_miskin_arsip');
    Route::get('/surat-keterangan-kurang-mampu/laporan-selesai', [CamatsuratketeranganmiskinController::class, 'laporan_selesai'])->name('camat.surat_keterangan_miskin_laporan_selesai');
});


// =================== Pegawai & Camat Shared Routes ===================
Route::middleware(['auth', 'role:pegawai,camat'])->group(function () {

    Route::get('camat/dashboard', [CamatController::class, 'dashboard'])->name('camat.dashboard');
    Route::get('pegawai/dashboard', [CamatController::class, 'dashboard'])->name('pegawai.dashboard');

    Route::get('/laporan', [CamatsuratketeranganmiskinController::class, 'laporan'])->name('laporan.surat_keterangan_miskin_laporan');
    Route::get('/laporan/{id}/print', [CamatsuratketeranganmiskinController::class, 'print'])->name('laporan.surat_keterangan_miskin_print');

    Route::get('/balasan-pengaduan', [BalasanpengaduanController::class, 'index'])->name('balasanpengaduan.index');
    Route::get('/balasan-pengaduan/create/{id_pengaduan}', [BalasanpengaduanController::class, 'create'])->name('balasanpengaduan.create');
    Route::post('/balasan-pengaduan/{id_pengaduan}', [BalasanpengaduanController::class, 'store'])->name('balasanpengaduan.store');
    Route::get('/balasan-pengaduan/{id}/edit', [BalasanpengaduanController::class, 'edit'])->name('balasanpengaduan.edit');
    Route::put('/balasan-pengaduan/{id}', [BalasanpengaduanController::class, 'update'])->name('balasanpengaduan.update');

    Route::get('pengaturan/masyarakat', [PengaturanmasyarakatController::class, 'index'])->name('camat.masyarakat.index');
    Route::post('pengaturan/masyarakat/store', [PengaturanmasyarakatController::class, 'store'])->name('camat.masyarakat.store');
    Route::get('pengaturan/masyarakat/show/{masyarakat}', [PengaturanmasyarakatController::class, 'show'])->name('camat.masyarakat.show');
    Route::patch('pengaturan/masyarakat/{masyarakat}/password', [PengaturanmasyarakatController::class, 'updatePassword'])->name('camat.masyarakat.updatePassword');
    Route::patch('pengaturan/masyarakat/{masyarakat}/toggle-status', [PengaturanmasyarakatController::class, 'toggleStatus'])->name('camat.masyarakat.toggleStatus');
    Route::put('/pengaturan/masyarakat/{id}', [MasyarakatController::class, 'update'])->name('camat.masyarakat.update');

    Route::get('profil', [ProfilpegawaiController::class, 'profil'])->name('pegawai.profil');
    Route::put('profil', [ProfilpegawaiController::class, 'profil_update'])->name('pegawai.profil_update');
    Route::put('password', [ProfilpegawaiController::class, 'password_update'])->name('pegawai.password_update');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    Route::get('/surat-keterangan-kurang-mampu', [UrussuratketeranganmiskinController::class, 'surat_keterangan_miskin'])->name('pegawai.surat_keterangan_miskin');
    Route::put('/surat-keterangan-kurang-mampu/{id}/verifikasi', [UrussuratketeranganmiskinController::class, 'verifikasi'])->name('pegawai.surat_keterangan_miskin_verifikasi');

    Route::get('walinagari/surat-keterangan-kurang-mampu', [CamatsuratketeranganmiskinController::class, 'surat_keterangan_miskin'])->name('nagari.surat_keterangan_miskin');
    Route::put('/walinagari/surat-keterangan-kurang-mampu/{id}/verifikasi', [CamatsuratketeranganmiskinController::class, 'verifikasi'])->name('walinagari.surat_keterangan_miskin_verifikasi');
    Route::put('/walinagari/surat-keterangan-kurang-mampu/{id}/terima', [CamatsuratketeranganmiskinController::class, 'terima'])->name('nagari.surat_keterangan_miskin_terima');
    Route::get('/walinagari/surat-keterangan-kurang-mampu/{id}/print', [CamatsuratketeranganmiskinController::class, 'print'])->name('nagari.surat_keterangan_miskin_print');
    Route::get('/walinagari/surat-keterangan-kurang-mampu/laporan', [CamatsuratketeranganmiskinController::class, 'laporan'])->name('nagari.surat_keterangan_miskin_laporan');
    Route::get('/walinagari/surat-keterangan-kurang-mampu/laporan-selesai', [CamatsuratketeranganmiskinController::class, 'laporan_selesai'])->name('nagari.surat_keterangan_miskin_laporan_selesai');
    Route::get('/walinagari/surat-keterangan-kurang-mampu/arsip', [CamatsuratketeranganmiskinController::class, 'arsip'])->name('nagari.surat_keterangan_miskin_arsip');
});


// =================== Masyarakat Routes ===================
Route::prefix('masyarakat')->middleware(['auth', 'role:masyarakat'])->group(function () {
    Route::get('dashboard', [MasyarakatDashboardController::class, 'index'])->name('dashboard.masyarakat');
    Route::get('profil', [ProfilmasyarakatController::class, 'profil'])->name('masyarakat.profil');
    Route::put('profil', [ProfilmasyarakatController::class, 'profil_update'])->name('masyarakat.profil_update');
    Route::put('password', [ProfilmasyarakatController::class, 'password_update'])->name('masyarakat.password_update');

    // Surat Keterangan Miskin (masyarakat biasa)
    Route::get('surat-keterangan-tidak-mampu', [SuratketeranganmiskinController::class, 'surat_keterangan_miskin'])->name('masyarakat.surat_keterangan_miskin');
    Route::post('surat-keterangan-tidak-mampu/store', [SuratketeranganmiskinController::class, 'surat_keterangan_miskin_store'])->name('masyarakat.surat_keterangan_miskin_store');
    Route::get('surat-keterangan-tidak-mampu/{id}/edit', [SuratketeranganmiskinController::class, 'surat_keterangan_miskin_edit'])->name('masyarakat.surat_keterangan_miskin_edit');
    Route::put('surat-keterangan-tidak-mampu/{id}/update', [SuratketeranganmiskinController::class, 'surat_keterangan_miskin_update'])->name('masyarakat.surat_keterangan_miskin_update');
    Route::delete('surat-keterangan-tidak-mampu/{id}/delete', [SuratketeranganmiskinController::class, 'surat_keterangan_miskin_destroy'])->name('masyarakat.surat_keterangan_miskin_destroy');
    Route::get('surat-keterangan-tidak-mampu/{id}/download', [SuratketeranganmiskinController::class, 'download'])->name('masyarakat.surat_keterangan_miskin_download');

    // Pengaduan (masyarakat biasa)
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'update'])->name('pengaduan.update');
    Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
});


// =================== Sekolah Routes ===================
// Prefix 'sekolah' — outer group mengizinkan admin_sekolah, siswa_sekolah, pegawai, camat.
// Inner group membatasi akses manajemen sekolah/siswa hanya untuk admin_sekolah, pegawai, camat.
Route::prefix('sekolah')->middleware(['auth', 'role:admin_sekolah,siswa_sekolah,pegawai,camat'])->group(function () {

    // ── Upload/Delete gambar Summernote (admin + siswa bisa pakai) ─────────
    Route::post('/upload-image', [MadingController::class, 'uploadImage'])->name('mading.upload.image');
    Route::post('/delete-image', [MadingController::class, 'deleteImage'])->name('mading.delete.image');

    // ── Mading (admin_sekolah + siswa_sekolah; controller cek akses internal) ──
    Route::get('/mading',                           [MadingController::class, 'index'])->name('mading.index');
    Route::get('/mading/tambah',                    [MadingController::class, 'create'])->name('mading.create');
    Route::post('/mading/tambah',                   [MadingController::class, 'store'])->name('mading.store');
    Route::delete('/mading/lampiran/{id}',          [MadingController::class, 'destroyLampiran'])->name('mading.lampiran.destroy');
    Route::get('/mading/{id_mading}/edit',          [MadingController::class, 'edit'])->name('mading.edit');
    Route::put('/mading/{id_mading}/edit',          [MadingController::class, 'update'])->name('mading.update');
    Route::delete('/mading/{id_mading}',            [MadingController::class, 'destroy'])->name('mading.destroy');
    Route::post('/mading/{id_mading}/approve',      [MadingController::class, 'approve'])->name('mading.approve');
    Route::post('/mading/{id_mading}/reject',       [MadingController::class, 'reject'])->name('mading.reject');
    Route::post('/mading/{id_mading}/toggle',       [MadingController::class, 'toggle'])->name('mading.toggle');
    // show → redirect ke halaman publik berdasarkan slug
    Route::get('/mading/{id_mading}',               [MadingController::class, 'show'])->name('mading.show');

    // ── Manajemen Sekolah & Siswa (admin_sekolah, pegawai, camat — bukan siswa_sekolah) ──
    Route::middleware(['role:admin_sekolah,pegawai,camat'])->group(function () {

        Route::get('dashboard', [MasyarakatController::class, 'dashboard'])->name('sekolah.dashboard');
        Route::get('/beranda',  [SekolahController::class, 'index'])->name('sekolah.index');

        // Pegawai (semua label) & camat bisa tambah/hapus sekolah
        Route::middleware(['role:pegawai,camat,staf_nagari,wali_nagari,staf_camat'])->group(function () {
            Route::get('/create',                    [SekolahController::class, 'create'])->name('sekolah.create');
            Route::post('/',                         [SekolahController::class, 'store'])->name('sekolah.store');
            Route::delete('/{sekolah}',              [SekolahController::class, 'destroy'])->name('sekolah.destroy');
            Route::patch('/{sekolah}/toggle-status', [SekolahController::class, 'toggleStatus'])->name('sekolah.toggle-status');
            Route::get('/ajax/user-by-nagari',       [SekolahController::class, 'getUserByNagari'])->name('sekolah.ajax.user-by-nagari');
        });

        // Siswa management
        Route::get('/siswa',                              [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create',                       [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa',                             [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}',                      [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit',                 [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}',                      [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}',                   [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::get('/siswa-ajax/sekolah-by-nagari',       [SiswaController::class, 'ajaxSekolahByNagari'])->name('siswa.ajax.sekolah-by-nagari');
        Route::get('/siswa-ajax/masyarakat',              [SiswaController::class, 'ajaxMasyarakat'])->name('siswa.ajax.masyarakat');
        Route::patch('/siswa/{siswa}/toggle-verifikasi',  [SiswaController::class, 'toggleVerifikasi'])->name('siswa.toggle-verifikasi');

        // ── Wildcard /{sekolah} — SELALU PALING BAWAH ────────────────────
        Route::get('/{sekolah}',      [SekolahController::class, 'show'])->name('sekolah.show');
        Route::get('/{sekolah}/edit', [SekolahController::class, 'edit'])->name('sekolah.edit');
        Route::put('/{sekolah}',      [SekolahController::class, 'update'])->name('sekolah.update');
    });
});


// =================== Pegawai Routes ===================
Route::prefix('pegawai')->middleware(['auth', 'role:pegawai'])->group(function () {
    // bagian pegawai
});
