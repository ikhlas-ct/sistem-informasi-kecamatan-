@extends('layouts.user.user')

@section('title', 'Pengaduan Saya')

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
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* Stats cards */
    .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:10px; margin-bottom:1.25rem; }
    .stat-card { background:#fff; border:1px solid #e9ecef; border-radius:12px; padding:14px 16px;
        text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .stat-num { font-size:1.5rem; font-weight:800; line-height:1; }
    .stat-label { font-size:.72rem; color:#94a3b8; font-weight:600; margin-top:4px; }

    .card-list { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); overflow:hidden; }
    .table { font-size:.83rem; }
    .table thead th { background:#f8fafc; font-size:.72rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.4px; color:#64748b; border-bottom:2px solid #e9ecef; padding:10px 14px; }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-color:#f1f5f9; }
    .table tbody tr:hover { background:#f8fafc; }

    .badge-status { font-size:.7rem; padding:3px 9px; border-radius:20px; font-weight:700; }
    .badge-pending  { background:#fef9c3; color:#854d0e; }
    .badge-diproses { background:#dbeafe; color:#1e40af; }
    .badge-selesai  { background:#dcfce7; color:#166534; }
    .badge-ditolak  { background:#fee2e2; color:#991b1b; }

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
</style>
@endsection

@section('content')
<div class="container" style="padding-left:28px;padding-right:24px;">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-bullhorn"></i></div>
            <div>
                <h5 class="ph-title">Pengaduan Saya</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Daftar Pengaduan</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengaduan.create') }}" class="btn btn-primary btn-sm"
           style="border-radius:10px;font-size:.83rem;font-weight:600;">
            <i class="fas fa-plus me-1"></i> Buat Pengaduan
        </a>
    </div>

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-num" style="color:#1e293b;">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#854d0e;">{{ $stats['pending'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#1e40af;">{{ $stats['diproses'] }}</div>
            <div class="stat-label">Diproses</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#166534;">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#991b1b;">{{ $stats['ditolak'] }}</div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert mb-3" style="background:#dcfce7;color:#166534;border-radius:10px;font-size:.83rem;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert mb-3" style="background:#fee2e2;color:#991b1b;border-radius:10px;font-size:.83rem;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card card-list">
        <div class="card-body pb-0 pt-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('pengaduan.index') }}" class="d-flex gap-2 flex-grow-1 flex-wrap">
                <div class="search-box" style="flex:1;min-width:200px;max-width:320px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari pengaduan…"
                           value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select"
                        style="width:140px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.83rem;background:#f8fafc;">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Menunggu</option>
                    <option value="diproses" {{ request('status')==='diproses' ? 'selected':'' }}>Diproses</option>
                    <option value="selesai"  {{ request('status')==='selesai'  ? 'selected':'' }}>Selesai</option>
                    <option value="ditolak"  {{ request('status')==='ditolak'  ? 'selected':'' }}>Ditolak</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:10px;">Cari</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-light" style="border-radius:10px;">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive mt-2">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Judul & Hal</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $pengaduans->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:280px;">
                                    {{ Str::limit($p->judul_pengaduan, 55) }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-tag me-1"></i>{{ $p->hal_pengaduan }}
                                </div>
                            </td>
                            <td>
                                @if($p->lampiran_pengaduan->count())
                                    <span class="attach-pill">
                                        <i class="fas fa-paperclip"></i> {{ $p->lampiran_pengaduan->count() }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:.75rem;">–</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $p->status }}">
                                    @switch($p->status)
                                        @case('pending')  Menunggu @break
                                        @case('diproses') Diproses @break
                                        @case('selesai')  Selesai  @break
                                        @case('ditolak')  Ditolak  @break
                                    @endswitch
                                </span>
                            </td>
                            <td style="white-space:nowrap;font-size:.78rem;color:#64748b;">
                                {{ \Carbon\Carbon::parse($p->tanggal_pengaduan)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pengaduan.show', $p->id_pengaduan) }}"
                                       class="btn btn-action btn-detail" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($p->status === 'pending')
                                        <a href="{{ route('pengaduan.edit', $p->id_pengaduan) }}"
                                           class="btn btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                                data-id="{{ $p->id_pengaduan }}"
                                                data-judul="{{ $p->judul_pengaduan }}" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="form-hapus-{{ $p->id_pengaduan }}"
                                              action="{{ route('pengaduan.destroy', $p->id_pengaduan) }}"
                                              method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon mx-auto"><i class="fas fa-bullhorn"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada pengaduan</div>
                                    <a href="{{ route('pengaduan.create') }}" class="btn btn-primary btn-sm mt-3"
                                       style="border-radius:10px;">
                                        <i class="fas fa-plus me-1"></i> Buat Pengaduan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengaduans->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background:#fff;border-top:1px solid #f1f5f9;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $pengaduans->firstItem() }}</strong>–<strong>{{ $pengaduans->lastItem() }}</strong>
                    dari <strong>{{ $pengaduans->total() }}</strong> data
                </small>
                {{ $pengaduans->links() }}
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
        if (confirm(`Hapus pengaduan "${judul}"? Aksi ini tidak bisa dibatalkan.`)) {
            document.getElementById('form-hapus-' + id).submit();
        }
    });
});
</script>
@endsection
