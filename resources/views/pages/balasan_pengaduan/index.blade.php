@extends('layouts.user.user')

@section('title', 'Daftar Pengaduan – Balasan')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, h4, h5, label, .btn, .table {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    :root {
        --accent: #e53e3e;
        --accent-light: #fff5f5;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border: none; border-radius: 16px; padding: 18px 20px;
        position: relative; overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after {
        content: ''; position: absolute; right: -16px; top: -16px;
        width: 72px; height: 72px; border-radius: 50%; opacity: .14;
    }
    .stat-card.blue   { background: linear-gradient(135deg,#e8f0fe,#dbeafe); }
    .stat-card.blue::after   { background: #1a73e8; }
    .stat-card.amber  { background: linear-gradient(135deg,#fffbeb,#fef3c7); }
    .stat-card.amber::after  { background: #d97706; }
    .stat-card.rose   { background: linear-gradient(135deg,#fff1f2,#ffe4e6); }
    .stat-card.rose::after   { background: #e11d48; }
    .stat-card.green  { background: linear-gradient(135deg,#e6f9f0,#d1fae5); }
    .stat-card.green::after  { background: #16a34a; }
    .stat-card.red    { background: linear-gradient(135deg,#fff1f2,#ffe4e6); }
    .stat-card.red::after    { background: #dc2626; }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .stat-icon.blue   { background: #1a73e8; color: #fff; }
    .stat-icon.amber  { background: #d97706; color: #fff; }
    .stat-icon.rose   { background: #e11d48; color: #fff; }
    .stat-icon.green  { background: #16a34a; color: #fff; }
    .stat-icon.red    { background: #dc2626; color: #fff; }

    .stat-value { font-size: 1.7rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748b; margin-top: 2px; }

    /* ===== PAGE HEADER ===== */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: var(--accent);
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem; flex-shrink: 0;
        background: var(--accent-light); color: var(--accent);
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        list-style: none; padding: 0; margin: 4px 0 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: var(--accent); text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== ROLE BADGE ===== */
    .role-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f1f5f9; color: #475569; border-radius: 20px;
        font-size: .76rem; font-weight: 600; padding: 5px 12px;
        border: 1px solid #e2e8f0;
    }
    .role-chip i { color: var(--accent); }

    /* ===== TABLE CARD ===== */
    .table-card {
        background: #fff; border: 1px solid #f1f5f9; border-radius: 16px;
        overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,.05);
    }
    .table-card-header {
        padding: 14px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 12px; flex-wrap: wrap;
        border-bottom: 1px solid #f1f5f9; background: #fafbfc;
    }
    .table thead th {
        background: #f8fafc; font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px; color: #64748b;
        border-bottom: 1px solid #e9ecef; padding: 10px 14px; white-space: nowrap;
    }
    .table tbody td { padding: 11px 14px; vertical-align: middle; font-size: .84rem; color: #334155; border-color: #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }
    .tr-belum-dibalas { background: #fffbeb !important; }

    /* ===== STATUS BADGES ===== */
    .badge-pending  { background: #fef9c3; color: #854d0e; }
    .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai  { background: #dcfce7; color: #15803d; }
    .badge-ditolak  { background: #fee2e2; color: #991b1b; }
    .badge-sudah    { background: #dcfce7; color: #15803d; }
    .badge-belum    { background: #fef9c3; color: #854d0e; }
    .badge { font-size: .73rem; font-weight: 600; padding: 4px 9px; border-radius: 20px; }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 30px; height: 30px; display: inline-flex; align-items: center;
        justify-content: center; border-radius: 8px; font-size: .75rem;
        border: none; transition: all .15s; padding: 0;
    }
    .btn-detail { background: #e0f2fe; color: #0369a1; }
    .btn-detail:hover { background: #0369a1; color: #fff; }
    .btn-balas  { background: #dcfce7; color: #15803d; }
    .btn-balas:hover  { background: #16a34a; color: #fff; }
    .btn-edit   { background: #fef9c3; color: #854d0e; }
    .btn-edit:hover   { background: #d97706; color: #fff; }

    /* ===== SEARCH & FILTER ===== */
    .filter-input {
        border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .82rem;
        padding: 7px 12px; color: #334155; background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-input:focus {
        border-color: var(--accent); background: #fff;
        box-shadow: 0 0 0 3px rgba(229,62,62,.1); outline: none;
    }
    .btn-filter {
        background: #f1f5f9; color: #475569; border: none;
        border-radius: 10px; font-size: .82rem; padding: 7px 14px;
    }

    /* ===== AVATAR MASYARAKAT ===== */
    .avatar-circle {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, #e53e3e, #f97316);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 800; color: #fff; flex-shrink: 0;
    }

    /* ===== LAMPIRAN THUMB ===== */
    .thumb {
        width: 40px; height: 40px; object-fit: cover;
        border-radius: 8px; border: 1px solid #e2e8f0;
    }
    .thumb-placeholder {
        width: 40px; height: 40px; border-radius: 8px;
        background: #f1f5f9; display: flex; align-items: center;
        justify-content: center; color: #94a3b8; font-size: .85rem;
    }
    .lampiran-stack { display: flex; gap: 4px; flex-wrap: wrap; }
    .lampiran-more {
        width: 40px; height: 40px; border-radius: 8px;
        background: #e2e8f0; display: flex; align-items: center;
        justify-content: center; font-size: .7rem; color: #64748b; font-weight: 700;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state { text-align: center; padding: 48px 24px; }
    .empty-icon {
        width: 72px; height: 72px; border-radius: 50%;
        background: var(--accent-light); color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin: 0 auto 16px;
    }

    /* ===== INFO BANNER (nagari filter) ===== */
    .nagari-banner {
        display: flex; align-items: center; gap: 10px;
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px;
        padding: 10px 16px; font-size: .82rem; color: #1e40af;
        margin-bottom: 1.1rem;
    }
    .nagari-banner i { flex-shrink: 0; font-size: .95rem; }
</style>
@endsection

@section('content')
<div class="container" style="padding-left:28px;padding-right:24px;">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-reply-all"></i></div>
            <div>
                <h5 class="ph-title">Manajemen Balasan Pengaduan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="#">Beranda</a></li>
                    <li><span class="bc-active">Balasan Pengaduan</span></li>
                </ol>
            </div>
        </div>
        {{-- Chip role pegawai yang sedang login --}}
        <div class="role-chip">
            <i class="fas fa-user-shield"></i>
            {{ $pegawai->jabatan ?? 'Pegawai' }}
            @if($pegawai->nagari)
                &mdash; {{ $pegawai->nagari->nama_nagari }}
            @endif
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-3"
             style="background:#dcfce7;color:#15803d;border-radius:12px;border:none;font-size:.84rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert d-flex align-items-center gap-2 mb-3"
             style="background:#fee2e2;color:#991b1b;border-radius:12px;border:none;font-size:.84rem;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Info scope nagari (ditampilkan jika bukan camat / staf camat) --}}
    @if($pegawai->nagari && !in_array(strtolower($pegawai->jabatan ?? ''), ['camat', 'staf camat']))
        <div class="nagari-banner">
            <i class="fas fa-map-marker-alt"></i>
            Anda hanya melihat pengaduan dari masyarakat <strong>Nagari {{ $pegawai->nagari->nama_nagari }}</strong>.
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-list-alt"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card amber">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon amber"><i class="fas fa-comment-slash"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['belum_dibalas'] }}</div>
                        <div class="stat-label">Belum Dibalas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card rose">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon rose"><i class="fas fa-comment-dots"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['sudah_dibalas'] }}</div>
                        <div class="stat-label">Sudah Dibalas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['selesai'] }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card red">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['ditolak'] }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="fw-semibold" style="font-size:.88rem;color:#1e293b;">
                <i class="fas fa-table me-2" style="color:var(--accent);"></i>Daftar Pengaduan
            </div>
            <form method="GET" action="{{ route('balasanpengaduan.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="filter-input" placeholder="Cari judul, hal, atau pengadu…" style="width:210px;">
                <select name="status" class="filter-input" style="width:140px;">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Pending','diproses'=>'Diproses','selesai'=>'Selesai','ditolak'=>'Ditolak'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <select name="balasan" class="filter-input" style="width:150px;">
                    <option value="">Semua Balasan</option>
                    <option value="belum" {{ request('balasan') === 'belum' ? 'selected' : '' }}>Belum Dibalas</option>
                    <option value="sudah" {{ request('balasan') === 'sudah' ? 'selected' : '' }}>Sudah Dibalas</option>
                </select>
                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
                @if(request('search') || request('status') || request('balasan'))
                    <a href="{{ route('balasanpengaduan.index') }}" class="btn btn-filter">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="60">Lampiran</th>
                        <th>Pengadu</th>
                        <th>Judul & Hal</th>
                        <th>Tgl. Masuk</th>
                        <th>Status</th>
                        <th>Balasan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $i => $item)
                        @php
                            $sudahDibalas   = $item->balasanpengaduan !== null;
                            $isPending      = $item->status === 'pending';
                            $gambarLampiran = $item->lampiran_pengaduan->where('tipe', 'gambar');
                            $fileLampiran   = $item->lampiran_pengaduan->where('tipe', 'file');
                            $totalLampiran  = $item->lampiran_pengaduan->count();
                            // Inisial nama masyarakat untuk avatar
                            $namaMasyarakat = $item->masyarakat->nama ?? '-';
                            $inisial        = collect(explode(' ', $namaMasyarakat))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
                        @endphp
                        <tr class="{{ !$sudahDibalas && !$isPending ? 'tr-belum-dibalas' : '' }}">

                            {{-- No --}}
                            <td class="text-muted">{{ $pengaduans->firstItem() + $i }}</td>

                            {{-- Lampiran Pengaduan --}}
                            <td>
                                @if($totalLampiran > 0)
                                    <div class="lampiran-stack">
                                        @foreach($gambarLampiran->take(2) as $lmp)
                                            <img src="{{ asset('storage/' . $lmp->path) }}"
                                                 alt="Lampiran" class="thumb">
                                        @endforeach
                                        @if($fileLampiran->count() > 0)
                                            <div class="thumb-placeholder" title="{{ $fileLampiran->count() }} file">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        @endif
                                        @if($totalLampiran > 3)
                                            <div class="lampiran-more">+{{ $totalLampiran - 3 }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:.78rem;">–</span>
                                @endif
                            </td>

                            {{-- Pengadu (Masyarakat) --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle">{{ $inisial }}</div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:.82rem;color:#1e293b;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $namaMasyarakat }}
                                        </div>
                                        @if($item->masyarakat && $item->masyarakat->nagari)
                                            <div style="font-size:.72rem;color:#94a3b8;">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($item->masyarakat->nagari->nama_nagari ?? '-', 24) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Judul & Hal --}}
                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:200px;">
                                    {{ Str::limit($item->judul_pengaduan, 45) }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-tag me-1"></i>{{ Str::limit($item->hal_pengaduan, 38) }}
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td style="white-space:nowrap;font-size:.8rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengaduan)->translatedFormat('d M Y') }}
                            </td>

                            {{-- Status Pengaduan --}}
                            <td>
                                <span class="badge badge-{{ $item->status }}">
                                    @if($item->status === 'pending')
                                        <i class="fas fa-clock me-1"></i>Pending
                                    @elseif($item->status === 'diproses')
                                        <i class="fas fa-spinner me-1"></i>Diproses
                                    @elseif($item->status === 'selesai')
                                        <i class="fas fa-check me-1"></i>Selesai
                                    @else
                                        <i class="fas fa-ban me-1"></i>Ditolak
                                    @endif
                                </span>
                            </td>

                            {{-- Status Balasan --}}
                            <td>
                                @if($sudahDibalas)
                                    <span class="badge badge-sudah">
                                        <i class="fas fa-check-circle me-1"></i>Sudah Dibalas
                                    </span>
                                    <div style="font-size:.71rem;color:#94a3b8;margin-top:3px;">
                                        {{ \Carbon\Carbon::parse($item->balasanpengaduan->tanggal_balasan)->translatedFormat('d M Y') }}
                                    </div>
                                @else
                                    <span class="badge badge-belum">
                                        <i class="fas fa-hourglass-half me-1"></i>Belum Dibalas
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    {{-- Lihat detail pengaduan --}}
                                    <a href="{{ route('pengaduan.show', $item->id_pengaduan) }}"
                                       class="btn btn-action btn-detail"
                                       title="Lihat Detail Pengaduan">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Balas jika belum ada balasan --}}
                                    @if(!$sudahDibalas)
                                        <a href="{{ route('balasanpengaduan.create', ['id_pengaduan' => $item->id_pengaduan]) }}"
                                           class="btn btn-action btn-balas"
                                           title="Balas Pengaduan">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                    @else
                                        {{-- Edit balasan jika sudah ada --}}
                                        <a href="{{ route('balasanpengaduan.edit', $item->balasanpengaduan->id_balasanpengaduan) }}"
                                           class="btn btn-action btn-edit"
                                           title="Edit Balasan">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada pengaduan</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        @if(request('search') || request('status') || request('balasan'))
                                            Tidak ada data yang cocok dengan filter Anda.
                                        @else
                                            Tidak ada pengaduan yang perlu ditangani saat ini.
                                        @endif
                                    </div>
                                    @if(request('search') || request('status') || request('balasan'))
                                        <a href="{{ route('balasanpengaduan.index') }}" class="btn btn-filter mt-3">
                                            <i class="fas fa-times me-1"></i> Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pengaduans->hasPages())
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3"
                 style="border-top:1px solid #f1f5f9;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $pengaduans->firstItem() }}</strong>–<strong>{{ $pengaduans->lastItem() }}</strong>
                    dari <strong>{{ $pengaduans->total() }}</strong> data
                </small>
                {{ $pengaduans->links() }}
            </div>
        @endif

    </div>{{-- end .table-card --}}
</div>
@endsection

@section('scripts')
<script>
// Tooltip ringan untuk tombol aksi
document.querySelectorAll('.btn-action[title]').forEach(btn => {
    btn.addEventListener('mouseenter', function () {
        // Gunakan Bootstrap tooltip jika sudah diinisialisasi di layout
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            new bootstrap.Tooltip(this, { trigger: 'manual', placement: 'top' }).show();
        }
    });
});
</script>
@endsection
