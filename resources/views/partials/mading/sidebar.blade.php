{{--
    Sidebar khusus halaman Mading
    Simpan di: resources/views/partials/mading/sidebar.blade.php

    Variabel yang harus dikirim dari controller:
    - $jenisOptions  : Collection jenis mading unik
    - $sekolahs      : Collection sekolah yang punya mading publik (+ madingPublik_count)
    - $recentMadings : Collection 5 mading terbaru
--}}

<div class="sidebar-widget search-widget mb-4">
    <h3 class="sidebar-title">Cari Mading</h3>
    <form action="{{ route('home.mading') }}" method="GET">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari mading..."
                   value="{{ request('q') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div>

<!-- Filter Jenis -->
@if ($jenisOptions->count())
<div class="sidebar-widget categories-widget mb-4">
    <h3 class="sidebar-title">Jenis Mading</h3>
    <ul class="mt-3 list-unstyled">
        <li>
            <a href="{{ route('home.mading') }}"
               class="{{ !isset($jenis) || !$jenis ? 'fw-bold text-primary' : '' }}">
                <i class="bi bi-journal-text me-1"></i>Semua Mading
            </a>
        </li>
        @foreach ($jenisOptions as $j)
        <li>
            <a href="{{ route('home.mading.jenis', $j) }}"
               class="{{ (isset($jenis) && $jenis === $j) ? 'fw-bold text-primary' : '' }}">
                <i class="bi bi-tag me-1"></i>
                {{ ucfirst(str_replace('_', ' ', $j)) }}
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif

<!-- Mading Terbaru -->
@if ($recentMadings->count())
<div class="sidebar-widget recent-posts-widget mb-4">
    <h3 class="sidebar-title">Mading Terbaru</h3>
    @foreach ($recentMadings as $recent)
    <div class="post-item d-flex align-items-start gap-2 mb-3">
        <!-- Thumbnail -->
        @if ($recent->gambar)
        <img src="{{ asset('storage/' . $recent->gambar) }}"
             alt="{{ $recent->judul }}"
             style="width:72px; height:56px; object-fit:cover; border-radius:6px; flex-shrink:0;">
        @else
        <img src="{{ asset('default-image/default-mading.png') }}"
             alt="Default"
             style="width:72px; height:56px; object-fit:cover; border-radius:6px; flex-shrink:0;">
        @endif

        <div>
            <h6 class="mb-0" style="font-size:13.5px; line-height:1.3;">
                <a href="{{ route('home.mading.detail', $recent->slug) }}"
                   class="text-decoration-none text-dark">
                    {{ Str::limit($recent->judul, 50) }}
                </a>
            </h6>
            <small class="text-muted">
                {{ $recent->tanggal_publikasi?->format('d M Y') ?? '-' }}
            </small>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Daftar Sekolah -->
@if ($sekolahs->count())
<div class="sidebar-widget mb-4">
    <h3 class="sidebar-title">Sekolah</h3>
    <ul class="mt-3 list-unstyled">
        @foreach ($sekolahs as $sekolah)
        <li class="d-flex justify-content-between align-items-center mb-1">
            <a href="{{ route('home.mading') }}?sekolah={{ $sekolah->id_sekolah }}"
               class="text-decoration-none text-dark small">
                <i class="bi bi-building me-1 text-secondary"></i>
                {{ $sekolah->nama_sekolah }}
                @if ($sekolah->jenjang)
                    <span class="text-muted">({{ strtoupper($sekolah->jenjang) }})</span>
                @endif
            </a>
            <span class="badge bg-light text-secondary border small">
                {{ $sekolah->mading_publik_count }}
            </span>
        </li>
        @endforeach
    </ul>
</div>
@endif
