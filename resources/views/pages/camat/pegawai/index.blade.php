@extends('layouts.user.user')

@section('title', 'Manajemen Pegawai')

@php
    $roleBadge = match ($rl) {
        'camat'        => ['text' => 'Camat',         'bg' => '#1a73e8'],
        'staf_camat'   => ['text' => 'Staf Camat',    'bg' => '#6366f1'],
        'wali_nagari'  => ['text' => 'Kepala Nagari', 'bg' => '#0d9488'],
        'staf_nagari'  => ['text' => 'Staf Nagari',   'bg' => '#16a34a'],
        default        => ['text' => $rl,              'bg' => '#64748b'],
    };
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
    .stat-card.indigo { background:linear-gradient(135deg,#ede9fe,#ddd6fe); } .stat-card.indigo::after { background:#6366f1; }
    .stat-card.teal   { background:linear-gradient(135deg,#ccfbf1,#99f6e4); } .stat-card.teal::after   { background:#0d9488; }
    .stat-card.green  { background:linear-gradient(135deg,#dcfce7,#bbf7d0); } .stat-card.green::after  { background:#16a34a; }

    .stat-icon { width:44px; height:44px; border-radius:11px; flex-shrink:0; display:inline-flex;
                 align-items:center; justify-content:center; font-size:1.1rem; }
    .stat-icon.blue   { background:#1a73e8; color:#fff; }
    .stat-icon.indigo { background:#6366f1; color:#fff; }
    .stat-icon.teal   { background:#0d9488; color:#fff; }
    .stat-icon.green  { background:#16a34a; color:#fff; }

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
    .tipe-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px;
                  border-radius:20px; font-size:.72rem; font-weight:700; white-space:nowrap; }
    .tipe-camat       { background:#dbeafe; color:#1e40af; }
    .tipe-staf_camat  { background:#ede9fe; color:#5b21b6; }
    .tipe-kepala      { background:#ccfbf1; color:#0f766e; }
    .tipe-staf_nagari { background:#dcfce7; color:#15803d; }

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
            <div class="ph-icon"><i class="fas fa-users-cog"></i></div>
            <div>
                <h5 class="ph-title">Manajemen Pegawai</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><span class="bc-active">Pegawai</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge" style="background:{{ $roleBadge['bg'] }};font-size:.75rem;padding:6px 12px;border-radius:20px;">
                <i class="fas fa-shield-alt me-1"></i>{{ $roleBadge['text'] }}
            </span>
            @if(!empty($allowedTipes))
                <a href="{{ route('pegawai.create') }}" class="btn btn-add">
                    <i class="fas fa-plus me-1"></i> Tambah Pegawai
                </a>
            @endif
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        @if($rl === 'camat')
        <div class="col-6 col-lg-3">
            <div class="card stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalCamat }}</div>
                        <div class="stat-label">Camat</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon indigo"><i class="fas fa-id-badge"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalStafCamat }}</div>
                        <div class="stat-label">Staf Camat</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-6 col-lg-3">
            <div class="card stat-card teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon teal"><i class="fas fa-user-shield"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalKepala }}</div>
                        <div class="stat-label">Kepala Nagari</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalStafNagari }}</div>
                        <div class="stat-label">Staf Nagari</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card main-card">
        <div class="card-header">
            <form method="GET" action="{{ route('pegawai.index') }}">
                <div class="filter-bar">
                    {{-- Search --}}
                    <div class="flex-grow-1" style="min-width:180px;max-width:280px;">
                        <div class="input-group" style="height:38px;">
                            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:9px 0 0 9px;font-size:.8rem;color:#94a3b8;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control"
                                   style="border-left:none;border-radius:0 9px 9px 0;"
                                   placeholder="Nama, NIP, NIK…"
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filter Tipe --}}
                    @if($rl === 'camat')
                    <select name="tipe" class="form-select" style="max-width:160px;">
                        <option value="">Semua Tipe</option>
                        <option value="camat"        {{ request('tipe')=='camat'        ? 'selected' : '' }}>Camat</option>
                        <option value="staf_camat"   {{ request('tipe')=='staf_camat'   ? 'selected' : '' }}>Staf Camat</option>
                        <option value="kepala_nagari" {{ request('tipe')=='kepala_nagari'? 'selected' : '' }}>Kepala Nagari</option>
                        <option value="staf_nagari"  {{ request('tipe')=='staf_nagari'  ? 'selected' : '' }}>Staf Nagari</option>
                    </select>
                    @elseif($rl === 'staf_camat')
                    <select name="tipe" class="form-select" style="max-width:160px;">
                        <option value="">Semua Tipe</option>
                        <option value="kepala_nagari" {{ request('tipe')=='kepala_nagari'? 'selected' : '' }}>Kepala Nagari</option>
                        <option value="staf_nagari"  {{ request('tipe')=='staf_nagari'  ? 'selected' : '' }}>Staf Nagari</option>
                    </select>
                    @endif

                    {{-- Filter Nagari --}}
                    @if(in_array($rl, ['camat','staf_camat']))
                    <select name="id_nagari" class="form-select" style="max-width:180px;">
                        <option value="">Semua Nagari</option>
                        @foreach($nagaris as $nagari)
                            <option value="{{ $nagari->id }}" {{ request('id_nagari')==$nagari->id ? 'selected' : '' }}>
                                {{ $nagari->nama_nagari }}
                            </option>
                        @endforeach
                    </select>
                    @endif

                    <button type="submit" class="btn btn-filter btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','tipe','id_nagari']))
                        <a href="{{ route('pegawai.index') }}" class="btn btn-filter btn-outline-secondary">
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
                        <th>Nama & NIP</th>
                        <th>Tipe</th>
                        <th>Jabatan</th>
                        <th>Nagari</th>
                        <th>Status</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $i => $pegawai)
                        @php
                            $targetRl = $pegawai->user->getRoleLabel();
                            $tipeLabel = match($targetRl) {
                                'camat'       => ['text'=>'Camat',         'class'=>'tipe-camat'],
                                'staf_camat'  => ['text'=>'Staf Camat',    'class'=>'tipe-staf_camat'],
                                'wali_nagari' => ['text'=>'Kepala Nagari', 'class'=>'tipe-kepala'],
                                'staf_nagari' => ['text'=>'Staf Nagari',   'class'=>'tipe-staf_nagari'],
                                default       => ['text'=>$targetRl,        'class'=>'tipe-staf_nagari'],
                            };
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $pegawais->firstItem() + $i }}</td>

                            {{-- Foto --}}
                            <td>
                                @if($pegawai->foto_profil && $pegawai->foto_profil !== 'pegawai/default.jpg')
                                    <img src="{{ asset('storage/'.$pegawai->foto_profil) }}"
                                         alt="{{ $pegawai->nama_pegawai }}" class="avatar">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($pegawai->nama_pegawai, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Nama & NIP --}}
                            <td>
                                <div class="fw-semibold" style="color:#1e293b;font-size:.85rem;">
                                    {{ $pegawai->nama_pegawai }}
                                </div>
                                <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                    <i class="fas fa-id-card me-1"></i>{{ $pegawai->nip }}
                                </div>
                            </td>

                            {{-- Tipe --}}
                            <td>
                                <span class="tipe-badge {{ $tipeLabel['class'] }}">
                                    <i class="fas fa-circle" style="font-size:.4rem;"></i>
                                    {{ $tipeLabel['text'] }}
                                </span>
                            </td>

                            {{-- Jabatan --}}
                            <td style="font-size:.82rem;color:#475569;">{{ $pegawai->jabatan }}</td>

                            {{-- Nagari --}}
                            <td style="font-size:.82rem;color:#475569;">
                                {{ $pegawai->nagari?->nama_nagari ?? '–' }}
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($pegawai->user->status === 'aktif')
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
                                @php
                                    $canAct = match($rl) {
                                        'camat'       => true,
                                        'staf_camat'  => !is_null($pegawai->id_nagari),
                                        'wali_nagari' => $pegawai->id_nagari == $ap->id_nagari && $pegawai->jabatan_nagari === 'staf_nagari',
                                        default       => false,
                                    };
                                @endphp
                                @if($canAct)
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}"
                                       class="btn btn-action btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button class="btn btn-action btn-hapus btn-confirm-hapus"
                                            data-id="{{ $pegawai->id_pegawai }}"
                                            data-nama="{{ $pegawai->nama_pegawai }}"
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="form-hapus-{{ $pegawai->id_pegawai }}"
                                          action="{{ route('pegawai.destroy', $pegawai->id_pegawai) }}"
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                                @else
                                    <span class="text-muted" style="font-size:.75rem;">–</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                                    <div class="fw-semibold text-secondary mb-1">Belum ada data pegawai</div>
                                    <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tambahkan pegawai baru</div>
                                    @if(!empty($allowedTipes))
                                        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm mt-3">
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
        @if($pegawais->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background:#fff;border-top:1px solid #f1f5f9;padding:12px 20px;">
                <small class="text-muted">
                    Menampilkan <strong>{{ $pegawais->firstItem() }}</strong>–<strong>{{ $pegawais->lastItem() }}</strong>
                    dari <strong>{{ $pegawais->total() }}</strong> pegawai
                </small>
                {{ $pegawais->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const nama = this.dataset.nama;
            swal({
                title: 'Hapus Pegawai?',
                text: `"${nama}" beserta akun login-nya akan dihapus secara permanen.`,
                icon: 'warning',
                buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
                dangerMode: true,
            }).then(ok => {
                if (ok) document.getElementById('form-hapus-' + id).submit();
            });
        });
    });
</script>
@endsection
