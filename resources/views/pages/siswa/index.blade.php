@extends('layouts.user.user')

@section('title', 'Data Siswa')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,label,.btn{font-family:'Plus Jakarta Sans',sans-serif;}
    :root{--accent:#0d9488;--accent-light:#ccfbf1;}
    .container{padding-left:28px;padding-right:24px;}

    /* ── Page Header ── */
    .ph-card{background:#fff;border:1px solid #e9ecef;border-radius:14px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:1.25rem;position:relative;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.05);}
    .ph-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:14px 0 0 14px;background:var(--accent);}
    .ph-left{display:flex;align-items:center;gap:12px;}
    .ph-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;background:var(--accent-light);color:var(--accent);}
    .ph-title{font-size:1.05rem;font-weight:700;color:#1e293b;margin:0;}
    .ph-sub{font-size:.75rem;color:#94a3b8;margin:3px 0 0;}

    /* ── Stat Cards ── */
    .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:1.25rem;}
    @media(max-width:768px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
    .stat-card{background:#fff;border-radius:12px;padding:14px 16px;box-shadow:0 1px 6px rgba(0,0,0,.05);border:1px solid #f1f5f9;display:flex;align-items:center;gap:12px;}
    .stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
    .si-total  {background:#e0f2fe;color:#0284c7;}
    .si-laki   {background:#dbeafe;color:#1d4ed8;}
    .si-peremp {background:#fce7f3;color:#be185d;}
    .si-kelas  {background:var(--accent-light);color:var(--accent);}
    .stat-num{font-size:1.3rem;font-weight:800;color:#1e293b;line-height:1;}
    .stat-lbl{font-size:.72rem;color:#94a3b8;margin-top:2px;}

    /* ── Filter ── */
    .filter-card{background:#fff;border-radius:14px;box-shadow:0 1px 6px rgba(0,0,0,.05);border:1px solid #f1f5f9;padding:14px 18px;margin-bottom:1rem;}
    .filter-card .form-control,
    .filter-card .form-select{border-radius:9px;border:1.5px solid #e2e8f0;font-size:.8rem;padding:7px 11px;background:#f8fafc;color:#334155;}
    .filter-card .form-control:focus,
    .filter-card .form-select:focus{border-color:var(--accent);background:#fff;box-shadow:none;}
    .btn-filter{background:var(--accent);border:none;border-radius:9px;color:#fff;font-size:.8rem;font-weight:700;padding:7px 18px;}
    .btn-filter:hover{filter:brightness(1.07);color:#fff;}
    .btn-reset{border:1.5px solid #e2e8f0;border-radius:9px;color:#64748b;font-size:.8rem;padding:7px 14px;}
    .btn-reset:hover{background:#f8fafc;}

    /* ── Table ── */
    .table-card{background:#fff;border-radius:14px;box-shadow:0 1px 8px rgba(0,0,0,.06);border:none;overflow:hidden;}
    .table-card .card-header{background:#fff;border-bottom:1px solid #f1f5f9;padding:13px 18px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
    .table-card .card-header h6{font-size:.88rem;font-weight:700;color:#1e293b;margin:0;}
    .siswa-table{width:100%;border-collapse:collapse;}
    .siswa-table thead tr{background:#f8fafc;}
    .siswa-table thead th{font-size:.74rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;padding:10px 14px;border-bottom:1px solid #f1f5f9;white-space:nowrap;}
    .siswa-table tbody tr{border-bottom:1px solid #f8fafc;transition:background .15s;}
    .siswa-table tbody tr:hover{background:#f0fdfa;}
    .siswa-table tbody td{padding:11px 14px;font-size:.83rem;color:#334155;vertical-align:middle;}

    /* ── Avatar ── */
    .tbl-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--accent-light);}
    .tbl-avatar-fallback{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#0f766e);display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;color:#fff;flex-shrink:0;}

    /* ── Sub-role badge ── */
    .badge-siswa{background:var(--accent-light);color:var(--accent);font-size:.7rem;padding:3px 10px;border-radius:20px;font-weight:700;white-space:nowrap;}

    /* ── Action buttons ── */
    .btn-act{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;border:none;cursor:pointer;transition:all .15s;flex-shrink:0;}
    .btn-act-view  {background:#e0f2fe;color:#0284c7;}
    .btn-act-view:hover  {background:#bae6fd;}
    .btn-act-edit  {background:var(--accent-light);color:var(--accent);}
    .btn-act-edit:hover  {background:#99f6e4;}
    .btn-act-delete{background:#fee2e2;color:#ef4444;}
    .btn-act-delete:hover{background:#fecaca;}

    /* ── Tambah ── */
    .btn-tambah{background:linear-gradient(135deg,var(--accent),#0f766e);border:none;border-radius:10px;color:#fff;font-size:.82rem;font-weight:700;padding:8px 18px;}
    .btn-tambah:hover{filter:brightness(1.07);color:#fff;}

    /* ── Empty ── */
    .empty-state{text-align:center;padding:48px 20px;color:#94a3b8;}
    .empty-state i{font-size:2.4rem;margin-bottom:12px;}
    .empty-state h6{font-size:.9rem;font-weight:700;color:#64748b;margin-bottom:4px;}
    .empty-state p{font-size:.8rem;}

    /* ── Pagination ── */
    .pagination .page-link{border-radius:8px!important;font-size:.78rem;color:var(--accent);border:1.5px solid #e2e8f0;margin:0 2px;padding:5px 12px;}
    .pagination .page-item.active .page-link{background:var(--accent);border-color:var(--accent);color:#fff;}
    .pagination .page-link:hover{background:var(--accent-light);color:var(--accent);}
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
                <h5 class="ph-title">Data Siswa</h5>
                <p class="ph-sub">
                    @if($isAdminSekolah) Kelola siswa sekolah Anda
                    @elseif($isNagari)   Kelola siswa di nagari Anda
                    @else                Kelola seluruh data siswa
                    @endif
                </p>
            </div>
        </div>
        @if($isSuperAdmin || $isNagari || $isAdminSekolah)
        <a href="{{ route('siswa.create') }}" class="btn btn-tambah">
            <i class="fas fa-plus me-1"></i> Tambah Siswa
        </a>
        @endif
    </div>

    {{-- ── Alert ── --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:.82rem;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    @php
        $totalLaki    = $siswa->getCollection()->filter(fn($s) =>
            $s->user?->masyarakat?->jenis_kelamin === 'laki-laki')->count();
        $totalPeremp  = $siswa->getCollection()->filter(fn($s) =>
            $s->user?->masyarakat?->jenis_kelamin === 'perempuan')->count();
        $jumlahKelas  = $totalPerKelas->count();
    @endphp
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon si-total"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-num">{{ $totalSiswa }}</div>
                <div class="stat-lbl">Total Siswa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-laki"><i class="fas fa-mars"></i></div>
            <div>
                <div class="stat-num">{{ $totalLaki }}</div>
                <div class="stat-lbl">Laki-laki</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-peremp"><i class="fas fa-venus"></i></div>
            <div>
                <div class="stat-num">{{ $totalPeremp }}</div>
                <div class="stat-lbl">Perempuan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-kelas"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <div class="stat-num">{{ $jumlahKelas }}</div>
                <div class="stat-lbl">Total Kelas</div>
            </div>
        </div>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('siswa.index') }}" id="form-filter">
            <div class="row g-2 align-items-end">

                {{-- Search --}}
                <div class="col-md-3">
                    <label style="font-size:.75rem;font-weight:600;color:#64748b;margin-bottom:4px;">Cari Siswa</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"
                              style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:9px 0 0 9px;">
                            <i class="fas fa-search" style="color:#94a3b8;font-size:.75rem;"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                               style="border-left:none;border-radius:0 9px 9px 0;"
                               placeholder="Nama / NIS / NIK..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Nagari (superAdmin) --}}
                @if($isSuperAdmin)
                <div class="col-md-2">
                    <label style="font-size:.75rem;font-weight:600;color:#64748b;margin-bottom:4px;">Nagari</label>
                    <select name="id_nagari" id="filter-nagari" class="form-select"
                            onchange="this.form.submit()">
                        <option value="">Semua Nagari</option>
                        @foreach($nagariList as $n)
                            <option value="{{ $n->id }}"
                                {{ request('id_nagari') == $n->id ? 'selected' : '' }}>
                                {{ $n->nama_nagari }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Sekolah (superAdmin & nagari) --}}
                @if(!$isAdminSekolah)
                <div class="col-md-3">
                    <label style="font-size:.75rem;font-weight:600;color:#64748b;margin-bottom:4px;">Sekolah</label>
                    <select name="id_sekolah" id="filter-sekolah" class="form-select">
                        <option value="">Semua Sekolah</option>
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id_sekolah }}"
                                {{ request('id_sekolah') == $s->id_sekolah ? 'selected' : '' }}>
                                {{ $s->nama_sekolah }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Kelas --}}
                <div class="col-md-2">
                    <label style="font-size:.75rem;font-weight:600;color:#64748b;margin-bottom:4px;">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($totalPerKelas->keys() as $k)
                            <option value="{{ $k }}"
                                {{ request('kelas') === $k ? 'selected' : '' }}>
                                {{ $k }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-filter">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('siswa.index') }}" class="btn btn-reset ms-1">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ── Tabel ── --}}
    <div class="table-card card">
        <div class="card-header">
            <h6><i class="fas fa-list me-2" style="color:var(--accent);"></i>Daftar Siswa</h6>
            <span style="font-size:.75rem;color:#94a3b8;">{{ $siswa->total() }} data ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="siswa-table">
                <thead>
                    <tr>
                        <th style="width:42px;">#</th>
                        <th>Siswa</th>
                        <th>NIK</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        @if(!$isAdminSekolah)<th>Sekolah</th>@endif
                        @if($isSuperAdmin)<th>Nagari</th>@endif
                        <th>Jenis Kelamin</th>
                        <th>Sub-role</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $idx => $s)
                    @php
                        $masy = $s->user?->masyarakat;
                    @endphp
                    <tr>
                        <td style="color:#94a3b8;font-size:.75rem;">
                            {{ $siswa->firstItem() + $idx }}
                        </td>

                        {{-- Nama + foto --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($s->foto_profil_url !== asset('default-image/default-user.png'))
                                    <img src="{{ $s->foto_profil_url }}" alt="{{ $s->nama_siswa }}"
                                         class="tbl-avatar">
                                @else
                                    <div class="tbl-avatar-fallback">
                                        {{ strtoupper(substr($s->nama_siswa, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:700;color:#1e293b;font-size:.83rem;">
                                        {{ $s->nama_siswa }}
                                    </div>
                                    @if($masy?->no_hp)
                                    <div style="font-size:.72rem;color:#94a3b8;">
                                        <i class="fas fa-phone me-1"></i>{{ $masy->no_hp }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- NIK dari user --}}
                        <td style="font-size:.8rem;color:#64748b;">
                            {{ $s->user?->nip_nik ?? '-' }}
                        </td>

                        {{-- NIS --}}
                        <td style="font-size:.82rem;">
                            {{ $s->nis ?? '<span style="color:#cbd5e1;">—</span>' }}
                        </td>

                        {{-- Kelas --}}
                        <td style="font-size:.82rem;">
                            {{ $s->kelas ?? '<span style="color:#cbd5e1;">—</span>' }}
                        </td>

                        {{-- Sekolah (kecuali admin sekolah) --}}
                        @if(!$isAdminSekolah)
                        <td>
                            <div style="font-size:.82rem;font-weight:600;color:#334155;">
                                {{ $s->sekolah?->nama_sekolah ?? '-' }}
                            </div>
                            <div style="font-size:.7rem;color:#94a3b8;">
                                {{ strtoupper($s->sekolah?->jenjang ?? '') }}
                            </div>
                        </td>
                        @endif

                        {{-- Nagari (superAdmin saja) --}}
                        @if($isSuperAdmin)
                        <td style="font-size:.8rem;color:#64748b;">
                            {{ $s->sekolah?->nagari?->nama_nagari ?? '-' }}
                        </td>
                        @endif

                        {{-- Jenis Kelamin --}}
                        <td style="font-size:.8rem;color:#64748b;">
                            @if($masy?->jenis_kelamin === 'laki-laki')
                                <i class="fas fa-mars me-1" style="color:#1d4ed8;"></i>Laki-laki
                            @elseif($masy?->jenis_kelamin === 'perempuan')
                                <i class="fas fa-venus me-1" style="color:#be185d;"></i>Perempuan
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>

                        {{-- Sub-role: pastikan sekolah='siswa' --}}
                        <td>
                            @if($s->user?->role === 'masyarakat' && $s->user?->sekolah === 'siswa')
                                <span class="badge-siswa">
                                    <i class="fas fa-graduation-cap me-1"></i>Siswa
                                </span>
                            @else
                                {{-- Data tidak konsisten: user belum ditandai sebagai siswa --}}
                                <span style="background:#fee2e2;color:#b91c1c;font-size:.7rem;padding:3px 10px;border-radius:20px;font-weight:700;">
                                    <i class="fas fa-exclamation me-1"></i>Perlu cek
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('siswa.show', $s->id_siswa) }}"
                                   class="btn-act btn-act-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($isSuperAdmin || $isNagari || $isAdminSekolah)
                                <a href="{{ route('siswa.edit', $s->id_siswa) }}"
                                   class="btn-act btn-act-edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn-act btn-act-delete"
                                        title="Hapus"
                                        onclick="confirmDelete({{ $s->id_siswa }},'{{ addslashes($s->nama_siswa) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="fas fa-user-graduate"></i>
                                <h6>Belum Ada Data Siswa</h6>
                                <p>
                                    @if(request('search') || request('id_sekolah') || request('kelas'))
                                        Tidak ada hasil untuk filter yang dipilih.
                                    @else
                                        Belum ada siswa yang terdaftar.
                                    @endif
                                </p>
                                @if($isSuperAdmin || $isNagari || $isAdminSekolah)
                                <a href="{{ route('siswa.create') }}" class="btn btn-tambah btn-sm mt-2">
                                    <i class="fas fa-plus me-1"></i> Tambah Siswa
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
        @if($siswa->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top:1px solid #f1f5f9;">
            <div style="font-size:.78rem;color:#94a3b8;">
                Menampilkan {{ $siswa->firstItem() }}–{{ $siswa->lastItem() }}
                dari {{ $siswa->total() }} data
            </div>
            <div>{{ $siswa->links() }}</div>
        </div>
        @endif

    </div>{{-- end table-card --}}

</div>

{{-- ── Modal Hapus ── --}}
<div class="modal fade" id="modal-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.14);">
            <div class="modal-body text-center p-4">
                <div style="width:58px;height:58px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.4rem;color:#ef4444;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 style="font-weight:800;color:#1e293b;margin-bottom:6px;">Hapus Data Siswa?</h5>
                <p style="font-size:.84rem;color:#64748b;margin-bottom:4px;">
                    Anda akan menghapus data siswa <strong id="delete-name"></strong>.
                </p>
                <p style="font-size:.8rem;color:#94a3b8;">
                    Akun akan dikembalikan ke masyarakat biasa.
                </p>
                <form id="delete-form" method="POST" class="mt-3">
                    @csrf @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn"
                                style="border-radius:10px;border:1.5px solid #e2e8f0;color:#64748b;font-size:.84rem;padding:8px 22px;"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                                style="border:none;border-radius:10px;background:#ef4444;color:#fff;font-size:.84rem;font-weight:700;padding:8px 22px;">
                            <i class="fas fa-trash-alt me-1"></i> Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(id, nama) {
    document.getElementById('delete-name').textContent = nama;
    document.getElementById('delete-form').action = `{{ url('siswa') }}/${id}`;
    new bootstrap.Modal(document.getElementById('modal-delete')).show();
}

// Auto-submit filter sekolah & kelas saat berubah
['filter-sekolah'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', function () {
        document.getElementById('form-filter').submit();
    });
});
</script>
@endsection
