@extends('layouts.user.user')

@section('title', 'Mading Sekolah')

@php
    // Gunakan helper dari User model — bukan raw role string
    // Admin sekolah: role='masyarakat' + sekolah='admin'
    // Siswa sekolah: role='masyarakat' + sekolah='siswa'
    $isSekolah = Auth::user()->isAdminSekolah();
    $isSiswa   = Auth::user()->isSiswaSekolah();

    $jenisOptions = [
        'karya'       => ['label' => 'Karya Siswa',  'icon' => 'paint-brush',  'color' => '#ec4899', 'light' => '#fce7f3'],
        'pengumuman'  => ['label' => 'Pengumuman',   'icon' => 'bullhorn',     'color' => '#1a73e8', 'light' => '#e8f0fe'],
        'berita'      => ['label' => 'Berita',        'icon' => 'newspaper',   'color' => '#0d9488', 'light' => '#ccfbf1'],
        'cerpen'      => ['label' => 'Cerpen',        'icon' => 'book-open',   'color' => '#9333ea', 'light' => '#f3e8ff'],
        'puisi'       => ['label' => 'Puisi',         'icon' => 'feather-alt', 'color' => '#d97706', 'light' => '#fef3c7'],
        'lainnya'     => ['label' => 'Lainnya',       'icon' => 'folder',      'color' => '#64748b', 'light' => '#f1f5f9'],
    ];
@endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .table { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent: #7c3aed; --accent-light: #f5f3ff; }

    /* ── Stat Cards ── */
    .stat-card { border:none; border-radius:16px; padding:18px 20px; position:relative; overflow:hidden; transition:transform .2s,box-shadow .2s; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-16px; top:-16px; width:72px; height:72px; border-radius:50%; opacity:.14; }
    .stat-card.blue  { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .stat-card.blue::after  { background:#1a73e8; }
    .stat-card.green { background:linear-gradient(135deg,#e6f9f0,#d1fae5); } .stat-card.green::after { background:#16a34a; }
    .stat-card.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); } .stat-card.amber::after { background:#d97706; }
    .stat-card.red   { background:linear-gradient(135deg,#fff1f2,#ffe4e6); } .stat-card.red::after   { background:#dc2626; }
    .stat-icon { width:44px; height:44px; border-radius:11px; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; font-size:1.1rem; }
    .stat-icon.blue  { background:#1a73e8; color:#fff; }
    .stat-icon.green { background:#16a34a; color:#fff; }
    .stat-icon.amber { background:#d97706; color:#fff; }
    .stat-icon.red   { background:#dc2626; color:#fff; }
    .stat-value { font-size:1.7rem; font-weight:800; color:#1e293b; line-height:1; }
    .stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:2px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Main Card ── */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .filter-section { background:#fafbfc; border-bottom:1px solid #f1f5f9; padding:14px 22px; }

    /* ── Form controls ── */
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.82rem; padding:7px 11px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,.12); }
    .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; font-size:.8rem; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

    /* ── Table ── */
    .table { font-size:.82rem; margin-bottom:0; }
    .table thead th { background:#f8fafc; color:#475569; font-weight:700; font-size:.7rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; padding:11px 14px; white-space:nowrap; border-top:none; }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-bottom:1px solid #f1f5f9; color:#334155; }
    .table tbody tr:hover td { background:#f8fafc; }
    .table tbody tr:last-child td { border-bottom:none; }

    /* ── Thumbnail ── */
    .thumb { width:68px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0; }
    .thumb-placeholder { width:68px; height:50px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:.7rem; }

    /* ── Badges ── */
    .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:20px; }
    .badge-approved { background:#dcfce7; color:#15803d; }
    .badge-pending  { background:#fef9c3; color:#854d0e; }
    .badge-rejected { background:#fee2e2; color:#991b1b; }
    .badge-draft    { background:#f1f5f9; color:#64748b; }

    /* ── Action Buttons ── */
    .btn-action { width:30px; height:30px; padding:0; border-radius:8px; border:none; display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; transition:all .15s; }
    .btn-edit    { background:#fef9c3; color:#a16207; } .btn-edit:hover    { background:#ca8a04; color:#fff; }
    .btn-hapus   { background:#fee2e2; color:#dc2626; } .btn-hapus:hover   { background:#dc2626; color:#fff; }
    .btn-approve { background:#dcfce7; color:#16a34a; } .btn-approve:hover { background:#16a34a; color:#fff; }
    .btn-reject  { background:#fee2e2; color:#dc2626; } .btn-reject:hover  { background:#dc2626; color:#fff; }
    .btn-show       { background:#e0f2fe; color:#0369a1; } .btn-show:hover       { background:#0369a1; color:#fff; }
    .btn-toggle-on  { background:#f1f5f9; color:#64748b; } .btn-toggle-on:hover  { background:#16a34a; color:#fff; }
    .btn-toggle-off { background:#dcfce7; color:#16a34a; } .btn-toggle-off:hover { background:#dc2626; color:#fff; }

    /* ── Btn Primary ── */
    .btn-primary { background:linear-gradient(135deg,var(--accent),color-mix(in srgb,var(--accent) 80%,black)); border:none; border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px; transition:all .2s; }
    .btn-primary:hover { transform:translateY(-1px); filter:brightness(1.07); }
    .btn-outline-secondary { border-radius:10px; font-size:.82rem; border-color:#e2e8f0; color:#64748b; padding:7px 12px; }

    /* ── Empty State ── */
    .empty-state { padding:48px 24px; text-align:center; }
    .empty-icon { width:60px; height:60px; border-radius:14px; background:#f1f5f9; color:#94a3b8; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin:0 auto 14px; }

    /* ── Alerts ── */
    .alert { border:none; border-radius:12px; font-size:.84rem; }
    .alert-success { background:#dcfce7; color:#166534; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }
    .alert-info    { background:#dbeafe; color:#1e40af; }

    /* ── Pending row highlight ── */
    .tr-pending td { background:repeating-linear-gradient(45deg,transparent,transparent 6px,rgba(250,240,140,.18) 6px,rgba(250,240,140,.18) 12px) !important; }
    .tr-rejected td { background:repeating-linear-gradient(45deg,transparent,transparent 6px,rgba(255,200,200,.18) 6px,rgba(255,200,200,.18) 12px) !important; }

    /* ── Modal reject ── */
    .modal-content { border-radius:14px; border:none; }
    .modal-header  { border-bottom:1px solid #f1f5f9; }
    .modal-footer  { border-top:1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-thumbtack"></i></div>
            <div>
                <h5 class="ph-title">Mading Sekolah</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Mading</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="badge" style="background:{{ $isSekolah ? '#e0f2fe' : '#f3e8ff' }};color:{{ $isSekolah ? '#0369a1' : '#7c3aed' }};font-size:.75rem;padding:5px 12px;">
                <i class="fas fa-{{ $isSekolah ? 'school' : 'user-graduate' }} me-1"></i>
                {{ $isSekolah ? 'Admin Sekolah' : 'Siswa' }}
            </span>
            <a href="{{ route('mading.create') }}" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-1"></i> Tulis Mading
            </a>
        </div>
    </div>

    {{-- ── Notif siswa belum terverifikasi ── --}}
    @if($isSiswa && (!Auth::user()->siswa || !Auth::user()->siswa->isApproved()))
        <div class="alert alert-info d-flex align-items-center mb-3 gap-2">
            <i class="fas fa-info-circle"></i>
            Akun Anda belum diverifikasi oleh sekolah. Hubungi pihak sekolah untuk aktivasi.
        </div>
    @endif

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3 gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3 gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-thumbtack"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Mading</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['publish'] }}</div>
                        <div class="stat-label">Tayang</div>
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
                    <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['rejected'] }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="card main-card">

        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0" style="font-size:.93rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-list me-2" style="color:var(--accent)"></i> Daftar Mading
            </h5>
            <span class="text-muted" style="font-size:.77rem;">{{ $mading->total() }} data</span>
        </div>

        {{-- Filter --}}
        <div class="filter-section">
            <form method="GET" action="{{ route('mading.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4 col-sm-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search" style="font-size:.7rem;"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul mading…"
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="jenis" class="form-select form-select-sm">
                        <option value="">-- Semua Jenis --</option>
                        @foreach($jenisOptions as $val => $opt)
                            <option value="{{ $val }}" {{ request('jenis') === $val ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="approval" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        @foreach(['approved' => 'Disetujui', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'] as $val => $lbl)
                            <option value="{{ $val }}" {{ request('approval') === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex col-auto gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','jenis','approval']))
                        <a href="{{ route('mading.index') }}" class="btn btn-outline-secondary btn-sm px-3">
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
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Publikasi</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mading as $i => $item)
                        @php
                            $jOpt = $jenisOptions[$item->jenis] ?? $jenisOptions['lainnya'];
                            $rowClass = match($item->approval_status) {
                                'pending'  => 'tr-pending',
                                'rejected' => 'tr-rejected',
                                default    => '',
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td class="text-muted">{{ $mading->firstItem() + $i }}</td>

                            {{-- Sampul --}}
                            <td>
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="thumb">
                                @else
                                    <div class="thumb-placeholder"><i class="fas fa-thumbtack"></i></div>
                                @endif
                            </td>

                            {{-- Judul --}}
                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:240px;">
                                    {{ Str::limit($item->judul, 55) }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $item->user?->masyarakat?->nama_masyarakat
                                        ?? $item->user?->dataSekolah?->nama_sekolah
                                        ?? $item->user?->nip_nik
                                        ?? '-' }}
                                    @if($item->id_user === auth()->id())
                                        <span class="badge ms-1" style="background:#e0f2fe;color:#0369a1;font-size:.65rem;">Saya</span>
                                    @endif
                                </div>
                                {{-- Alasan penolakan --}}
                                @if($item->approval_status === 'rejected' && $item->alasan_penolakan)
                                    <div class="mt-1" style="font-size:.72rem;color:#dc2626;">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ Str::limit($item->alasan_penolakan, 60) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Jenis --}}
                            <td>
                                <span class="badge" style="background:{{ $jOpt['light'] }};color:{{ $jOpt['color'] }};">
                                    <i class="fas fa-{{ $jOpt['icon'] }} me-1"></i>{{ $jOpt['label'] }}
                                </span>
                            </td>

                            {{-- Status approval --}}
                            <td>
                                @if($item->approval_status === 'approved')
                                    <span class="badge badge-approved"><i class="fas fa-check me-1"></i>Tayang</span>
                                @elseif($item->approval_status === 'pending')
                                    <span class="badge badge-pending"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                @elseif($item->approval_status === 'rejected')
                                    <span class="badge badge-rejected"><i class="fas fa-times me-1"></i>Ditolak</span>
                                @else
                                    <span class="badge badge-draft"><i class="fas fa-file me-1"></i>Draft</span>
                                @endif
                            </td>

                            {{-- Publikasi --}}
                            <td style="white-space:nowrap;">
                                {{ $item->tanggal_publikasi?->translatedFormat('d M Y') ?? '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    {{-- Lihat detail: semua role --}}
                                    <a href="{{ route('mading.show', $item->id_mading) }}"
                                        class="btn btn-action btn-show" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Approve & Reject: hanya admin sekolah, hanya mading pending --}}
                                    @if($isSekolah && $item->approval_status === 'pending')
                                        <form action="{{ route('mading.approve', $item->id_mading) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-action btn-approve" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-action btn-reject btn-open-reject"
                                            data-id="{{ $item->id_mading }}" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    {{-- Toggle publish/draft: hanya admin sekolah, hanya mading approved --}}
                                    @if($isSekolah && $item->approval_status === 'approved')
                                        <form action="{{ route('mading.toggle', $item->id_mading) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if($item->status === 'publish')
                                                <button type="submit" class="btn btn-action btn-toggle-off" title="Nonaktifkan (jadikan draft)">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-action btn-toggle-on" title="Aktifkan (publish)">
                                                    <i class="fas fa-toggle-off"></i>
                                                </button>
                                            @endif
                                        </form>
                                    @endif

                                    {{-- Edit & Hapus: admin sekolah atau pemilik mading --}}
                                    @if($isSekolah || $item->id_user === auth()->id())
                                        <a href="{{ route('mading.edit', $item->id_mading) }}"
                                            class="btn btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                            data-id="{{ $item->id_mading }}" data-judul="{{ $item->judul }}"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="form-hapus-{{ $item->id_mading }}"
                                            action="{{ route('mading.destroy', $item->id_mading) }}"
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
                                    <div class="empty-icon mx-auto"><i class="fas fa-thumbtack"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada mading</div>
                                    <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tulis mading baru</div>
                                    <a href="{{ route('mading.create') }}" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-plus me-1"></i> Tulis Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mading->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                style="background:#fff;border-top:1px solid #f1f5f9;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $mading->firstItem() }}</strong>–<strong>{{ $mading->lastItem() }}</strong>
                    dari <strong>{{ $mading->total() }}</strong> data
                </small>
                {{ $mading->links() }}
            </div>
        @endif

    </div>{{-- end .card --}}

</div>

{{-- ── Modal Reject ── --}}
@if($isSekolah)
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="fas fa-times-circle me-2 text-danger"></i>Tolak Mading</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formReject" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold" style="font-size:.83rem;">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan_penolakan" class="form-control" rows="3"
                        placeholder="Tuliskan alasan penolakan mading ini…" required maxlength="500"></textarea>
                    <div class="form-text">Alasan akan ditampilkan kepada siswa.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm" style="background:#dc2626;color:#fff;border-radius:10px;font-weight:600;">
                        <i class="fas fa-times me-1"></i> Tolak Mading
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Konfirmasi hapus
    document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const judul = this.dataset.judul;
            swal({
                title: 'Hapus Mading?',
                text: `"${judul}" akan dihapus permanen beserta seluruh lampirannya.`,
                icon: 'warning',
                buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
                dangerMode: true,
            }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
        });
    });

    // Buka modal reject
    document.querySelectorAll('.btn-open-reject').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            document.getElementById('formReject').action = `/mading/${id}/reject`;
            document.querySelector('#modalReject textarea').value = '';
            new bootstrap.Modal(document.getElementById('modalReject')).show();
        });
    });
</script>
@endsection
