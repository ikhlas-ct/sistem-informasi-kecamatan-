@extends('layouts.home.app')
@section('title', 'Koleksi Video')
@section('content')

    <!-- Page Title -->
    <div class="page-title dark-background mb-lg-5">
        <div class="container d-lg-flex justify-content-between align-items-center ">
            <h1 class="mb-2 mb-lg-0">Koleksi Video</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Koleksi Video</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="row">
            <!-- Konten Koleksi Video -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Koleksi Video</h3>

                        @if($posts->count() > 0)
                            <div class="row">
                                @foreach($posts as $post)
                                    @if(!empty($post->isi))
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-0 shadow-sm position-relative">
                                            <!-- Tampilkan gambar sebagai thumbnail -->
                                            <img src="{{ $post->gambar ? asset('storage/' . $post->gambar) : asset('storage/public/defaultimage/no_image_available.jpg') }}"
                                                 alt="Gambar Video" class="img-fluid w-100" style="height: 200px; object-fit: cover;">

                                            <!-- Overlay untuk Judul -->
                                            <div class="overlay position-absolute bottom-0 start-0 w-100 p-2 text-white"
                                                 style="background: rgba(0, 0, 0, 0.6);">
                                                <a href="{{ route('home.koleksi_video.detail', ['slug' => $post->slug]) }}" class="text-white">
                                                    <p class="m-0">{{ $post->judul }}</p>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Kotak jumlah video -->
                                        <div class="mt-2 d-flex align-items-center"
                                             style="background: rgba(255,255,255,0.7); color: black; border-radius: 5px;">
                                            <i class="bi bi-camera-video me-1"></i>
                                            <span>{{ $post->jumlah_video ?? 1 }} Video</span>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <section id="blog-pagination" class="blog-pagination section">
                                <div class="container">
                                    {{ $posts->links('pagination::custom') }}
                                </div>
                              </section>

                        @else
                            <p class="text-center">Belum ada video yang tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 sidebar">
                @include('partials.konten.sidebarleft')
            </div>

        </div>
    </div>

@endsection
