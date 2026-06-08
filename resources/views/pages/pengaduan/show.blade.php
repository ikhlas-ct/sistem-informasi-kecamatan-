@extends('layouts.user.user')

@section('title', 'Detail Pengaduan')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body,
        .card,
        h4,
        h5,
        label,
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --accent: #1a73e8;
            --accent-light: #e8f0fe;
            --accent-shadow: rgba(26, 115, 232, .15);
        }

        .container {
            padding-left: 28px;
            padding-right: 24px;
        }

        .ph-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .05);
        }

        .ph-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 14px 0 0 14px;
            background: var(--accent);
        }

        .ph-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ph-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            background: var(--accent-light);
            color: var(--accent);
        }

        .ph-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .ph-breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            padding: 0;
            margin: 4px 0 0;
        }

        .ph-breadcrumb li {
            display: flex;
            align-items: center;
        }

        .ph-breadcrumb li+li::before {
            content: '›';
            color: #cbd5e1;
            font-size: .7rem;
            margin: 0 4px;
        }

        .ph-breadcrumb a {
            font-size: .75rem;
            color: var(--accent);
            text-decoration: none;
        }

        .ph-breadcrumb .bc-active {
            font-size: .75rem;
            color: #94a3b8;
        }

        .section-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .06);
            margin-bottom: 1.25rem;
        }

        .section-card .card-body {
            padding: 22px 24px;
        }

        .section-divider {
            border-left: 4px solid var(--accent);
            background: #f8f9fa;
            padding: 7px 13px;
            border-radius: 0 6px 6px 0;
            font-weight: 700;
            font-size: .82rem;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.1rem;
        }

        .meta-row {
            display: flex;
            gap: 6px;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: .83rem;
        }

        .meta-label {
            min-width: 120px;
            color: #94a3b8;
            font-weight: 600;
            flex-shrink: 0;
        }

        .meta-value {
            color: #1e293b;
        }

        /* Status badges */
        .badge-status {
            font-size: .75rem;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 700;
        }

        .badge-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-diproses {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Foto grid */
        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 8px;
        }

        .foto-thumb {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            border: 1.5px solid #e2e8f0;
        }

        .foto-thumb:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        /* Lampiran file (non-gambar) */
        .lampiran-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 8px;
            background: #fafbfc;
            transition: border-color .15s;
        }

        .lampiran-item:hover {
            border-color: var(--accent);
        }

        .lamp-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .lamp-icon.gambar {
            background: #fce7f3;
            color: #ec4899;
        }

        .lamp-icon.file {
            background: #dbeafe;
            color: #1a73e8;
        }

        .lamp-name {
            font-size: .83rem;
            font-weight: 600;
            color: #1e293b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }

        .btn-open {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e0f2fe;
            color: #0369a1;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-open:hover {
            background: #0369a1;
            color: #fff;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dcfce7;
            color: #166534;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-download:hover {
            background: #166534;
            color: #fff;
        }

        /* Balasan section */
        .balasan-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            padding: 18px 20px;
        }

        .balasan-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .balasan-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .balasan-meta-name {
            font-size: .83rem;
            font-weight: 700;
            color: #1e293b;
        }

        .balasan-meta-date {
            font-size: .72rem;
            color: #64748b;
        }

        .balasan-body {
            font-size: .85rem;
            color: #374151;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Lampiran balasan */
        .balasan-lamp-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border: 1.5px solid #bbf7d0;
            border-radius: 9px;
            margin-top: 6px;
            background: #fff;
        }

        /* Peta */
        #map {
            height: 220px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
        }

        /* Lightbox */
        #lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .88);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
        }

        #lightbox img {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .5);
        }

        #lb-close {
            position: absolute;
            top: 16px;
            right: 20px;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.6rem;
            cursor: pointer;
            line-height: 1;
        }

        #lb-download {
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .3);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: .8rem;
            text-decoration: none;
        }

        #lb-download:hover {
            background: rgba(255, 255, 255, .25);
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        {{-- Page Header --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon"><i class="fas fa-bullhorn"></i></div>
                <div>
                    <h5 class="ph-title">{{ Str::limit($pengaduan->judul_pengaduan, 60) }}</h5>
                    <ol class="ph-breadcrumb">
                        <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                        <li><span class="bc-active">Detail</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if (Auth::user()->isMasyarakatBiasa())

                    @if ($pengaduan->status === 'pending')
                        <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}" class="btn btn-sm btn-warning"
                            style="border-radius:10px;font-size:.82rem;font-weight:600;">
                            <i class="fas fa-pencil-alt me-1"></i> Edit
                        </a>
                    @endif
                @endif

                @php
                    $kembaliUrl = auth()->user()->hasRole('masyarakat')
                        ? route('pengaduan.index')
                        : route('balasanpengaduan.index');
                @endphp
                <a href="{{ $kembaliUrl }}" class="btn btn-sm btn-light"
                    style="border-radius:10px;border:1.5px solid #e2e8f0;font-size:.82rem;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert mb-3" style="background:#dcfce7;color:#166534;border-radius:10px;font-size:.83rem;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

            {{-- ── Kolom Utama ── --}}
            <div class="col-lg-8">

                {{-- Info Pengaduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-info-circle"></i> Informasi Pengaduan</div>

                        <div class="meta-row">
                            <span class="meta-label">Judul</span>
                            <span class="meta-value fw-semibold">{{ $pengaduan->judul_pengaduan }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Hal / Perihal</span>
                            <span class="meta-value">{{ $pengaduan->hal_pengaduan }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Tanggal</span>
                            <span class="meta-value">
                                {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Status</span>
                            <span class="badge-status badge-{{ $pengaduan->status }}">
                                @switch($pengaduan->status)
                                    @case('pending')
                                        Menunggu
                                    @break

                                    @case('diproses')
                                        Sedang Diproses
                                    @break

                                    @case('selesai')
                                        Selesai
                                    @break

                                    @case('ditolak')
                                        Ditolak
                                    @break
                                @endswitch
                            </span>
                        </div>
                        @if ($pengaduan->alamat)
                            <div class="meta-row">
                                <span class="meta-label">Alamat</span>
                                <span class="meta-value">{{ $pengaduan->alamat }}</span>
                            </div>
                        @endif

                        <hr style="border-color:#f1f5f9;margin:14px 0;">

                        <div style="font-size:.83rem;font-weight:600;color:#94a3b8;margin-bottom:8px;">Deskripsi</div>
                        <div style="font-size:.88rem;color:#1e293b;line-height:1.7;white-space:pre-wrap;">
                            {{ $pengaduan->deskripsi }}</div>
                    </div>
                </div>

                {{-- Lampiran Pengaduan --}}
                @if ($pengaduan->lampiran_pengaduan->count())
                    @php
                        $gambarList = $pengaduan->lampiran_pengaduan->where('tipe', 'gambar');
                        $fileList = $pengaduan->lampiran_pengaduan->where('tipe', 'file');
                    @endphp

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider">
                                <i class="fas fa-paperclip"></i>
                                Lampiran ({{ $pengaduan->lampiran_pengaduan->count() }} file)
                            </div>

                            {{-- ── Foto / Gambar ── --}}
                            @if ($gambarList->count())
                                <div style="font-size:.78rem;font-weight:600;color:#64748b;margin-bottom:8px;">
                                    <i class="fas fa-images me-1"></i> Foto ({{ $gambarList->count() }})
                                </div>
                                <div class="foto-grid mb-4">
                                    @foreach ($gambarList as $lmp)
                                        {{-- ⚠️ Kunci utama: gunakan asset('storage/' . $lmp->path) --}}
                                        <img src="{{ asset('storage/' . $lmp->path) }}" class="foto-thumb"
                                            data-url="{{ asset('storage/' . $lmp->path) }}"
                                            data-name="{{ basename($lmp->path) }}" alt="{{ basename($lmp->path) }}"
                                            onerror="this.src='{{ asset('images/img-error.png') }}';this.style.opacity='.4'">
                                    @endforeach
                                </div>
                            @endif

                            {{-- ── File Dokumen ── --}}
                            @if ($fileList->count())
                                <div style="font-size:.78rem;font-weight:600;color:#64748b;margin-bottom:8px;">
                                    <i class="fas fa-file-alt me-1"></i> Dokumen ({{ $fileList->count() }})
                                </div>
                                @foreach ($fileList as $lmp)
                                    @php
                                        $namaFile = basename($lmp->path);
                                        $urlFile = asset('storage/' . $lmp->path);
                                        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
                                        $iconMap = [
                                            'pdf' => 'fa-file-pdf',
                                            'doc' => 'fa-file-word',
                                            'docx' => 'fa-file-word',
                                        ];
                                        $icon = $iconMap[$ext] ?? 'fa-file-alt';
                                    @endphp
                                    <div class="lampiran-item">
                                        <div class="lamp-icon file">
                                            <i class="fas {{ $icon }}"></i>
                                        </div>
                                        <span class="lamp-name" title="{{ $namaFile }}">{{ $namaFile }}</span>
                                        <a href="{{ $urlFile }}" target="_blank" class="btn-open">
                                            <i class="fas fa-external-link-alt"></i> Buka
                                        </a>
                                        <a href="{{ $urlFile }}" download="{{ $namaFile }}" class="btn-download">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                @endif

                {{-- Balasan Pegawai --}}
                @if ($pengaduan->balasanpengaduan)
                    @php $balasan = $pengaduan->balasanpengaduan; @endphp
                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider" style="border-left-color:#16a34a;color:#166534;">
                                <i class="fas fa-reply"></i> Balasan dari Petugas
                            </div>

                            <div class="balasan-box">
                                <div class="balasan-header">
                                    <div class="balasan-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="balasan-meta-name">
                                            {{ $balasan->pegawai?->nama_pegawai ?? 'Petugas' }}
                                        </div>
                                        <div class="balasan-meta-date">
                                            {{ \Carbon\Carbon::parse($balasan->tanggal_balasan)->translatedFormat('d F Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="balasan-body">{{ $balasan->balasan }}</div>

                                {{-- Lampiran Balasan --}}
                                @if ($balasan->lampiran_balasan && $balasan->lampiran_balasan->count())
                                    <div style="margin-top:14px;border-top:1px solid #bbf7d0;padding-top:12px;">
                                        <div style="font-size:.75rem;font-weight:700;color:#166534;margin-bottom:8px;">
                                            <i class="fas fa-paperclip me-1"></i>
                                            Lampiran Balasan ({{ $balasan->lampiran_balasan->count() }})
                                        </div>

                                        @php
                                            $balGambar = $balasan->lampiran_balasan->where('tipe', 'gambar');
                                            $balFile = $balasan->lampiran_balasan->where('tipe', 'file');
                                        @endphp

                                        {{-- Gambar balasan --}}
                                        @if ($balGambar->count())
                                            <div class="foto-grid mb-3">
                                                @foreach ($balGambar as $blmp)
                                                    <img src="{{ asset('storage/' . $blmp->path) }}" class="foto-thumb"
                                                        data-url="{{ asset('storage/' . $blmp->path) }}"
                                                        data-name="{{ basename($blmp->path) }}"
                                                        onerror="this.style.opacity='.3'">
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- File balasan --}}
                                        @foreach ($balFile as $blmp)
                                            <div class="balasan-lamp-item">
                                                <div class="lamp-icon file" style="width:30px;height:30px;">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <span
                                                    style="font-size:.8rem;font-weight:600;flex:1;
                                                overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ basename($blmp->path) }}
                                                </span>
                                                <a href="{{ asset('storage/' . $blmp->path) }}" target="_blank"
                                                    class="btn-open" style="font-size:.72rem;">
                                                    <i class="fas fa-external-link-alt"></i> Buka
                                                </a>
                                                <a href="{{ asset('storage/' . $blmp->path) }}" download
                                                    class="btn-download" style="font-size:.72rem;">
                                                    <i class="fas fa-download"></i> Unduh
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Belum ada balasan --}}
                    <div class="card section-card">
                        <div class="card-body py-4 text-center">
                            <div
                                style="width:50px;height:50px;border-radius:50%;background:#f1f5f9;
                            display:flex;align-items:center;justify-content:center;
                            font-size:1.2rem;color:#94a3b8;margin:0 auto 10px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div style="font-size:.85rem;font-weight:600;color:#64748b;">Belum ada balasan</div>
                            <div style="font-size:.78rem;color:#94a3b8;margin-top:4px;">
                                Petugas sedang meninjau pengaduan Anda.
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Kolom Kanan (sidebar) ── --}}
            <div class="col-lg-4">

                {{-- Status & Info --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-info-circle"></i> Ringkasan</div>

                        <div class="meta-row">
                            <span class="meta-label">Status</span>
                            <span class="badge-status badge-{{ $pengaduan->status }}">
                                @switch($pengaduan->status)
                                    @case('pending')
                                        Menunggu
                                    @break

                                    @case('diproses')
                                        Diproses
                                    @break

                                    @case('selesai')
                                        Selesai
                                    @break

                                    @case('ditolak')
                                        Ditolak
                                    @break
                                @endswitch
                            </span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Dibuat</span>
                            <span class="meta-value">
                                {{ $pengaduan->created_at->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Lampiran</span>
                            <span class="meta-value">
                                {{ $pengaduan->lampiran_pengaduan->count() }} file
                            </span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Balasan</span>
                            <span class="meta-value">
                                @if ($pengaduan->balasanpengaduan)
                                    <span style="color:#16a34a;font-weight:600;">
                                        <i class="fas fa-check-circle me-1"></i>Ada
                                    </span>
                                @else
                                    <span style="color:#94a3b8;">Belum ada</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Peta Lokasi --}}
                @if ($pengaduan->latitude && $pengaduan->longitude)
                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Lokasi</div>
                            <div id="map"></div>
                            @if ($pengaduan->alamat)
                                <div style="font-size:.78rem;color:#64748b;margin-top:8px;">
                                    <i class="fas fa-map-pin text-danger me-1"></i>{{ $pengaduan->alamat }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @if(Auth::user()->isMasyarakatBiasa())

                {{-- Aksi (hanya jika pending) --}}
                @if ($pengaduan->status === 'pending')
                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider" style="border-left-color:#dc2626;color:#dc2626;">
                                <i class="fas fa-cog"></i> Aksi
                            </div>
                            <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}"
                                class="btn btn-warning w-100 mb-2" style="border-radius:10px;font-size:.85rem;">
                                <i class="fas fa-pencil-alt me-2"></i>Edit Pengaduan
                            </a>
                            <button class="btn btn-danger w-100 btn-confirm-hapus"
                                data-id="{{ $pengaduan->id_pengaduan }}" data-judul="{{ $pengaduan->judul_pengaduan }}"
                                style="border-radius:10px;font-size:.85rem;">
                                <i class="fas fa-trash-alt me-2"></i>Hapus Pengaduan
                            </button>
                            <form id="form-hapus-{{ $pengaduan->id_pengaduan }}"
                                action="{{ route('pengaduan.destroy', $pengaduan->id_pengaduan) }}" method="POST"
                                class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endif
                @endif

            </div>
        </div>
    </div>

    {{-- Lightbox foto --}}
    <div id="lightbox">
        <button id="lb-close">✕</button>
        <img id="lb-img" src="" alt="">
        <a id="lb-download" href="#" download>
            <i class="fas fa-download me-1"></i> Unduh
        </a>
    </div>
@endsection

@section('scripts')
    @if ($pengaduan->latitude && $pengaduan->longitude)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @if ($pengaduan->latitude && $pengaduan->longitude)
                // ── Peta (read-only) ─────────────────────────────────────────
                const lat = {{ $pengaduan->latitude }};
                const lng = {{ $pengaduan->longitude }};
                const map = L.map('map', {
                    zoomControl: true,
                    dragging: true
                }).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('{{ addslashes($pengaduan->alamat ?? 'Lokasi Pengaduan') }}').openPopup();
            @endif

            // ── Lightbox untuk foto ──────────────────────────────────────
            const lb = document.getElementById('lightbox');
            const lbImg = document.getElementById('lb-img');
            const lbDl = document.getElementById('lb-download');
            const lbClose = document.getElementById('lb-close');

            document.querySelectorAll('.foto-thumb').forEach(img => {
                img.addEventListener('click', () => {
                    lbImg.src = img.dataset.url;
                    lbDl.href = img.dataset.url;
                    lbDl.download = img.dataset.name || 'foto';
                    lb.style.display = 'flex';
                });
            });

            lbClose.addEventListener('click', () => lb.style.display = 'none');
            lb.addEventListener('click', e => {
                if (e.target === lb) lb.style.display = 'none';
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') lb.style.display = 'none';
            });

            // ── Hapus pengaduan ──────────────────────────────────────────
            document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm(`Hapus pengaduan ini? Aksi tidak bisa dibatalkan.`)) {
                        document.getElementById('form-hapus-' + this.dataset.id).submit();
                    }
                });
            });
        });
    </script>
@endsection
