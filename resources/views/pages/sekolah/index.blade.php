@extends('layouts.user.user')

@section('title', 'Daftar Sekolah')

@php
    $roleBadge = match ($roleLabel) {
        'camat'         => ['text' => 'Camat (Superadmin)',       'bg' => '#1a73e8'],
        'staf_camat'    => ['text' => 'Staf Camat (Superadmin)',  'bg' => '#6366f1'],
        'wali_nagari'   => ['text' => 'Wali Nagari',              'bg' => '#0d9488'],
        'staf_nagari'   => ['text' => 'Staf Nagari',              'bg' => '#16a34a'],
        'admin_sekolah' => ['text' => 'Admin Sekolah',            'bg' => '#d97706'],
        default         => ['text' => $roleLabel,                 'bg' => '#64748b'],
    };
    // Hak aksi: admin sekolah hanya bisa lihat & edit sekolah miliknya
    $bisaCRUD = $roleLabel !== 'admin_sekolah';
@endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, h4, h5, label, .btn, .table {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    :root {
        --accent: #0d9488;
        --accent-light: #ccfbf1;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border: none;
        border-radius: 16px;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after {
        content: '';
        position: absolute;
        right: -16px; top: -16px;
        width: 72px; height: 72px;
        border-radius: 50%;
        opacity: .14;
    }
    .stat-card.teal  { background: linear-gradient(135deg, #ccfbf1, #99f6e4); }
    .stat-card.teal::after  { background: #0d9488; }
    .stat-card.green { background: linear-gradient(135deg, #e6f9f0, #d1fae5); }
    .stat-card.green::after { background: #16a34a; }
    .stat-card.red   { background: linear-gradient(135deg, #fff1f2, #ffe4e6); }
    .stat-card.red::after   { background: #dc2626; }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 11px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .stat-icon.teal  { background: #0d9488; color: #fff; }
    .stat-icon.green { background: #16a34a; color: #fff; }
    .stat-icon.red   { background: #dc2626; color: #fff; }

    .stat-value { font-size: 1.7rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748b; margin-top: 2px; }

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
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 14px 0 0 14px;
        background: var(--accent);
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        background: var(--accent-light);
        color: var(--accent);
    }
    .ph-title {
        font-size: 1.05rem; font-weight: 700;
        color: #1e293b;
        letter-spacing: -.2px; line-height: 1.2;
        margin: 0;
    }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        flex-wrap: wrap;
        margin-top: 4px;
        list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a   { font-size: .75rem; color: var(--accent); text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== MAIN CARD ===== */
    .main-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
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
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .82rem;
        padding: 7px 11px;
        color: #334155;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
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
    .table { font-size: .82rem; margin-bottom: 0; }
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
    .table tbody tr:hover td { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: none; }

    /* ===== LOGO THUMB ===== */
    .logo-thumb {
        width: 46px; height: 46px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    .logo-placeholder {
        width: 46px; height: 46px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 1.1rem;
        border: 1px solid #e2e8f0;
    }

    /* ===== BADGES ===== */
    .badge { font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 20px; }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #fee2e2; color: #991b1b; }
    .badge-jenjang  { background: var(--accent-light); color: var(--accent); font-size: .7rem; font-weight: 600; padding: 4px 9px; border-radius: 20px; }
    .badge-role {
        font-size: .72rem; font-weight: 600;
        padding: 3px 9px; border-radius: 20px;
        color: #fff;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 30px; height: 30px;
        padding: 0; border-radius: 8px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem;
        transition: all .15s;
    }
    .btn-detail      { background: #e0f2fe; color: #0369a1; }
    .btn-detail:hover{ background: #0369a1; color: #fff; }
    .btn-edit        { background: #fef9c3; color: #a16207; }
    .btn-edit:hover  { background: #ca8a04; color: #fff; }
    .btn-hapus       { background: #fee2e2; color: #dc2626; }
    .btn-hapus:hover { background: #dc2626; color: #fff; }
    .btn-toggle-on        { background: #dcfce7; color: #15803d; }
    .btn-toggle-on:hover  { background: #15803d; color: #fff; }
    .btn-toggle-off       { background: #fee2e2; color: #b91c1c; }
    .btn-toggle-off:hover { background: #b91c1c; color: #fff; }

    /* ===== BTN PRIMARY ===== */
    .btn-primary {
        background: linear-gradient(135deg, var(--accent), color-mix(in srgb, var(--accent) 80%, black));
        border: none; border-radius: 10px;
        font-weight: 600; font-size: .83rem;
        padding: 8px 18px;
        transition: all .2s;
    }
    .btn-primary:hover { transform: translateY(-1px); filter: brightness(1.07); }
    .btn-outline-secondary {
        border-radius: 10px; font-size: .82rem;
        border-color: #e2e8f0; color: #64748b;
        padding: 7px 12px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 48px 24px; text-align: center; }
    .empty-icon {
        width: 60px; height: 60px;
        border-radius: 14px;
        background: var(--accent-light);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 14px;
    }

    /* ===== ALERTS ===== */
    .alert { border: none; border-radius: 12px; font-size: .84rem; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-danger  { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon">
                <i class="fas fa-school"></i>
            </div>
            <div>
                <h5 class="ph-title">Daftar Sekolah</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Sekolah</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            {{-- Badge peran --}}
            <span class="badge-role" style="background:{{ $roleBadge['bg'] }};">
                <i class="fas fa-user-shield me-1"></i>{{ $roleBadge['text'] }}
            </span>
            @if($bisaCRUD)
            <a href="{{ route('sekolah.create') }}" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-1"></i> Tambah Sekolah
            </a>
            @endif
        </div>
    </div>

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
        <div class="col-6 col-md-4">
            <div class="stat-card teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon teal"><i class="fas fa-school"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Sekolah</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
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
        <div class="col-6 col-md-4">
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
                Daftar Sekolah
            </h5>
            <span class="text-muted" style="font-size:.77rem;">{{ $sekolah->total() }} data</span>
        </div>

        {{-- Filter --}}
        <div class="filter-section">
            <form method="GET" action="{{ route('sekolah.index') }}" class="row g-2 align-items-end">

                {{-- Search --}}
                <div class="col-md-4 col-sm-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search" style="font-size:.7rem;"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari nama / NPSN…"
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Filter Nagari (camat / staf camat = superadmin) --}}
                @if(in_array($roleLabel, ['camat','staf_camat']))
                <div class="col-md-2">
                    <select name="id_nagari" class="form-select form-select-sm">
                        <option value="">-- Semua Nagari --</option>
                        @foreach($nagaris as $n)
                            <option value="{{ $n->id }}" {{ request('id_nagari') == $n->id ? 'selected' : '' }}>
                                {{ $n->nama_nagari }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Filter Jenjang --}}
                <div class="col-md-2">
                    <select name="jenjang" class="form-select form-select-sm">
                        <option value="">-- Semua Jenjang --</option>
                        @foreach(['TK','PAUD','SD','MI','SMP','MTs','SMA','MA','SMK'] as $j)
                            <option value="{{ $j }}" {{ request('jenjang') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex col-auto gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','id_nagari','jenjang','status']))
                        <a href="{{ route('sekolah.index') }}" class="btn btn-outline-secondary btn-sm px-3">
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
                        <th width="60">Logo</th>
                        <th>Sekolah</th>
                        <th>Nagari</th>
                        <th>Jenjang</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sekolah as $i => $item)
                    <tr>
                        <td class="text-muted">{{ $sekolah->firstItem() + $i }}</td>

                        {{-- Logo --}}
                        <td>
                            @if($item->logo)
                                <img src="{{ asset('storage/' . $item->logo) }}"
                                     alt="{{ $item->nama_sekolah }}" class="logo-thumb">
                            @else
                                <div class="logo-placeholder"><i class="fas fa-school"></i></div>
                            @endif
                        </td>

                        {{-- Nama & NPSN --}}
                        <td>
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:220px;">
                                {{ Str::limit($item->nama_sekolah, 50) }}
                            </div>
                            @if($item->npsn)
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">
                                    NPSN: {{ $item->npsn }}
                                </div>
                            @endif
                            @if($item->alamat)
                                <div style="font-size:.72rem;color:#94a3b8;">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($item->alamat, 40) }}
                                </div>
                            @endif
                        </td>

                        {{-- Nagari --}}
                        <td style="font-size:.82rem;">{{ $item->nagari?->nama_nagari ?? '-' }}</td>

                        {{-- Jenjang --}}
                        <td><span class="badge-jenjang">{{ $item->jenjang }}</span></td>

                        {{-- Kontak --}}
                        <td style="font-size:.8rem;">
                            @if($item->no_hp)
                                <div><i class="fas fa-phone me-1 text-muted"></i>{{ $item->no_hp }}</div>
                            @endif
                            @if($item->email)
                                <div style="color:#64748b;">
                                    <i class="fas fa-envelope me-1 text-muted"></i>{{ $item->email }}
                                </div>
                            @endif
                            @if(!$item->no_hp && !$item->email)
                                <span class="text-muted">–</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="badge badge-{{ $item->status }}">
                                @if($item->status === 'aktif')
                                    <i class="fas fa-check me-1"></i>Aktif
                                @else
                                    <i class="fas fa-ban me-1"></i>Nonaktif
                                @endif
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                {{-- Detail --}}
                                <a href="{{ route('sekolah.show', $item->id_sekolah) }}"
                                   class="btn btn-action btn-detail" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('sekolah.edit', $item->id_sekolah) }}"
                                   class="btn btn-action btn-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                {{-- Toggle Status: camat, staf camat, pegawai nagari --}}
                                @if($bisaCRUD)
                                <button class="btn btn-action {{ $item->status === 'aktif' ? 'btn-toggle-on' : 'btn-toggle-off' }} btn-toggle-status"
                                        data-id="{{ $item->id_sekolah }}"
                                        data-status="{{ $item->status }}"
                                        title="{{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-{{ $item->status === 'aktif' ? 'toggle-on' : 'toggle-off' }}"></i>
                                </button>
                                @endif

                                {{-- Hapus: camat, staf camat, pegawai nagari --}}
                                @if($bisaCRUD)
                                <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                        data-id="{{ $item->id_sekolah }}"
                                        data-nama="{{ $item->nama_sekolah }}"
                                        title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="form-hapus-{{ $item->id_sekolah }}"
                                      action="{{ route('sekolah.destroy', $item->id_sekolah) }}"
                                      method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-0">
                            <div class="empty-state">
                                <div class="empty-icon mx-auto">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="fw-semibold text-secondary mb-1">Belum ada data sekolah</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    Coba ubah filter atau tambahkan data baru
                                </div>
                                @if($bisaCRUD)
                                <a href="{{ route('sekolah.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Tambah Sekarang
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
        @if($sekolah->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background:#fff; border-top:1px solid #f1f5f9;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $sekolah->firstItem() }}</strong>–<strong>{{ $sekolah->lastItem() }}</strong>
                    dari <strong>{{ $sekolah->total() }}</strong> data
                </small>
                {{ $sekolah->links() }}
            </div>
        @endif

    </div>{{-- end .main-card --}}

</div>
@endsection

@section('scripts')
<script>
// ── Hapus ──────────────────────────────────────────────
document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const nama = this.dataset.nama;
        swal({
            title: 'Hapus Sekolah?',
            text: `"${nama}" akan dihapus permanen beserta logo-nya.`,
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
            dangerMode: true,
        }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
    });
});

// ── Toggle Status ──────────────────────────────────────
document.querySelectorAll('.btn-toggle-status').forEach(btn => {
    btn.addEventListener('click', function () {
        const id     = this.dataset.id;
        const status = this.dataset.status;
        const label  = status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan';

        swal({
            title: label + ' Sekolah?',
            text: 'Status sekolah akan diubah.',
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Ubah!', className: 'btn-warning' } },
        }).then(ok => {
            if (!ok) return;
            fetch(`/sekolah/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                swal('Berhasil', data.message, 'success').then(() => location.reload());
            })
            .catch(() => swal('Gagal', 'Terjadi kesalahan.', 'error'));
        });
    });
});
</script>
@endsection