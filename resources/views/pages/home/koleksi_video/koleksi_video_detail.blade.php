@extends('layouts.home.app')
@section('title', 'Detail Video')
@section('content')

    <!-- Page Title -->
    <div class="page-title dark-background mb-lg-5">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Detail Video</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Detail Video</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="row">
            <!-- Konten Detail Video -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center">{{ $post->judul }}</h3>

                        <!-- Video Utama -->
                        @if(!empty($videos))
                            <div class="embed-responsive embed-responsive-16by9 mb-3" style="height: 250px;">
                                <iframe class="embed-responsive-item" src="{{ $videos[0] }}" allowfullscreen style="height: 100%;"></iframe>
                            </div>
                        @else
                            <p class="text-center">Tidak ada video tersedia.</p>
                        @endif

                        <!-- Galeri Video -->
                        @if(count($videos) > 1)
                            <div class="row">
                                @foreach(array_slice($videos, 1) as $video)
                                    <div class="col-md-6 mb-3">
                                        <div class="embed-responsive embed-responsive-16by9" style="height: 300px;">
                                            <iframe class="embed-responsive-item" src="{{ $video }}" allowfullscreen style="height: 100%;"></iframe>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
