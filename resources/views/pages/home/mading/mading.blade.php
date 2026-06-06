@extends('layouts.home.app')
@section('title', 'Mading Digital - ' . $jenis_label)
@section('content')

@section('styles')
<style>
.mading-card article {
    border-bottom: 1px solid #eee;
    padding-bottom: 1.5rem;
    margin-bottom: 1rem;
}
.mading-card article .post-img img {
    border-radius: 8px;
    object-fit: cover;
}
.mading-card article .title a {
    color: #1a1a2e;
    text-decoration: none;
    transition: color 0.2s;
}
.mading-card article .title a:hover {
    color: #fd550d;
}
.mading-card .meta-top ul {
    list-style: none;
    padding: 0;
    margin: 0 0 8px 0;
    flex-wrap: wrap;
    gap: 8px;
    display: flex;
}
.mading-card .meta-top ul li {
    font-size: 13px;
    color: #6c757d;
}
.mading-card .content {
    color: #444;
    font-size: 14.5px;
    margin: 8px 0 12px;
}
.mading-card .read-more a {
    font-size: 13px;
    color: #fd550d;
    font-weight: 600;
    text-decoration: none;
}
.mading-card .read-more a:hover {
    text-decoration: underline;
}
</style>
@endsection

<main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Mading Digital</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">{{ $jenis_label }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="container mt-4">
        <div class="row">

            <!-- ── Konten Utama ── -->
            <div class="col-lg-8">

                <section id="mading-posts" class="blog-posts section mading-card">
                    <div class="container">
                        <div class="row gy-4">

                            @forelse ($madings as $item)
                            <div class="col-12">
                                <article>

                                    <!-- Gambar -->
                                    <div class="post-img mt-3">
                                        @if ($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                                 alt="{{ $item->judul }}"
                                                 class="img-fluid w-100"
                                                 style="max-height: 260px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('default-image/default-mading.png') }}"
                                                 alt="Default Mading"
                                                 class="img-fluid w-100"
                                                 style="max-height: 260px; object-fit: cover;">
                                        @endif
                                    </div>

                                    <!-- Badge Jenis & Sekolah -->
                                    <div class="mt-2 mb-1">
                                        <span class="badge bg-primary me-1">
                                            {{ ucfirst(str_replace('_', ' ', $item->jenis)) }}
                                        </span>
                                        @if ($item->sekolah)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-building me-1"></i>{{ $item->sekolah->nama_sekolah }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Judul -->
                                    <h2 class="title">
                                        <a href="{{ route('home.mading.detail', $item->slug) }}">
                                            {{ $item->judul }}
                                        </a>
                                    </h2>

                                    <!-- Meta -->
                                    <div class="meta-top">
                                        <ul>
                                            <li class="d-flex align-items-center">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $item->user->masyarakat->nama_masyarakat
                                                   ?? $item->user->pegawai->nama_pegawai
                                                   ?? 'Anonim' }}
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="bi bi-clock me-1"></i>
                                                <time datetime="{{ $item->tanggal_publikasi }}">
                                                    {{ $item->tanggal_publikasi?->format('d M Y') ?? '-' }}
                                                </time>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="bi bi-chat-dots me-1"></i>
                                                <a href="{{ route('home.mading.detail', $item->slug) }}">
                                                    {{ $item->komentar_count }} Komentar
                                                </a>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="bi bi-eye me-1"></i>
                                                {{ $item->views ?? 0 }} Dilihat
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Ringkasan Isi -->
                                    <div class="content">
                                        {{ Str::limit(html_entity_decode(strip_tags($item->isi)), 200) }}
                                    </div>

                                    <!-- Tombol Baca -->
                                    <div class="read-more">
                                        <a href="{{ route('home.mading.detail', $item->slug) }}">
                                            Baca Selengkapnya
                                        </a>
                                    </div>

                                </article>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center py-5">
                                    <i class="bi bi-newspaper fs-1 d-block mb-2 text-muted"></i>
                                    Belum ada mading yang dipublikasikan.
                                </div>
                            </div>
                            @endforelse

                        </div><!-- End .row -->
                    </div>
                </section>

                <!-- Pagination -->
                <section id="blog-pagination" class="blog-pagination section">
                    <div class="container">
                        <ul class="pagination justify-content-center">
                            {{ $madings->links('pagination::custom') }}
                        </ul>
                    </div>
                </section>

            </div><!-- End col-lg-8 -->

            <!-- ── Sidebar ── -->
            <div class="col-lg-4 sidebar">
                @include('partials.mading.sidebar')
            </div>

        </div>
    </div>

</main>

@endsection
