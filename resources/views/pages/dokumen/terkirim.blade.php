{{--
    FILE: resources/views/pages/dokumen/terkirim.blade.php
    Halaman daftar dokumen yang DIKIRIM oleh user yang login
--}}
@extends('layouts.user.user')

@section('title', 'Terkirim – Dokumen Bersama')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,h4,h5,label,.btn,.table { font-family:'Plus Jakarta Sans',sans-serif; }
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
    .nav-tabs-custom .nav-link.active { background:#fff; color:var(--accent); box-shadow:0 1px 4px rgba(0,0,0,.1); }

    .card-list { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); overflow:hidden; }
    .table { font-size:.83rem; }
    .table thead th { background:#f8fafc; font-size:.72rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.4px; color:#64748b; border-bottom:2px solid #e9ecef; padding:10px 14px; }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-color:#f1f5f9; }
    .table tbody tr:hover { background:#f8fafc; }

    .badge-status { font-size:.7rem; padding:3px 9px; border-radius:20px; font-weight:700; }
    .badge-aktif { background:#dcfce7; color:#16a34a; }
    .badge-arsip { background:#f1f5f9; color:#64748b; }
    .attach-pill { display:inline-flex; align-items:center; gap:4px; background:#f1f5f9;
        border-radius:20px; padding:2px 8px; font-size:.7rem; color:#64748b; }

    .btn-action { width:30px; height:30px; border-radius:8px; display:inline-flex;
        align-items:center; justify-content:center; font-size:.75rem; border:none; transition:all .15s; }
    .btn-detail { background:#eff6ff; color:#1a73e8; }
    .btn-detail:hover { background:#1a73e8; color:#fff; }
    .btn-edit { background:#fef9c3; color:#854d0e; }
    .btn-edit:hover { background:#d97706; color:#fff; }
    .btn-hapus { background:#fee2e2; color:#dc2626; }
    .btn-hapus:hover { background:#dc2626; color:#fff; }

    .search-box { position:relative; }
    .search-box .form-control { padding-left:36px; border-radius:10px; border:1.5px solid #e2e8f0;
        font-size:.83rem; background:#f8fafc; }
    .search-box .search-icon { position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:#94a3b8; font-size:.8rem; }
    .search-box .form-control:focus { border-color:var(--accent); background:#fff;
        box-shadow:0 0 0 3px var(--accent-shadow); }

    .empty-state { padding:48px 24px; text-align:center; color:#94a3b8; }
    .empty-state .empty-icon { width:60px; height:60px; border-radius:50%; background:#f1f5f9;
        display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 12px; }

    .progress-baca { font-size:.72rem; color:#94a3b8; white-space:nowrap; }
    .progress-baca span { color:#1a73e8; font-weight:700; }
</style>
@endsection

@section('content')
<div class="container" style="padding-left:28px;padding-right:24px;">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-paper-plane"></i></div>
            <div>
                <h5 class="ph-title">Dokumen Bersama</h5>
                <ol class="ph-breadcrumb"><li><span class="bc-active">Terkirim</span></li></ol>
            </div>
        </div>
        <a href="{{ route('dokumen.create') }}" class="btn btn-primary btn-sm"
           style="border-radius:10px;font-size:.83rem;font-weight:600;">
            <i class="fas fa-plus me-1"></i> Kirim Dokumen
        </a>
    </div>

    <ul class="nav nav-tabs-custom">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dokumen.index') }}">
                <i class="fas fa-inbox me-1"></i> Kotak Masuk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dokumen.terkirim') }}">
                <i class="fas fa-paper-plane me-1"></i> Terkirim
            </a>
        </li>
    </ul>

    @if(session('success'))
        <div class="alert mb-3" style="background:#dcfce7;color:#166534;border-radius:10px;font-size:.83rem;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card card-list">
        <div class="card-body pb-0 pt-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('dokumen.terkirim') }}" class="d-flex gap-2 flex-grow-1 flex-wrap">
                <div class="search-box" style="flex:1;min-width:200px;max-width:320px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="form-control" placeholder="Cari judul…"
                           value="{{ request('q') }}">
                </div>
                <select name="status" class="form-select" style="width:140px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.83rem;background:#f8fafc;">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="arsip" {{ request('status') === 'arsip' ? 'selected' : '' }}>Arsip</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:10px;">Cari</button>
                @if(request('q') || request('status'))
                    <a href="{{ route('dokumen.terkirim') }}" class="btn btn-sm btn-light" style="border-radius:10px;">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive mt-2">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Judul</th>
                        <th>Lampiran</th>
                        <th>Penerima</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $i => $doc)
                        <tr>
                            <td class="text-muted">{{ $dokumens->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:260px;">
                                    {{ Str::limit($doc->judul, 60) }}
                                </div>
                                @if($doc->deskripsi)
                                    <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">
                                        {{ Str::limit($doc->deskripsi, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($doc->lampiran->count())
                                    <span class="attach-pill"><i class="fas fa-paperclip"></i> {{ $doc->lampiran->count() }}</span>
                                @endif
                                @if($doc->links->count())
                                    <span class="attach-pill"><i class="fas fa-link"></i> {{ $doc->links->count() }}</span>
                                @endif
                                @if(!$doc->lampiran->count() && !$doc->links->count())
                                    <span class="text-muted" style="font-size:.75rem;">–</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress-baca">
                                    <span>{{ $doc->jumlahSudahDibaca() }}</span>/{{ $doc->jumlahPenerima() }} dibaca
                                </div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">
                                    {{ $doc->jumlahPenerima() }} penerima
                                </div>
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $doc->status }}">
                                    {{ $doc->status === 'aktif' ? 'Aktif' : 'Arsip' }}
                                </span>
                            </td>
                            <td style="white-space:nowrap;font-size:.78rem;color:#64748b;">
                                {{ $doc->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('dokumen.show', $doc->id) }}" class="btn btn-action btn-detail" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dokumen.edit', $doc->id) }}" class="btn btn-action btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                            data-id="{{ $doc->id }}" data-judul="{{ $doc->judul }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="form-hapus-{{ $doc->id }}"
                                          action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon mx-auto"><i class="fas fa-paper-plane"></i></div>
                                <div class="fw-semibold text-secondary mb-1">Belum ada dokumen terkirim</div>
                                <a href="{{ route('dokumen.create') }}" class="btn btn-primary btn-sm mt-3"
                                   style="border-radius:10px;">
                                    <i class="fas fa-plus me-1"></i> Kirim Sekarang
                                </a>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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

@section('scripts')
<script>
document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
    btn.addEventListener('click', function () {
        const id    = this.dataset.id;
        const judul = this.dataset.judul;
        swal({
            title: 'Hapus Dokumen?',
            text: `"${judul}" beserta semua lampiran akan dihapus permanen.`,
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
            dangerMode: true,
        }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
    });
});
</script>
@endsection
