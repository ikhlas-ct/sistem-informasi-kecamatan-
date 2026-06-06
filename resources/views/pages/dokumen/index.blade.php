@extends('layouts.user.user')

@section('title', 'Kotak Masuk – Dokumen Bersama')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .table { font-family: 'Plus Jakarta Sans', sans-serif; }
    :root { --accent:#1a73e8; --accent-light:#e8f0fe; --accent-shadow:rgba(26,115,232,.15); }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px;
        display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
        border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center;
        justify-content:center; font-size:1.1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .nav-tabs-custom { display:flex; gap:4px; background:#f1f5f9; border-radius:10px;
        padding:4px; margin-bottom:1.25rem; border:none; }
    .nav-tabs-custom .nav-link { border:none; border-radius:8px; font-size:.82rem; font-weight:600;
        color:#64748b; padding:7px 18px; transition:all .15s; }
    .nav-tabs-custom .nav-link.active { background:#fff; color:var(--accent);
        box-shadow:0 1px 4px rgba(0,0,0,.1); }

    .card-list { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); overflow:hidden; }
    .table { font-size:.83rem; }
    .table thead th { background:#f8fafc; font-size:.72rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.4px; color:#64748b; border-bottom:2px solid #e9ecef; padding:10px 14px; }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-color:#f1f5f9; }
    .table tbody tr:hover { background:#f8fafc; }
    .table tbody tr.unread { background:#eff6ff; }
    .table tbody tr.unread:hover { background:#dbeafe; }

    .badge-role { font-size:.68rem; font-weight:700; padding:3px 8px; border-radius:20px; }
    .badge-aktif  { background:#dcfce7; color:#16a34a; }
    .badge-arsip  { background:#f1f5f9; color:#64748b; }
    .badge-unread { background:#1a73e8; color:#fff; font-size:.65rem;
        padding:2px 7px; border-radius:20px; margin-left:6px; }

    .btn-action { width:30px; height:30px; border-radius:8px; display:inline-flex;
        align-items:center; justify-content:center; font-size:.75rem; border:none; transition:all .15s; }
    .btn-detail { background:#eff6ff; color:#1a73e8; }
    .btn-detail:hover { background:#1a73e8; color:#fff; }

    .empty-state { padding:48px 24px; text-align:center; color:#94a3b8; }
    .empty-state .empty-icon { width:60px; height:60px; border-radius:50%; background:#f1f5f9;
        display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 12px; }

    .search-box { position:relative; }
    .search-box .form-control { padding-left:36px; border-radius:10px; border:1.5px solid #e2e8f0;
        font-size:.83rem; background:#f8fafc; }
    .search-box .search-icon { position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:#94a3b8; font-size:.8rem; }
    .search-box .form-control:focus { border-color:var(--accent); background:#fff;
        box-shadow:0 0 0 3px var(--accent-shadow); }

    .attach-pill { display:inline-flex; align-items:center; gap:4px; background:#f1f5f9;
        border-radius:20px; padding:2px 8px; font-size:.7rem; color:#64748b; }
</style>
@endsection

@section('content')
<div class="container" style="padding-left:28px;padding-right:24px;">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-inbox"></i></div>
            <div>
                <h5 class="ph-title">
                    Dokumen Bersama
                    @if($belumDibaca > 0)
                        <span class="badge-unread">{{ $belumDibaca }}</span>
                    @endif
                </h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Kotak Masuk</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('dokumen.create') }}" class="btn btn-primary btn-sm"
           style="border-radius:10px;font-size:.83rem;font-weight:600;">
            <i class="fas fa-plus me-1"></i> Kirim Dokumen
        </a>
    </div>

    {{-- Tab navigasi --}}
    <ul class="nav nav-tabs-custom">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dokumen.index') }}">
                <i class="fas fa-inbox me-1"></i> Kotak Masuk
                @if($belumDibaca > 0)
                    <span class="badge-unread">{{ $belumDibaca }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dokumen.terkirim') }}">
                <i class="fas fa-paper-plane me-1"></i> Terkirim
            </a>
        </li>
    </ul>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="alert mb-3" style="background:#dcfce7;color:#166534;border-radius:10px;font-size:.83rem;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card card-list">
        {{-- Toolbar --}}
        <div class="card-body pb-0 pt-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('dokumen.index') }}" class="d-flex gap-2 flex-grow-1">
                <div class="search-box flex-grow-1" style="max-width:320px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="form-control" placeholder="Cari judul dokumen…"
                           value="{{ request('q') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:10px;">Cari</button>
                @if(request('q'))
                    <a href="{{ route('dokumen.index') }}" class="btn btn-sm btn-light" style="border-radius:10px;">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive mt-2">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Judul & Pengirim</th>
                        <th>Lampiran</th>
                        <th>Status Baca</th>
                        <th>Izin</th>
                        <th>Tanggal</th>
                        <th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $i => $doc)
                        @php
                            $pivot = $doc->penerimas->firstWhere('id_user', auth()->id());
                        @endphp
                        <tr class="{{ !$pivot?->sudah_dibaca ? 'unread' : '' }}">
                            <td class="text-muted">{{ $dokumens->firstItem() + $i }}</td>

                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:280px;">
                                    @if(!$pivot?->sudah_dibaca)
                                        <span style="width:7px;height:7px;background:#1a73e8;border-radius:50%;
                                            display:inline-block;margin-right:6px;"></span>
                                    @endif
                                    {{ Str::limit($doc->judul, 60) }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $doc->pengirim?->namaTampil() ?? '-' }}
                                    <span class="badge-role ms-1"
                                        style="background:#e8f0fe;color:#1a73e8;font-size:.64rem;padding:2px 6px;border-radius:20px;">
                                        {{ $doc->pengirim?->getRoleLabel() }}
                                    </span>
                                </div>
                            </td>

                            {{-- Lampiran --}}
                            <td>
                                @if($doc->lampiran->count())
                                    <span class="attach-pill">
                                        <i class="fas fa-paperclip"></i> {{ $doc->lampiran->count() }}
                                    </span>
                                @endif
                                @if($doc->links->count())
                                    <span class="attach-pill">
                                        <i class="fas fa-link"></i> {{ $doc->links->count() }}
                                    </span>
                                @endif
                                @if(!$doc->lampiran->count() && !$doc->links->count())
                                    <span class="text-muted" style="font-size:.75rem;">–</span>
                                @endif
                            </td>

                            {{-- Status baca --}}
                            <td>
                                @if($pivot?->sudah_dibaca)
                                    <span style="font-size:.75rem;color:#16a34a;">
                                        <i class="fas fa-check-double me-1"></i>
                                        {{ $pivot->dibaca_at?->translatedFormat('d M, H:i') }}
                                    </span>
                                @else
                                    <span style="font-size:.75rem;color:#1a73e8;font-weight:700;">
                                        <i class="fas fa-circle me-1" style="font-size:.55rem;"></i>Belum dibaca
                                    </span>
                                @endif
                            </td>

                            {{-- Izin --}}
                            <td>
                                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                    @if($pivot?->izin_lihat)
                                        <span class="attach-pill" style="background:#dcfce7;color:#166534;">
                                            <i class="fas fa-eye"></i> Lihat
                                        </span>
                                    @endif
                                    @if($pivot?->izin_download)
                                        <span class="attach-pill" style="background:#e0f2fe;color:#0369a1;">
                                            <i class="fas fa-download"></i> Unduh
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td style="white-space:nowrap;font-size:.78rem;color:#64748b;">
                                {{ $doc->created_at->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                <a href="{{ route('dokumen.show', $doc->id) }}"
                                   class="btn btn-action btn-detail" title="Buka">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon mx-auto"><i class="fas fa-inbox"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Kotak masuk kosong</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        Belum ada dokumen yang dikirimkan kepada Anda.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($dokumens->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background:#fff;border-top:1px solid #f1f5f9;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $dokumens->firstItem() }}</strong>–<strong>{{ $dokumens->lastItem() }}</strong>
                    dari <strong>{{ $dokumens->total() }}</strong> data
                </small>
                {{ $dokumens->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
