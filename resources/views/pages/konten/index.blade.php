@extends('layouts.user.user')

@section('title', 'Daftar ' . ucfirst(str_replace('_', ' ', $jenis)))

@php
    $jenisLabel = match ($jenis) {
        'berita' => ['label' => 'Berita', 'icon' => 'newspaper', 'color' => '#1a73e8', 'light' => '#e8f0fe'],
        'artikel' => ['label' => 'Artikel', 'icon' => 'file-alt', 'color' => '#6366f1', 'light' => '#ede9fe'],
        'seni_tari' => ['label' => 'Seni Tari', 'icon' => 'music', 'color' => '#ec4899', 'light' => '#fce7f3'],
        'makanan_daerah' => [
            'label' => 'Makanan Daerah',
            'icon' => 'utensils',
            'color' => '#f97316',
            'light' => '#ffedd5',
        ],
        'kerajinan_daerah' => [
            'label' => 'Kerajinan Daerah',
            'icon' => 'paint-brush',
            'color' => '#d97706',
            'light' => '#fef3c7',
        ],
        'seni_musik' => ['label' => 'Seni Musik', 'icon' => 'guitar', 'color' => '#9333ea', 'light' => '#f3e8ff'],
        'seni_budaya' => [
            'label' => 'Seni Budaya',
            'icon' => 'theater-masks',
            'color' => '#0d9488',
            'light' => '#ccfbf1',
        ],
        'pariwisata' => [
            'label' => 'Pariwisata',
            'icon' => 'map-marked-alt',
            'color' => '#16a34a',
            'light' => '#dcfce7',
        ],
        'pertanian' => ['label' => 'Pertanian', 'icon' => 'seedling', 'color' => '#65a30d', 'light' => '#ecfccb'],
        default => ['label' => ucfirst($jenis), 'icon' => 'list', 'color' => '#64748b', 'light' => '#f1f5f9'],
    };

    $roleBadge = match ($roleLabel) {
        'camat' => ['text' => 'Camat (Superadmin)', 'bg' => '#1a73e8'],
        'staf_camat' => ['text' => 'Staf Camat (Superadmin)', 'bg' => '#6366f1'],
        'wali_nagari' => ['text' => 'Wali Nagari', 'bg' => '#0d9488'],
        'staf_nagari' => ['text' => 'Staf Nagari', 'bg' => '#16a34a'],
        'masyarakat' => ['text' => 'Masyarakat', 'bg' => '#94a3b8'],
        default => ['text' => $roleLabel, 'bg' => '#64748b'],
    };
@endphp

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body,
        .card,
        h4,
        h5,
        label,
        .btn,
        .table {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --accent: {{ $jenisLabel['color'] }};
            --accent-light: {{ $jenisLabel['light'] }};
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -16px;
            top: -16px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            opacity: .14;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #e8f0fe, #dbeafe);
        }

        .stat-card.blue::after {
            background: #1a73e8;
        }

        .stat-card.green {
            background: linear-gradient(135deg, #e6f9f0, #d1fae5);
        }

        .stat-card.green::after {
            background: #16a34a;
        }

        .stat-card.amber {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .stat-card.amber::after {
            background: #d97706;
        }

        .stat-card.red {
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
        }

        .stat-card.red::after {
            background: #dc2626;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .stat-icon.blue {
            background: #1a73e8;
            color: #fff;
        }

        .stat-icon.green {
            background: #16a34a;
            color: #fff;
        }

        .stat-icon.amber {
            background: #d97706;
            color: #fff;
        }

        .stat-icon.red {
            background: #dc2626;
            color: #fff;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
        }

        .stat-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ===== PAGE HEADER ===== */
        .ph-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 16px 20px;
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
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            background: var(--accent-light);
            color: var(--accent);
        }

        .ph-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -.2px;
            line-height: 1.2;
            margin: 0;
        }

        .ph-breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: 4px;
            list-style: none;
            padding: 0;
            margin-bottom: 0;
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

        /* ===== MAIN CARD ===== */
        .main-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            overflow: hidden;
        }

        .main-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 22px;
        }

        .filter-section {
            background: #fafbfc;
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 22px;
        }

        /* ===== FORM CONTROLS ===== */
        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .82rem;
            padding: 7px 11px;
            color: #334155;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }

        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            font-size: .8rem;
            color: #94a3b8;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        /* ===== TABLE ===== */
        .table {
            font-size: .82rem;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 11px 14px;
            white-space: nowrap;
            border-top: none;
        }

        .table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table tbody tr:hover td {
            background: #f8fafc;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== THUMBNAIL ===== */
        .thumb {
            width: 68px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .thumb-placeholder {
            width: 68px;
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: .7rem;
        }

        /* ===== BADGES ===== */
        .badge {
            font-size: .7rem;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 20px;
        }

        .badge-aktif {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-nonaktif {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-role {
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            color: #fff;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            transition: all .15s;
        }

        .btn-detail {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-detail:hover {
            background: #0369a1;
            color: #fff;
        }

        .btn-edit {
            background: #fef9c3;
            color: #a16207;
        }

        .btn-edit:hover {
            background: #ca8a04;
            color: #fff;
        }

        .btn-hapus {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #dc2626;
            color: #fff;
        }

        .btn-approve {
            background: #dcfce7;
            color: #16a34a;
        }

        .btn-approve:hover {
            background: #16a34a;
            color: #fff;
        }

        /* ===== BTN PRIMARY ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), color-mix(in srgb, var(--accent) 80%, black));
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: .83rem;
            padding: 8px 18px;
            transition: all .2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.07);
        }

        .btn-outline-secondary {
            border-radius: 10px;
            font-size: .82rem;
            border-color: #e2e8f0;
            color: #64748b;
            padding: 7px 12px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin: 0 auto 14px;
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: .84rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ===== PENDING NOTICE ===== */
        .tr-pending td {
            background: repeating-linear-gradient(45deg, transparent, transparent 6px, rgba(250, 240, 140, .18) 6px, rgba(250, 240, 140, .18) 12px) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        {{-- ── Page Header ── --}}
        <div class="ph-card">
            <div class="ph-left">
                <div class="ph-icon">
                    <i class="fas fa-{{ $jenisLabel['icon'] }}"></i>
                </div>
                <div>
                    <h5 class="ph-title">Daftar {{ $jenisLabel['label'] }}</h5>
                    <ol class="ph-breadcrumb">
                        <li><span class="bc-active">{{ $jenisLabel['label'] }}</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                {{-- Badge peran --}}
                <span class="badge-role" style="background:{{ $roleBadge['bg'] }};">
                    <i class="fas fa-user-shield me-1"></i>{{ $roleBadge['text'] }}
                </span>
                <a href="{{ route('konten.create', $jenis) }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-plus me-1"></i> Tambah {{ $jenisLabel['label'] }}
                </a>
            </div>
        </div>

        {{-- ── Notif hak akses terbatas ── --}}
        @if (!$isSuperAdmin)
            <div class="alert alert-info d-flex align-items-center mb-3 gap-2">
                <i class="fas fa-info-circle"></i>
                @if ($roleLabel === 'masyarakat')
                    Anda hanya dapat melihat dan mengelola konten milik Anda sendiri.
                @else
                    Anda dapat melihat konten milik Anda dan masyarakat di nagari Anda. Edit/hapus hanya untuk konten milik
                    Anda sendiri.
                @endif
            </div>
        @endif

        {{-- ── Flash ── --}}
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-3 gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-3 gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- ── Stat Cards ── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card blue">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['total'] }}</div>
                            <div class="stat-label">Total Konten</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card green">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['aktif'] }}</div>
                            <div class="stat-label">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card amber">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['pending'] }}</div>
                            <div class="stat-label">Menunggu</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card red">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon red"><i class="fas fa-ban"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['nonaktif'] }}</div>
                            <div class="stat-label">Nonaktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="card main-card">

            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="mb-0" style="font-size:.93rem; font-weight:700; color:#1e293b;">
                    <i class="fas fa-list me-2" style="color:var(--accent)"></i>
                    Daftar {{ $jenisLabel['label'] }}
                </h5>
                <span class="text-muted" style="font-size:.77rem;">{{ $konten->total() }} data</span>
            </div>

            {{-- Filter --}}
            <div class="filter-section">
                <form method="GET" action="{{ route('konten.index', $jenis) }}" class="row g-2 align-items-end">
                    <div class="col-md-5 col-sm-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search" style="font-size:.7rem;"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari judul konten…"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            @foreach (['aktif', 'pending', 'nonaktif'] as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex col-auto gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('konten.index', $jenis) }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th width="80">Sampul</th>
                            <th>Judul & Penulis</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Publikasi</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($konten as $i => $item)
                            @php $isPending = $item->status === 'pending'; @endphp
                            <tr class="{{ $isPending ? 'tr-pending' : '' }}">
                                <td class="text-muted">{{ $konten->firstItem() + $i }}</td>

                                {{-- Sampul --}}
                                <td>
                                    @if ($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                                            class="thumb">
                                    @else
                                        <div class="thumb-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </td>

                                {{-- Judul --}}
                                <td>
                                    <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:240px;">
                                        {{ Str::limit($item->judul, 55) }}
                                    </div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $item->user?->nip_nik ?? '-' }}
                                        @if ($item->id_user === auth()->id())
                                            <span class="badge ms-1"
                                                style="background:#e0f2fe;color:#0369a1;font-size:.65rem;">Saya</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td>
                                    @if ($item->kategori->isNotEmpty())
                                        @foreach ($item->kategori as $kat)
                                            <span class="badge"
                                                style="background:var(--accent-light);color:var(--accent);">
                                                {{ $kat->nama_kategori }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted" style="font-size:.78rem;">–</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge badge-{{ $item->status }}">
                                        @if ($item->status === 'aktif')
                                            <i class="fas fa-check me-1"></i>Aktif
                                        @elseif($item->status === 'pending')
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        @else
                                            <i class="fas fa-ban me-1"></i>Nonaktif
                                        @endif
                                    </span>
                                </td>

                                {{-- Publikasi --}}
                                <td style="white-space:nowrap;">
                                    {{ $item->tanggal_publikasi?->translatedFormat('d M Y') ?? '-' }}
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        {{-- Lihat --}}
                                        <a href="{{ route('konten.detail', [
                                            'jenis_konten' => $jenis,
                                            'slug' => $item->slug,
                                        ]) }}"
                                            class="btn btn-action btn-detail" title="Lihat" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- Approve (superadmin, hanya untuk pending) --}}
                                        @if ($isSuperAdmin && $item->status === 'pending')
                                            <form
                                                action="{{ route('konten.approve', ['jenis' => $jenis, 'id_konten' => $item->id_konten]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-action btn-approve"
                                                    title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Edit (superadmin atau pemilik konten) --}}
                                        @if ($isSuperAdmin || $item->id_user === auth()->id())
                                            <a href="{{ route('konten.edit', ['jenis' => $jenis, 'slug' => $item->slug]) }}"
                                                class="btn btn-action btn-edit" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                                data-id="{{ $item->id_konten }}" data-judul="{{ $item->judul }}"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <form id="form-hapus-{{ $item->id_konten }}"
                                                action="{{ route('konten.destroy', ['jenis' => $jenis, 'id_konten' => $item->id_konten]) }}"
                                                method="POST" class="d-none">
                                                @csrf @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0">
                                    <div class="empty-state">
                                        <div class="empty-icon mx-auto">
                                            <i class="fas fa-{{ $jenisLabel['icon'] }}"></i>
                                        </div>
                                        <div class="fw-semibold text-secondary mb-1">Belum ada {{ $jenisLabel['label'] }}
                                        </div>
                                        <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tambahkan
                                            data baru</div>
                                        <a href="{{ route('konten.create', $jenis) }}"
                                            class="btn btn-primary btn-sm mt-3">
                                            <i class="fas fa-plus me-1"></i> Tambah Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($konten->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                    style="background:#fff;border-top:1px solid #f1f5f9;">
                    <small class="text-muted">
                        Menampilkan <strong>{{ $konten->firstItem() }}</strong>–<strong>{{ $konten->lastItem() }}</strong>
                        dari <strong>{{ $konten->total() }}</strong> data
                    </small>
                    {{ $konten->links() }}
                </div>
            @endif

        </div>{{-- end .card --}}
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const judul = this.dataset.judul;
                swal({
                    title: 'Hapus Konten?',
                    text: `"${judul}" akan dihapus permanen beserta gambar sampulnya.`,
                    icon: 'warning',
                    buttons: {
                        cancel: 'Batal',
                        confirm: {
                            text: 'Ya, Hapus!',
                            className: 'btn-danger'
                        }
                    },
                    dangerMode: true,
                }).then(ok => {
                    if (ok) document.getElementById('form-hapus-' + id).submit();
                });
            });
        });
    </script>
@endsection
