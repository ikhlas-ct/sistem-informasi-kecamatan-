@extends('layouts.user.user')

@section('title', 'Manajemen Masyarakat')

@php
    $roleBadge = match ($rl) {
        'camat'          => ['text' => 'Camat',           'bg' => '#1a73e8'],
        'staf_camat'     => ['text' => 'Staf Camat',      'bg' => '#6366f1'],
        'wali_nagari'    => ['text' => 'Kepala Nagari',   'bg' => '#0d9488'],
        'pegawai_nagari' => ['text' => 'Pegawai Nagari',  'bg' => '#16a34a'],
        default          => ['text' => $rl,                'bg' => '#64748b'],
    };
    $nagaris = $nagaris ?? collect(); 
@endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .table { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent: #1a73e8; --accent-light: #e8f0fe; }

    /* ── Stat Cards ── */
    .stat-card { border:none; border-radius:16px; padding:18px 20px; position:relative; overflow:hidden;
                 transition:transform .2s,box-shadow .2s; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-16px; top:-16px; width:72px; height:72px;
                        border-radius:50%; opacity:.14; }
    .stat-card.blue   { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .stat-card.blue::after   { background:#1a73e8; }
    .stat-card.teal   { background:linear-gradient(135deg,#ccfbf1,#99f6e4); } .stat-card.teal::after   { background:#0d9488; }

    .stat-icon { width:44px; height:44px; border-radius:11px; flex-shrink:0; display:inline-flex;
                 align-items:center; justify-content:center; font-size:1.1rem; }
    .stat-icon.blue { background:#1a73e8; color:#fff; }
    .stat-icon.teal { background:#0d9488; color:#fff; }

    .stat-value { font-size:1.7rem; font-weight:800; color:#1e293b; line-height:1; }
    .stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
                  color:#64748b; margin-top:2px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px;
               display:flex; align-items:center; justify-content:space-between; gap:16px;
               flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden;
               box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
                       border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center;
               justify-content:center; font-size:1.05rem; flex-shrink:0;
               background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Main Card ── */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 16px rgba(0,0,0,.07); }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; border-radius:16px 16px 0 0;
                               padding:16px 20px; }

    /* ── Filter Bar ── */
    .filter-bar { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .filter-bar .form-control,
    .filter-bar .form-select { border-radius:9px; border:1.5px solid #e2e8f0; font-size:.82rem;
                                padding:7px 12px; background:#f8fafc; height:38px; }
    .filter-bar .form-control:focus,
    .filter-bar .form-select:focus { border-color:var(--accent); background:#fff;
                                      box-shadow:0 0 0 3px rgba(26,115,232,.15); }
    .btn-filter { border-radius:9px; font-size:.82rem; font-weight:600; padding:7px 16px; height:38px; }
    .btn-add { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px;
               font-weight:600; font-size:.85rem; padding:8px 18px; color:#fff; transition:all .2s; }
    .btn-add:hover { transform:translateY(-1px); filter:brightness(1.08); color:#fff; }

    /* ── Table ── */
    .table { font-size:.84rem; }
    .table thead th { background:#f8fafc; color:#64748b; font-size:.72rem; font-weight:700;
                      text-transform:uppercase; letter-spacing:.5px; border:none;
                      padding:12px 14px; white-space:nowrap; }
    .table tbody td { padding:12px 14px; border-color:#f1f5f9; vertical-align:middle; }
    .table tbody tr:hover { background:#fafbff; }

    /* ── Avatar ── */
    .avatar { width:42px; height:42px; border-radius:50%; object-fit:cover;
              border:2px solid #e2e8f0; flex-shrink:0; }
    .avatar-placeholder { width:42px; height:42px; border-radius:50%;
                           background:var(--accent-light); color:var(--accent);
                           display:inline-flex; align-items:center; justify-content:center;
                           font-weight:700; font-size:.9rem; flex-shrink:0; }

    /* ── Badges ── */
    .status-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px;
                    border-radius:20px; font-size:.72rem; font-weight:600; }
    .status-aktif    { background:#dcfce7; color:#15803d; }
    .status-nonaktif { background:#fee2e2; color:#991b1b; }

    /* ── Action Buttons ── */
    .btn-action { width:30px; height:30px; padding:0; border-radius:8px; font-size:.75rem;
                  display:inline-flex; align-items:center; justify-content:center;
                  border:none; transition:all .15s; }
    .btn-edit  { background:#e0f2fe; color:#0369a1; }
    .btn-edit:hover  { background:#0369a1; color:#fff; }
    .btn-hapus { background:#fee2e2; color:#dc2626; }
    .btn-hapus:hover { background:#dc2626; color:#fff; }
    .btn-view  { background:#f0fdf4; color:#15803d; }
    .btn-view:hover  { background:#15803d; color:#fff; }

    /* ── Empty state ── */
    .empty-state { padding:52px 20px; text-align:center; }
    .empty-icon  { width:56px; height:56px; border-radius:50%; background:var(--accent-light);
                   color:var(--accent); display:flex; align-items:center; justify-content:center;
                   font-size:1.4rem; margin:0 auto 12px; }

    /* ── Pagination ── */
    .pagination .page-link { border-radius:8px; font-size:.8rem; color:var(--accent);
                              border:1px solid #e2e8f0; margin:0 2px; }
    .pagination .page-item.active .page-link { background:var(--accent); border-color:var(--accent); }
</style>
@endsection

@section('content')
<div class="container" style="padding-left:28px;padding-right:24px;">

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-3"
             style="background:#dcfce7;color:#15803d;border-radius:10px;font-size:.85rem;border:none;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert d-flex align-items-center gap-2 mb-3"
             style="background:#fee2e2;color:#991b1b;border-radius:10px;font-size:.85rem;border:none;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-users"></i></div>
            <div>
                <h5 class="ph-title">Manajemen Masyarakat</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><span class="bc-active">Masyarakat</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge" style="background:{{ $roleBadge['bg'] }};font-size:.75rem;padding:6px 12px;border-radius:20px;">
                <i class="fas fa-shield-alt me-1"></i>{{ $roleBadge['text'] }}
            </span>
            <a href="{{ route('camat.masyarakat.create') }}" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Tambah Masyarakat
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        @if(in_array($rl, ['camat','staf_camat']))
        <div class="col-6 col-lg-6">
            <div class="card stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalAll }}</div>
                        <div class="stat-label">Total Semua Masyarakat</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($ap)
        <div class="col-6 col-lg-6">
            <div class="card stat-card teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon teal"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalNagariku }}</div>
                        <div class="stat-label">
                            Masyarakat {{ $ap->nagari?->nama_nagari ?? 'Nagari Anda' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Main Table Card --}}
    <div class="card main-card">
        <div class="card-header">
            <form method="GET" action="{{ route('camat.masyarakat.index') }}">
                <div class="filter-bar">
                    {{-- Search --}}
                    <div class="flex-grow-1" style="min-width:180px;max-width:300px;">
                        <div class="input-group" style="height:38px;">
                            <span class="input-group-text"
                                  style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:9px 0 0 9px;font-size:.8rem;color:#94a3b8;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control"
                                   style="border-left:none;border-radius:0 9px 9px 0;"
                                   placeholder="Nama, NIK, No HP…"
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filter Nagari (hanya camat/staf_camat) --}}
                    @if(in_array($rl, ['camat','staf_camat']))
                    <select name="id_nagari" class="form-select" style="max-width:200px;">
                        <option value="">Semua Nagari</option>
                        @foreach($nagaris as $nagariItem) {{-- FIX: ganti nama loop var agar tidak konflik --}}
                            <option value="{{ $nagariItem->id }}" {{ request('id_nagari') == $nagariItem->id ? 'selected' : '' }}>
                                {{ $nagariItem->nama_nagari }}
                            </option>
                        @endforeach
                    </select>
                    @endif

                    <button type="submit" class="btn btn-filter btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','id_nagari']))
                        <a href="{{ route('camat.masyarakat.index') }}" class="btn btn-filter btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="56">Foto</th>
                        <th>Nama & NIK</th>
                        <th>No. HP</th>
                        <th>Nagari</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($masyarakats as $i => $masyarakat)
                        <tr>
                            <td class="text-muted">{{ $masyarakats->firstItem() + $i }}</td>

                            {{-- Foto --}}
                            <td>
                                @if($masyarakat->foto_profil)
                                    <img src="{{ asset('storage/'.$masyarakat->foto_profil) }}"
                                         alt="{{ $masyarakat->nama_masyarakat }}" class="avatar">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($masyarakat->nama_masyarakat, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Nama & NIK --}}
                            <td>
                                <div class="fw-semibold" style="color:#1e293b;font-size:.85rem;">
                                    {{ $masyarakat->nama_masyarakat }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-id-card me-1"></i>{{ $masyarakat->nik }}
                                </div>
                            </td>

                            {{-- No HP --}}
                            <td style="font-size:.82rem;color:#475569;">
                                {{ $masyarakat->no_hp ?? '–' }}
                            </td>

                            {{-- Nagari --}}
                            <td style="font-size:.82rem;color:#475569;">
                                {{ $masyarakat->nagari?->nama_nagari ?? '–' }}
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($masyarakat->user?->status === 'aktif')
                                    <span class="status-badge status-aktif">
                                        <i class="fas fa-check-circle" style="font-size:.65rem;"></i> Aktif
                                    </span>
                                @else
                                    <span class="status-badge status-nonaktif">
                                        <i class="fas fa-ban" style="font-size:.65rem;"></i> Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="d-flex gap-1">
                                  
                                    <a href="{{ route('camat.masyarakat.edit', $masyarakat->id_masyarakat) }}"
                                       class="btn btn-action btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('camat.masyarakat.toggleStatus', $masyarakat->id_masyarakat) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-action"
                                                style="background:#fef9c3;color:#a16207;"
                                                title="{{ $masyarakat->user?->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $masyarakat->user?->status === 'aktif' ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada data masyarakat</div>
                                    <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tambahkan masyarakat baru</div>
                                    <a href="{{ route('camat.masyarakat.create') }}" class="btn btn-primary btn-sm mt-3">
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
        @if($masyarakats->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background:#fff;border-top:1px solid #f1f5f9;padding:12px 20px;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $masyarakats->firstItem() }}</strong>–<strong>{{ $masyarakats->lastItem() }}</strong>
                    dari <strong>{{ $masyarakats->total() }}</strong> masyarakat
                </small>
                {{ $masyarakats->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
