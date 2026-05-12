@extends('layouts.home.app')
@section('title', 'Galeri Detail Foto')
@section('content')

    <!-- Page Title -->
    <div class="page-title dark-background mb-lg-5">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Galeri Detail Foto</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Galeri Detail Foto</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="row">
            <!-- Konten Galeri Foto -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-3">{{ $post->judul }}</h3>

                        <!-- Gambar Utama dengan Carousel -->
                        <div id="mainImageCarousel" class="carousel slide position-relative mb-3">
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-index="0">
                                    <img id="mainImage" src="{{ asset('storage/' . $post->gambar) }}" class="d-block w-100 img-fluid"
                                         style="height: 250px; object-fit: contain; border-radius: 8px;" alt="Gambar Utama">
                                </div>
                                @foreach($galeri as $index => $foto)
                                    <div class="carousel-item" data-index="{{ $index + 1 }}">
                                        <img src="{{ $foto }}" class="d-block w-100 img-fluid"
                                             style="height: 250px; object-fit: contain; border-radius: 8px;" alt="Foto Galeri">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mainImageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mainImageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <!-- Galeri Thumbnail -->
                        <div class="row" id="thumbnailContainer">
                            <div class="col-3">
                                <a href="javascript:void(0);" onclick="changeMainImage('{{ asset('storage/' . $post->gambar) }}', 0)">
                                    <img src="{{ asset('storage/' . $post->gambar) }}"
                                         alt="Gambar Utama" class="img-thumbnail"
                                         style="height: 100px; object-fit: contain;">
                                </a>
                            </div>
                            @foreach($galeri as $index => $foto)
                                <div class="col-3">
                                    <a href="javascript:void(0);" onclick="changeMainImage('{{ $foto }}', {{ $index + 1 }})">
                                        <img src="{{ $foto }}"
                                             alt="Foto Galeri" class="img-thumbnail"
                                             style="height: 100px; object-fit: contain;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
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

@section('script')
<script>
    function changeMainImage(src, index) {
        var mainImage = document.getElementById('mainImage');
        var thumbnailContainer = document.getElementById('thumbnailContainer');

        // Check if the thumbnail already exists
        var existingThumbnail = Array.from(thumbnailContainer.children).find(function(thumbnail) {
            return thumbnail.querySelector('img').src === mainImage.src;
        });

        // If the thumbnail does not exist, create a new one
        if (!existingThumbnail) {
            var newThumbnail = document.createElement('div');
            newThumbnail.className = 'col-3';
            newThumbnail.innerHTML = '<a href="javascript:void(0);" onclick="changeMainImage(\'' + mainImage.src + '\', ' + index + ')">' +
                                     '<img src="' + mainImage.src + '" alt="Foto Galeri" class="img-thumbnail" style="height: 100px; object-fit: contain;">' +
                                     '</a>';
            thumbnailContainer.appendChild(newThumbnail);
        }

        // Change the main image source
        mainImage.src = src;

        // Update the carousel to show the new main image
        var carouselItems = document.querySelectorAll('#mainImageCarousel .carousel-item');
        carouselItems.forEach(function(item) {
            item.classList.remove('active');
            if (item.getAttribute('data-index') == index) {
                item.classList.add('active');
            }
        });
    }
</script>
@endsection
