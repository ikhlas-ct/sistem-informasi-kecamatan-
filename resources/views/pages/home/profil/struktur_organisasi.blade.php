@extends('layouts.home.app')
@section('title', 'Struktur Organisasi')
@section('content')
  <main class="main">
    <!-- Page Title -->
    <div class="page-title dark-background mb-lg-5">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Struktur Organisasi</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">Struktur Organisasi</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center">Struktur Organisasi</h3>
                    @if(isset($settings->gambar_struktur))
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $settings->gambar_struktur) }}" alt="Gambar Struktur" class="img-fluid d-block mx-auto">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 sidebar">

            @include('partials.konten.sidebarleft')

           </div>

      </div>
    </div>

  </main>
@endsection
