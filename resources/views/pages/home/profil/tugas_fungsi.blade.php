@extends('layouts.home.app')
@section('title', 'Tugas Pokok Dan Fungsi')
@section('content')
  <main class="main">
    <!-- Page Title -->
    <div class="page-title dark-background mb-lg-5">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Tugas Pokok Dan Fungsi</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">Tugas Pokok Dan Fungsi</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center">Tugas Pokok Dan Fungsi</h3>
                    <div class="visi-misi-content">
                        {!! $settings->tugas_pokok ?? 'Tugas Pokok Dan Fungsi belum tersedia.' !!}
                    </div>
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
