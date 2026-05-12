{{-- filepath: resources/views/pages/camat/nagari/index.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Data Nagari')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body, .card, .table, .btn, h4, h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .stat-card::after {
            content: ''; position: absolute; right: -18px; top: -18px;
            width: 80px; height: 80px; border-radius: 50%; opacity: .12;
        }
        .stat-card.blue  { background: linear-gradient(135deg,#e8f0fe,#dbeafe); }
        .stat-card.blue::after  { background: #1a73e8; }
        .stat-card.green { background: linear-gradient(135deg,#e6f9f0,#d1fae5); }
        .stat-card.green::after { background: #16a34a; }
        .stat-card.orange{ background: linear-gradient(135deg,#fff7ed,#ffedd5); }
        .stat-card.orange::after{ background: #ea580c; }

        .stat-icon {
            width:48px; height:48px; border-radius:12px;
            display:inline-flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
        }
        .stat-icon.blue  { background:#1a73e8; color:#fff; }
        .stat-icon.green { background:#16a34a; color:#fff; }
        .stat-icon.orange{ background:#ea580c; color:#fff; }

        .stat-value { font-size:1.85rem; font-weight:800; line-height:1; color:#1e293b; }
        .stat-label { font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:3px; }

        /* ===== PAGE HEADER ===== */
        .ph-card {
            background:#fff; border:1px solid #e9ecef; border-radius:14px;
            padding:16px 20px; display:flex; align-items:center;
            justify-content:space-between; gap:16px; flex-wrap:wrap;
            margin-bottom:1.25rem; position:relative; overflow:hidden;
            box-shadow:0 1px 6px rgba(0,0,0,.05);
        }
        .ph-card::before {
            content:''; position:absolute; left:0; top:0; bottom:0;
            width:4px; border-radius:14px 0 0 14px;
        }
        .ph-card.index-page::before { background:#1a73e8; }
        .ph-left { display:flex; align-items:center; gap:12px; }
        .ph-icon {
            width:42px; height:42px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; flex-shrink:0;
        }
        .ph-icon.index { background:#e8f0fe; color:#1a73e8; }
        .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
        .ph-breadcrumb {
            display:flex; align-items:center; gap:4px; flex-wrap:wrap;
            margin-top:4px; list-style:none; padding:0; margin-bottom:0;
        }
        .ph-breadcrumb li { display:flex; align-items:center; }
        .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
        .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
        .ph-breadcrumb a:hover { text-decoration:underline; }
        .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

        /* ===== FILTER CARD ===== */
        .filter-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
        .filter-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:18px 24px; }
        .filter-card .card-header h5 { font-size:.95rem; font-weight:700; color:#1e293b; }
        .filter-section { background:#fafbfc; border-bottom:1px solid #f1f5f9; padding:16px 24px; }

        .form-control, .form-select {
            border-radius:10px; border:1.5px solid #e2e8f0; font-size:.83rem;
            padding:7px 12px; color:#334155; background-color:#f8fafc;
            transition:border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color:#1a73e8; background:#fff;
            box-shadow:0 0 0 3px rgba(26,115,232,.12);
        }
        .input-group .input-group-text {
            background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none;
            border-radius:10px 0 0 10px; color:#94a3b8; font-size:.8rem;
        }
        .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

        /* ===== TABLE ===== */
        .table thead th {
            background:#f8fafc; color:#64748b; font-size:.72rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.6px; padding:12px 16px;
            border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap;
        }
        .table tbody td {
            padding:13px 16px; vertical-align:middle; font-size:.85rem;
            color:#334155; border-bottom:1px solid #f1f5f9;
        }
        .table tbody tr:last-child td { border-bottom:none; }
        .table-hover tbody tr:hover td { background:#f8fafc; }

        /* ===== BADGES ===== */
        .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:6px; letter-spacing:.2px; }
        .badge-aktif    { background:#dcfce7; color:#15803d; }
        .badge-nonaktif { background:#f1f5f9; color:#64748b; }
        .badge-singkat  { background:#e8f0fe; color:#1558b0; }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            width:30px; height:30px; display:inline-flex; align-items:center;
            justify-content:center; border-radius:8px; font-size:.75rem;
            padding:0; border:none; transition:all .15s ease;
        }
        .btn-edit  { background:#fef9c3; color:#a16207; }
        .btn-edit:hover { background:#ca8a04; color:#fff; }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background:linear-gradient(135deg,#1a73e8,#1558b0); border:none;
            border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px;
            box-shadow:0 2px 8px rgba(26,115,232,.35); transition:all .2s ease;
        }
        .btn-primary:hover {
            background:linear-gradient(135deg,#1558b0,#0f3e82);
            box-shadow:0 4px 14px rgba(26,115,232,.45); transform:translateY(-1px);
        }
        .btn-outline-secondary {
            border-radius:10px; font-size:.83rem; border-color:#e2e8f0;
            color:#64748b; padding:7px 12px;
        }
        .btn-outline-secondary:hover { background:#f1f5f9; border-color:#cbd5e1; color:#334155; }

        /* ===== EMPTY STATE ===== */
        .empty-state { padding:60px 20px; }
        .empty-state-icon {
            width:72px; height:72px; background:#f1f5f9; border-radius:50%;
            display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
        }
        .empty-state-icon i { font-size:1.8rem; color:#94a3b8; }

        .card-footer { background:#f8fafc; border-top:1px solid #f1f5f9; padding:12px 24px; }

        .alert-success {
            background:#dcfce7; border:1px solid #bbf7d0;
            color:#15803d; border-radius:12px; font-size:.85rem;
        }

        /* ===== MODAL ===== */
        .modal-content { border:none; border-radius:16px; overflow:hidden; }
        .modal-header  { padding:18px 24px; border-bottom:1px solid #f1f5f9; }
        .modal-body    { padding:20px 24px; }
        .modal-footer  { padding:14px 24px; border-top:1px solid #f1f5f9; background:#fafbfc; }
        .modal-header.bg-primary { background:linear-gradient(135deg,#1a73e8,#1558b0) !important; }
        .modal-header.bg-warning { background:linear-gradient(135deg,#f59e0b,#d97706) !important; }
        .modal-title { font-size:.95rem; font-weight:700; }
        .form-label { font-size:.8rem; font-weight:600; color:#475569; margin-bottom:5px; }
    </style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card index-page">
        <div class="ph-left">
            <div class="ph-icon index"><i class="fas fa-map-marker-alt"></i></div>
            <div>
                <h5 class="ph-title">Daftar Nagari</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><span class="bc-active">Nagari</span></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="page-inner">

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card stat-card blue">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon blue"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="stat-value">{{ $Nagari->count() }}</div>
                            <div class="stat-label">Total Nagari</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card stat-card green">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $Nagari->where('status', true)->count() }}</div>
                            <div class="stat-label">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card stat-card orange">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon orange"><i class="fas fa-times-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $Nagari->where('status', false)->count() }}</div>
                            <div class="stat-label">Tidak Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Table Card --}}
        <div class="card filter-card shadow-sm">

            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary me-2 opacity-75"></i>
                    Daftar Nagari
                </h5>
                <button type="button" class="btn btn-primary btn-sm"
                    data-bs-toggle="modal" data-bs-target="#tambahNagariModal">
                    <i class="fas fa-plus me-1"></i> Tambah Nagari
                </button>
            </div>

            {{-- Filter Section --}}
            <div class="filter-section">
                <form method="GET" action="{{ route('camat.nagari.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama / singkatan / alamat..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="sktm" class="form-select">
                                <option value="">Semua SKTM</option>
                                <option value="1" {{ request('sktm') === '1' ? 'selected' : '' }}>SKTM Aktif</option>
                                <option value="0" {{ request('sktm') === '0' ? 'selected' : '' }}>SKTM Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('camat.nagari.index') }}"
                                class="btn btn-outline-secondary btn-sm ms-1" title="Reset">
                                <i class="fas fa-redo-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Nama Nagari</th>
                                <th>Singkatan</th>
                                <th>Alamat</th>
                                <th>Status Nagari</th>
                                <th>SKTM</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($Nagari as $index => $nagari)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>

                                    {{-- Nama --}}
                                    <td>
                                        <div class="fw-semibold" style="font-size:.87rem;color:#1e293b;">
                                            {{ $nagari->nama_nagari }}
                                        </div>
                                    </td>

                                    {{-- Singkatan --}}
                                    <td>
                                        @if ($nagari->singkatan)
                                            <span class="badge badge-singkat">{{ $nagari->singkatan }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Alamat --}}
                                    <td>
                                        <div style="font-size:.82rem;color:#64748b;">
                                            {{ $nagari->alamat ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Status Nagari --}}
                                    <td>
                                        <span class="badge {{ $nagari->status ? 'badge-aktif' : 'badge-nonaktif' }}">
                                            {{ $nagari->status ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>

                                    {{-- SKTM --}}
                                    <td>
                                        <span class="badge {{ $nagari->surat_keterangan_tidak_mampu ? 'badge-aktif' : 'badge-nonaktif' }}">
                                            {{ $nagari->surat_keterangan_tidak_mampu ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-action btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editNagariModal-{{ $nagari->id }}"
                                                title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-0 text-center">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="fw-semibold text-secondary mb-1">Belum ada data nagari</div>
                                            <div class="text-muted" style="font-size:.8rem;">
                                                Coba ubah filter atau tambahkan data baru
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end .card --}}
    </div>{{-- end .page-inner --}}
</div>


{{-- ==================== MODAL TAMBAH NAGARI ==================== --}}
<div class="modal fade" id="tambahNagariModal" tabindex="-1" aria-labelledby="tambahNagariLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('camat.nagari.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tambahNagariLabel">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Nagari
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Nagari <span class="text-danger">*</span></label>
                        <input type="text" name="nama_nagari"
                            class="form-control @error('nama_nagari') is-invalid @enderror"
                            value="{{ old('nama_nagari') }}"
                            placeholder="Masukkan nama nagari" required>
                        @error('nama_nagari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Singkatan</label>
                        <input type="text" name="singkatan"
                            class="form-control @error('singkatan') is-invalid @enderror"
                            value="{{ old('singkatan') }}"
                            placeholder="Contoh: KTG" maxlength="20">
                        @error('singkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control"
                            value="{{ old('alamat') }}"
                            placeholder="Masukkan alamat nagari">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Status Nagari <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">SKTM <span class="text-danger">*</span></label>
                            <select name="surat_keterangan_tidak_mampu" class="form-select" required>
                                <option value="1" {{ old('surat_keterangan_tidak_mampu') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('surat_keterangan_tidak_mampu') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT NAGARI ==================== --}}
@foreach ($Nagari as $nagari)
<div class="modal fade" id="editNagariModal-{{ $nagari->id }}" tabindex="-1"
    aria-labelledby="editNagariLabel-{{ $nagari->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('camat.nagari.update', $nagari->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editNagariLabel-{{ $nagari->id }}">
                        <i class="fas fa-edit me-2"></i> Edit: {{ $nagari->nama_nagari }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Nagari <span class="text-danger">*</span></label>
                        <input type="text" name="nama_nagari" class="form-control"
                            value="{{ $nagari->nama_nagari }}"
                            placeholder="Masukkan nama nagari" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Singkatan</label>
                        <input type="text" name="singkatan" class="form-control"
                            value="{{ $nagari->singkatan }}"
                            placeholder="Contoh: KTG" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control"
                            value="{{ $nagari->alamat }}"
                            placeholder="Masukkan alamat nagari">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Status Nagari <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ $nagari->status ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$nagari->status ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">SKTM <span class="text-danger">*</span></label>
                            <select name="surat_keterangan_tidak_mampu" class="form-select" required>
                                <option value="1" {{ $nagari->surat_keterangan_tidak_mampu ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$nagari->surat_keterangan_tidak_mampu ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm text-white">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Buka kembali modal tambah jika ada error validasi --}}
@if ($errors->any() && !old('_method'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('tambahNagariModal')).show();
    });
</script>
@endif

@endsection
