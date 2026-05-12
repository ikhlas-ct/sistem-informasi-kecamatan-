@extends('layouts.home.app')
@section('title', 'Blog')
@section('content')

@section('styles')

<style>
.article .content img {
    max-width: 100%;
    height: auto;
}
</style>
@endsection



  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Blog {{ $jenis_konten }}</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Blog {{ $jenis_konten }}</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-8">

     <!-- Blog Posts Section -->
     <section id="blog-posts" class="blog-posts section">
        <div class="container">
          <div class="row gy-4">
            @foreach ($posts as $post)
            <div class="col-12">
              <article>
                <div class="post-img mt-3">
                    <img src="{{ asset('storage/' . $post->gambar) }}" alt="" class="img-fluid" style="width: 100%; max-height: 250px; object-fit: contain;">
                </div>
                <h2 class="title">
                    <a href="{{ route('konten.detail', [$post->jenis_konten, $post->slug]) }}">{{ $post->judul }}</a>
                </h2>
                <div class="meta-top">
                    <ul class="d-flex align-items-center gap-1">
                      <li class="d-flex align-items-center">
                        <i class="bi bi-person me-1"></i>
                        <a href="{{ route('konten.detail', [$post->jenis_konten, $post->slug]) }}">
                            {{ $post->user->pegawai->nama_pegawai
                                ?? $post->user->masyarakat->nama_masyarakat
                                ?? 'Anonim' }}
                        </a>

                      </li>
                      <li class="d-flex align-items-center">
                        <i class="bi bi-clock me-1"></i>
                        <time datetime="{{ $post->tanggal_publikasi }}">
                          {{ $post->tanggal_publikasi->format('d M Y') }}
                        </time>
                      </li>
                      <li class="d-flex align-items-center">
                        <i class="bi bi-chat-dots me-1"></i>
                        <a href="{{ route('konten.detail', [$post->jenis_konten, $post->slug]) }}">
                            {{ $post->komentar_count }} KOMENTAR
                        </a>
                      </li>
                    </ul>
                  </div>


                <div class="content">
                  {!! Str::limit(strip_tags($post->isi), 200) !!}
                </div>

                <div class="read-more">
                    <a href="{{ route('konten.detail', [$post->jenis_konten, $post->slug]) }}">
                        Read More</a>
                </div>
              </article>
            </div>
            @endforeach
          </div><!-- End blog posts list -->
        </div>
      </section><!-- /Blog Posts Section -->


         <!-- Blog Pagination Section -->
          <section id="blog-pagination" class="blog-pagination section">
            <div class="container">
                <ul class="pagination justify-content-center">
                {{ $posts->links('pagination::custom') }}
            </ul>
            </div>
          </section>



        </div>


        <div class="col-lg-4 sidebar">

            @include('partials.konten.sidebarleft')

           </div>

      </div>
    </div>

  </main>








@endsection
