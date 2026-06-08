@extends('layouts.home.app')
@section('title', 'Home')
@section('content')

 <!-- Hero Section -->
<!-- filepath: c:\storage\resources\views\pages\home\beranda.blade.php -->
<section id="hero" class="hero section dark-background">
    <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
      @foreach($heros as $hero)
        <div class="carousel-item @if($loop->first) active @endif">
          <img src="{{ asset('storage/' . $hero->image) }}" alt="" class="d-block w-100">
          @if($hero->title || $hero->description || ($hero->button_text && $hero->button_link))
          <div class="container">
              @if($hero->title)
                  <h2>{{ $hero->title }}</h2>
              @endif
              @if($hero->description)
                  <p>{{ $hero->description }}</p>
              @endif
              @if($hero->button_text && $hero->button_link)
                  <a href="{{ $hero->button_link }}" class="btn-get-started">{{ $hero->button_text }}</a>
              @endif
          </div>
      @endif
        </div><!-- End Carousel Item -->
      @endforeach

      <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
      </a>

      <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
      </a>

      <ol class="carousel-indicators"></ol>
    </div>
  </section><!-- /Hero Section -->
  <!-- /Hero Section -->

<!-- Call To Action Section -->
<section id="call-to-action" class="call-to-action section" style="background-color: rgb(248, 70, 34);">
    <div class="container">
      <div class="row align-items-center" data-aos="zoom-in" data-aos-delay="100">

        <!-- Kolom Gambar -->
        <div class="col-xl-3 text-center">
          <img src="{{ asset('home/pengaduan/transparasi.jpg') }}" alt="Transparansi" class="img-fluid">
        </div>

        <!-- Kolom Teks -->
        <div class="col-xl-6 text-center text-xl-start text-white">
          <h3>Layanan Pengaduan</h3>
          <p>
            Sampaikan pengaduan Anda secara langsung dan bantu kami meningkatkan layanan publik di Kecamatan IV Koto. Setiap aspirasi Anda sangat berarti untuk pemerintahan yang lebih transparan dan responsif.
          </p>
        </div>

        <!-- Kolom Tombol -->
        <div class="col-xl-3 text-center">
          <a class="cta-btn align-middle" href="{{ route('pengaduan.index') }}">Ajukan Pengaduan</a>
        </div>

      </div>
    </div>
    </section>

    <section id="kata-pengantar" class="kata-pengantar section">
        <!-- Kata Sambutan Camat Start -->
        <div class="container-fluid" data-aos="fade-up" data-aos-delay="200">

            <div class="container">
                <div class="row g-5 align-items-center">
                    <!-- Kolom Gambar -->
                    <div class="col-lg-5 col-md-6 wow fadeIn" data-wow-delay=".3s" data-aos="fade-right" data-aos-delay="300">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . ($settings->gambar_pengantar ?? 'home/img/portfolio/app-2.jpg')) }}" class="img-fluid rounded w-100" style="max-width: 100%; height: 400px; object-fit: cover;" alt="Foto Camat">
                        </div>
                    </div>
                    <!-- Kolom Kata Sambutan -->
                    <div class="col-lg-7 col-md-6 wow fadeIn" data-wow-delay=".5s" data-aos="fade-left" data-aos-delay="500">
                        <p>
                            {!! $settings->paragraf_pengantar ?? 'paragraf_pengantar' !!}
                        </p>
                        <p class="fw-bold">Hormat Kami,</p>
                        <p class="fw-bold mb-0">{{ $settings->camat->nama_pegawai ?? '[Nama Camat]' }}</p>
                        <p class="text-secondary">{{ $settings->camat->jabatan ?? 'Camat Kecamatan ' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Berita Terbaru Section -->
<section id="berita-terbaru" class="section" data-aos="fade-up" data-aos-delay="200">
    <div class="container">
      <!-- Bagian Header -->
      <div class="row mb-3 justify-content-between align-items-center" data-aos="fade-down" data-aos-delay="300">
        <div class="col-auto">
          <h2 class="fw-bold">Berita Terbaru</h2>
          <p class="text-muted"><em>Baca informasi terbaru dan terkini</em></p>
        </div>
        <div class="col-auto">
          <a href="{{ route('home.konten',['jenis' => 'berita']) }}" class="fw-bold text-decoration-none">Lihat Semua</a>
        </div>
      </div>

      <!-- Grid Card Berita -->
      <div class="row gy-4" data-aos="fade-up" data-aos-delay="400">
        @foreach ($berita as $item)
        <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="500">
          <div class="card border-0 shadow-sm h-100">
            <!-- Gambar Berita -->
            <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}"
                 style="height: 180px; object-fit: fill;">
            <div class="card-body d-flex flex-column">
              <!-- Tanggal, Penulis & Komentar -->
              <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 12px;">
                <span>{{ $item->user->pegawai->nama_pegawai ?? $item->user->masyarakat->nama_masyarakat ?? 'Nama Tidak Ditemukan' }}</span>
                <span>{{ $item->tanggal_publikasi->format('d M Y') }}</span>
                <span>{{ $item->komentar_count ?? 0 }} KOMENTAR</span>
              </div>
              <!-- Judul Berita -->
              <a href="{{ route('konten.detail', [$item->jenis_konten, $item->slug]) }}" class="text-decoration-none" style="color: #000;">
                <h5 class="card-title fw-bold">{{ $item->judul }}</h5>
              </a>

              <!-- Ringkasan Singkat -->
              <p class="card-text">
                {{ Str::limit(html_entity_decode(strip_tags($item->isi)), 50) }}
            </p>


              <!-- Kategori -->
              <p class="card-text">
                <small>{{ optional($item->kategori->first())->nama_kategori }}</small>
            </p>

              <!-- Tombol Baca Selengkapnya -->
              <div class="mt-auto text-end">
                <a href="{{ route('konten.detail', [$item->jenis_konten, $item->slug]) }}" class="btn btn-sm btn-primary w-100">Baca Selengkapnya</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div><!-- End row gy-4 -->
    </div><!-- End container -->
</section>



<!-- Mading Sekolah Section -->
<section id="mading-sekolah" class="section py-5" data-aos="fade-up" data-aos-delay="200">
    <div class="container">
        <!-- Header -->
        <div class="row mb-3 justify-content-between align-items-center" data-aos="fade-down" data-aos-delay="300">
            <div class="col-auto">
                <h2 class="fw-bold">Mading Sekolah</h2>
                <p class="text-muted"><em>Karya dan informasi terkini dari sekolah-sekolah di wilayah kami</em></p>
            </div>
            <div class="col-auto">
                <a href="{{ route('home.mading') }}" class="fw-bold text-decoration-none">Lihat Semua</a>
            </div>
        </div>

        @if($mading->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-newspaper" style="font-size:2.5rem;"></i>
                <p class="mt-2">Belum ada mading yang dipublikasikan.</p>
            </div>
        @else
        <!-- Grid Card Mading -->
        <div class="row gy-4" data-aos="fade-up" data-aos-delay="400">
            @foreach ($mading as $item)
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="500">
                <div class="card border-0 shadow-sm h-100">
                    <!-- Gambar Mading -->
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}"
                             class="card-img-top" alt="{{ $item->judul }}"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light"
                             style="height:180px;">
                            <i class="bi bi-newspaper text-secondary" style="font-size:2.5rem;"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <!-- Badge Jenis -->
                        @if($item->jenis)
                            <span class="badge bg-primary mb-2" style="width:fit-content;font-size:.7rem;">
                                {{ ucfirst(str_replace('_', ' ', $item->jenis)) }}
                            </span>
                        @endif

                        <!-- Meta: Sekolah, Tanggal, Komentar -->
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size:12px;">
                            <span class="text-truncate" style="max-width:110px;">
                                {{ $item->sekolah->nama_sekolah ?? 'Sekolah' }}
                            </span>
                            <span>{{ $item->tanggal_publikasi?->format('d M Y') }}</span>
                            <span>{{ $item->komentar_count ?? 0 }} Komentar</span>
                        </div>

                        <!-- Judul -->
                        <a href="{{ route('home.mading.detail', $item->slug) }}"
                           class="text-decoration-none" style="color:#000;">
                            <h5 class="card-title fw-bold" style="font-size:.95rem;">{{ $item->judul }}</h5>
                        </a>

                        <!-- Ringkasan -->
                        <p class="card-text" style="font-size:.85rem;">
                            {{ Str::limit(html_entity_decode(strip_tags($item->isi)), 60) }}
                        </p>

                        <!-- Tombol -->
                        <div class="mt-auto text-end">
                            <a href="{{ route('home.mading.detail', $item->slug) }}"
                               class="btn btn-sm btn-outline-primary w-100">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>



<section id="gallery" class="gallery section bg-light py-5">
    <div class="container" data-aos="fade-up">
        <div class="section-header text-center mb-5">
            <h2>Galeri Seni & Budaya</h2>
            <p>Jelajahi momen dan karya seni yang memukau dari daerah kami</p>
        </div>
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs justify-content-center" id="gallery-tabs" role="tablist">
            @foreach ($jenis_konten as $index => $jenis)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($loop->first) active @endif" id="tab-{{ $jenis }}"
                        data-bs-toggle="tab" data-bs-target="#content-{{ $jenis }}" type="button" role="tab"
                        aria-controls="content-{{ $jenis }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ ucfirst(str_replace('_', ' ', $jenis)) }}
                    </button>
                </li>
            @endforeach
        </ul>
        <!-- Tabs Content -->
        <div class="tab-content mt-4" id="gallery-tabs-content">
            @foreach ($jenis_konten as $jenis)
                <div class="tab-pane fade @if ($loop->first) show active @endif" id="content-{{ $jenis }}" role="tabpanel"
                    aria-labelledby="tab-{{ $jenis }}">
                    <!-- Link "Lihat Semua" untuk jenis konten ini -->
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('home.konten', $jenis) }}" class="fw-bold text-decoration-none">
                            Lihat Semua {{ ucfirst(str_replace('_', ' ', $jenis)) }}
                        </a>
                    </div>
                    <div class="row gy-4">
                        @foreach ($galeri->where('jenis_konten', $jenis) as $item)
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-item">
                                    <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}" class="img-fluid" alt="{{ $item->judul }}">
                                    <div class="gallery-links d-flex align-items-center justify-content-center">
                                        <a href="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}" class="glightbox preview-link"
                                            title="{{ $item->judul }}" data-gallery="{{ $jenis }}">
                                            <i class="bi bi-arrows-angle-expand"></i>
                                        </a>
                                        <a href="{{ route('konten.detail', ['jenis_konten' => $jenis, 'slug' => $item->slug]) }}" title="Detail {{ $item->judul }}" class="details-link">
                                            <i class="bi bi-link-45deg"></i>
                                        </a>
                                    </div>
                                    <div class="mt-2 text-center">
                                        <h6>{{ $item->judul }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>




@endsection
