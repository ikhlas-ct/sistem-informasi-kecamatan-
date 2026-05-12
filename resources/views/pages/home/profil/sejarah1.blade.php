@extends('layouts.home.app')
@section('title', 'Sejarah')
@section('content')
<main class="main">
  <!-- Page Title -->
  <div class="page-title dark-background mb-lg-5">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Sejarah</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Sejarah</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title text-center">Sejarah</h3>
            <div class="content">
              {!! $currentPageContent !!}
            </div>
          </div>
        </div>

        <!-- Blog Pagination Section -->
        @if($totalPages > 1)
          <section id="blog-pagination" class="blog-pagination section mt-4">
            <div class="container">
              <ul class="pagination justify-content-center">
                @for ($i = 1; $i <= $totalPages; $i++)
                  <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="{{ route('home.sejarah', ['page' => $i]) }}">{{ $i }}</a>
                  </li>
                @endfor
              </ul>
            </div>
          </section>
        @endif
      </div>

      <div class="col-lg-4 sidebar">
        @include('partials.konten.sidebarleft')
      </div>
    </div>
  </div>
</main>
@endsection
