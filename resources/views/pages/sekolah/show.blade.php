@extends('layouts.user.user')

@section('title', 'Detail Sekolah – ' . $sekolah->nama_sekolah)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent:#0d9488; --accent-light:#ccfbf1; }

    .container { padding-left:28px; padding-right:24px; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:20px 22px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    /* ── Detail row ── */
    .detail-row { display:flex; gap:12px; align-items:flex-start; padding:9px 0; border-bottom:1px solid #f8fafc; font-size:.84rem; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { min-width:160px; font-weight:600; color:#64748b; font-size:.8rem; flex-shrink:0; }
    .detail-value { color:#1e293b; }

    /* ── Logo box ── */
    .logo-box { width:100%; aspect-ratio:1; border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; background:#f8fafc; }
    .logo-box img { width:100%; height:100%; object-fit:contain; padding:12px; }
    .logo-box-placeholder { color:#94a3b8; font-size:2.5rem; }

    /* ── Badge ── */
    .badge-aktif    { background:#dcfce7; color:#15803d; font-size:.72rem; padding:4px 12px; border-radius:20px; font-weight:600; }
    .badge-nonaktif { background:#fee2e2; color:#b91c1c; font-size:.72rem; padding:4px 12px; border-radius:20px; font-weight:600; }
    .badge-jenjang  { background:var(--accent-light); color:var(--accent); font-size:.72rem; padding:4px 12px; border-radius:20px; font-weight:600; }

    /* ── Stat mini ── */
    .stat-mini { background:#f8fafc; border-radius:10px; padding:12px 16px; text-align:center; border:1px solid #f1f5f9; }
    .stat-mini .val { font-size:1.5rem; font-weight:800; color:#1e293b; }
    .stat-mini .lbl { font-size:.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; }

    .btn-edit-page { background:var(--accent); color:#fff; border:none; border-radius:9px; font-size:.83rem; font-weight:600; padding:8px 18px; }
    .btn-edit-page:hover { background:#0f766e; color:#fff; }
    .btn-back { border-radius:9px; font-size:.83rem; border:1.5px solid #e2e8f0; color:#64748b; padding:7px 16px; }
    .btn-back:hover { background:#f8fafc; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-school"></i></div>
            <div>
                <h5 class="ph-title">Detail Sekolah</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('sekolah.index') }}">Data Sekolah</a></li>
                    <li><span class="bc-active">{{ Str::limit($sekolah->nama_sekolah, 40) }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sekolah.edit', $sekolah->id_sekolah) }}" class="btn btn-edit-page btn-sm">
                <i class="fas fa-pencil-alt me-1"></i> Edit
            </a>
            <a href="{{ route('sekolah.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Kolom Kiri ── --}}
        <div class="col-lg-8">

            {{-- Informasi Sekolah --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-school"></i> Informasi Sekolah</div>

                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-school me-2 text-muted"></i>Nama Sekolah</div>
                        <div class="detail-value fw-semibold">{{ $sekolah->nama_sekolah }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-hashtag me-2 text-muted"></i>NPSN</div>
                        <div class="detail-value">{{ $sekolah->npsn ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-graduation-cap me-2 text-muted"></i>Jenjang</div>
                        <div class="detail-value">
                            <span class="badge-jenjang">{{ $sekolah->jenjang }}</span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Nagari</div>
                        <div class="detail-value">{{ $sekolah->nagari?->nama_nagari ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-location-dot me-2 text-muted"></i>Alamat</div>
                        <div class="detail-value">{{ $sekolah->alamat ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-phone me-2 text-muted"></i>No. HP</div>
                        <div class="detail-value">{{ $sekolah->no_hp ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-envelope me-2 text-muted"></i>Email</div>
                        <div class="detail-value">{{ $sekolah->email ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-circle-check me-2 text-muted"></i>Status</div>
                        <div class="detail-value">
                            <span class="badge-{{ $sekolah->status }}">
                                <i class="fas fa-{{ $sekolah->status === 'aktif' ? 'check' : 'ban' }} me-1"></i>
                                {{ ucfirst($sekolah->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Administrator Sekolah --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-user-tie"></i> Administrator Sekolah</div>
                    @if($sekolah->user)
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-id-card me-2 text-muted"></i>NIP / NIK</div>
                        <div class="detail-value fw-semibold">{{ $sekolah->user->nip_nik }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-user me-2 text-muted"></i>Nama</div>
                        <div class="detail-value">{{ $sekolah->user->masyarakat?->nama ?? '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-tag me-2 text-muted"></i>Role</div>
                        <div class="detail-value">{{ ucfirst($sekolah->user->role) }}</div>
                    </div>
                    @else
                    <div class="text-muted" style="font-size:.84rem;">Tidak ada administrator yang ditetapkan.</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ── Kolom Kanan ── --}}
        <div class="col-lg-4">

            {{-- Logo --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-image"></i> Logo Sekolah</div>
                    <div class="logo-box">
                        @if($sekolah->logo)
                            <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo {{ $sekolah->nama_sekolah }}">
                        @else
                            <div class="logo-box-placeholder"><i class="fas fa-school"></i></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-chart-bar"></i> Statistik</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="val">{{ $sekolah->siswa->count() }}</div>
                                <div class="lbl">Total Siswa</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="val" style="color:#16a34a;">{{ $sekolah->siswaAktif->count() }}</div>
                                <div class="lbl">Siswa Aktif</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="val" style="color:#d97706;">{{ $sekolah->siswaPending->count() }}</div>
                                <div class="lbl">Pending</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="val" style="color:#6366f1;">{{ $sekolah->mading->count() }}</div>
                                <div class="lbl">Mading</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-clock"></i> Informasi Data</div>
                    <div class="detail-row">
                        <div class="detail-label">Ditambahkan</div>
                        <div class="detail-value">{{ $sekolah->created_at->translatedFormat('d M Y, H:i') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Diperbarui</div>
                        <div class="detail-value">{{ $sekolah->updated_at->translatedFormat('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
