@extends('layouts.user.user')

@section('title', 'Detail Siswa – ' . $siswa->nama_siswa)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,label,.btn{font-family:'Plus Jakarta Sans',sans-serif;}
    :root{--accent:#0d9488;--accent-light:#ccfbf1;}
    .container{padding-left:28px;padding-right:24px;}

    .ph-card{background:#fff;border:1px solid #e9ecef;border-radius:14px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:1.25rem;position:relative;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.05);}
    .ph-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:14px 0 0 14px;background:var(--accent);}
    .ph-left{display:flex;align-items:center;gap:12px;}
    .ph-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;background:var(--accent-light);color:var(--accent);}
    .ph-title{font-size:1.05rem;font-weight:700;color:#1e293b;margin:0;}
    .ph-breadcrumb{display:flex;align-items:center;gap:4px;list-style:none;padding:0;margin:4px 0 0;}
    .ph-breadcrumb li{display:flex;align-items:center;}
    .ph-breadcrumb li+li::before{content:'›';color:#cbd5e1;font-size:.7rem;margin:0 4px;}
    .ph-breadcrumb a{font-size:.75rem;color:var(--accent);text-decoration:none;}
    .ph-breadcrumb .bc-active{font-size:.75rem;color:#94a3b8;}

    .section-card{border:none;border-radius:14px;box-shadow:0 1px 8px rgba(0,0,0,.06);margin-bottom:1.25rem;}
    .section-card .card-body{padding:20px 22px;}
    .section-divider{border-left:4px solid var(--accent);background:#f8f9fa;padding:7px 13px;border-radius:0 6px 6px 0;font-weight:700;font-size:.82rem;color:var(--accent);display:flex;align-items:center;gap:8px;margin-bottom:1.1rem;}

    /* Profil hero */
    .profile-hero{text-align:center;padding:24px 16px 16px;}
    .profile-avatar{width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--accent-light);margin:0 auto 12px;display:block;}
    .profile-avatar-fallback{width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#0f766e);display:flex;align-items:center;justify-content:center;font-size:1.9rem;font-weight:800;color:#fff;margin:0 auto 12px;}
    .profile-name{font-size:1.05rem;font-weight:800;color:#1e293b;margin:0 0 2px;}
    .profile-sub{font-size:.78rem;color:#94a3b8;margin:0 0 10px;}

    /* Sub-role badge */
    .role-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--accent-light);color:var(--accent);}

    /* Detail tabel */
    .dt{width:100%;border-collapse:collapse;}
    .dt tr td{padding:8px 0;font-size:.83rem;border-bottom:1px solid #f1f5f9;vertical-align:top;}
    .dt tr:last-child td{border-bottom:none;}
    .dt .lbl{color:#94a3b8;font-weight:600;width:40%;}
    .dt .val{color:#334155;font-weight:600;}

    /* Action buttons */
    .btn-edit{background:linear-gradient(135deg,var(--accent),#0f766e);color:#fff;border:none;border-radius:10px;font-size:.82rem;font-weight:700;padding:8px 20px;}
    .btn-edit:hover{filter:brightness(1.07);color:#fff;}
    .btn-delete{background:#fee2e2;color:#b91c1c;border:none;border-radius:10px;font-size:.82rem;font-weight:700;padding:8px 20px;}
    .btn-delete:hover{background:#fecaca;color:#991b1b;}
    .btn-back{border-radius:10px;font-size:.82rem;border:1.5px solid #e2e8f0;color:#64748b;padding:7px 18px;}
    .btn-back:hover{background:#f8fafc;}

    .info-box{background:#f0fdfa;border:1.5px solid var(--accent-light);border-radius:10px;padding:10px 14px;font-size:.8rem;color:#0f766e;display:flex;align-items:flex-start;gap:8px;}
</style>
@endsection

@section('content')
@php
    $masyarakat = $siswa->user?->masyarakat;
    $u          = $siswa->user;
@endphp
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
                <h5 class="ph-title">Detail Siswa</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('siswa.index') }}">Data Siswa</a></li>
                    <li><span class="bc-active">{{ Str::limit($siswa->nama_siswa, 35) }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($isSuperAdmin || $isNagari || $isAdminSekolah)
            <a href="{{ route('siswa.edit', $siswa->id_siswa) }}" class="btn btn-edit btn-sm">
                <i class="fas fa-pen me-1"></i> Edit
            </a>
            <button type="button" class="btn btn-delete btn-sm" onclick="confirmDelete()">
                <i class="fas fa-trash-alt me-1"></i> Hapus
            </button>
            @endif
            <a href="{{ route('siswa.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:.82rem;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- ── Kolom Kiri ── --}}
        <div class="col-lg-4">

            {{-- Profil Card --}}
            <div class="card section-card">
                <div class="card-body p-0">
                    <div class="profile-hero">
                        @if($siswa->foto_profil_url !== asset('default-image/default-user.png'))
                            <img src="{{ $siswa->foto_profil_url }}" alt="{{ $siswa->nama_siswa }}"
                                 class="profile-avatar">
                        @else
                            <div class="profile-avatar-fallback">
                                {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                            </div>
                        @endif
                        <div class="profile-name">{{ $siswa->nama_siswa }}</div>
                        <div class="profile-sub">
                            {{ $siswa->nis ? 'NIS: ' . $siswa->nis : 'NIS belum diisi' }}
                        </div>
                        <span class="role-badge">
                            <i class="fas fa-graduation-cap"></i> Siswa Sekolah
                        </span>
                    </div>
                    <hr class="m-0">
                    <div class="p-3">
                        <table class="dt">
                            <tr>
                                <td class="lbl"><i class="fas fa-school me-1"></i>Sekolah</td>
                                <td class="val">{{ $siswa->sekolah?->nama_sekolah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl"><i class="fas fa-layer-group me-1"></i>Jenjang</td>
                                <td class="val">{{ strtoupper($siswa->sekolah?->jenjang ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="lbl"><i class="fas fa-map-marker-alt me-1"></i>Nagari</td>
                                <td class="val">{{ $siswa->sekolah?->nagari?->nama_nagari ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl"><i class="fas fa-chalkboard me-1"></i>Kelas</td>
                                <td class="val">{{ $siswa->kelas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-clock"></i> Riwayat Data</div>
                    <table class="dt">
                        <tr>
                            <td class="lbl">Didaftarkan</td>
                            <td class="val">{{ $siswa->created_at?->translatedFormat('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Diperbarui</td>
                            <td class="val">{{ $siswa->updated_at?->translatedFormat('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>{{-- end col-lg-4 --}}

        {{-- ── Kolom Kanan ── --}}
        <div class="col-lg-8">

            {{-- Data Masyarakat --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-id-card"></i> Data Masyarakat</div>
                    @if($masyarakat)
                    <div class="row g-0">
                        <div class="col-md-6">
                            <table class="dt" style="padding-right:16px;">
                                <tr>
                                    <td class="lbl">Nama Lengkap</td>
                                    <td class="val">{{ $masyarakat->nama_masyarakat }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">NIK</td>
                                    <td class="val">{{ $masyarakat->nik ?? $u?->nip_nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">No. KK</td>
                                    <td class="val">{{ $masyarakat->kk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Jenis Kelamin</td>
                                    <td class="val">{{ ucfirst($masyarakat->jenis_kelamin ?? '-') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6" style="padding-left:16px;border-left:1px solid #f1f5f9;">
                            <table class="dt">
                                <tr>
                                    <td class="lbl">No. HP</td>
                                    <td class="val">{{ $masyarakat->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Nagari</td>
                                    <td class="val">{{ $masyarakat->nagari?->nama_nagari ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Alamat</td>
                                    <td class="val">{{ $masyarakat->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Pekerjaan</td>
                                    <td class="val">{{ $masyarakat->pekerjaan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="info-box">
                        <i class="fas fa-exclamation-triangle flex-shrink-0 mt-1"></i>
                        <span>Data masyarakat tidak ditemukan untuk akun ini.</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Akun Login --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-user-circle"></i> Akun Login</div>
                    @if($u)
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div style="font-size:.74rem;color:#94a3b8;font-weight:600;margin-bottom:3px;">NIK / Username</div>
                            <div style="font-size:.88rem;font-weight:700;color:#1e293b;">{{ $u->nip_nik }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div style="font-size:.74rem;color:#94a3b8;font-weight:600;margin-bottom:3px;">Tipe Akun</div>
                            <div style="margin-top:2px;">
                                @if($u->role === 'masyarakat' && $u->sekolah === 'siswa')
                                    <span style="background:var(--accent-light);color:var(--accent);font-size:.72rem;padding:3px 10px;border-radius:6px;font-weight:700;">
                                        Masyarakat – Siswa Sekolah
                                    </span>
                                @else
                                    <span style="font-size:.83rem;color:#334155;font-weight:600;">{{ ucfirst($u->role) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="font-size:.74rem;color:#94a3b8;font-weight:600;margin-bottom:3px;">Status Akun</div>
                            <span class="badge bg-{{ $u->status === 'aktif' ? 'success' : 'secondary' }} bg-opacity-10
                                         text-{{ $u->status === 'aktif' ? 'success' : 'secondary' }}" style="font-size:.75rem;">
                                {{ ucfirst($u->status ?? '-') }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="text-muted" style="font-size:.82rem;">
                        <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                        Tidak ada akun login yang terhubung.
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info Sekolah Lengkap --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-school"></i> Info Sekolah</div>
                    @php $sekolah = $siswa->sekolah; @endphp
                    @if($sekolah)
                    <div class="row g-0">
                        <div class="col-md-6">
                            <table class="dt" style="padding-right:16px;">
                                <tr>
                                    <td class="lbl">Nama Sekolah</td>
                                    <td class="val">{{ $sekolah->nama_sekolah }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">NPSN</td>
                                    <td class="val">{{ $sekolah->npsn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Jenjang</td>
                                    <td class="val">{{ strtoupper($sekolah->jenjang ?? '-') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6" style="padding-left:16px;border-left:1px solid #f1f5f9;">
                            <table class="dt">
                                <tr>
                                    <td class="lbl">Nagari</td>
                                    <td class="val">{{ $sekolah->nagari?->nama_nagari ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Alamat</td>
                                    <td class="val">{{ $sekolah->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Status</td>
                                    <td class="val">
                                        <span class="badge bg-{{ $sekolah->status === 'aktif' ? 'success' : 'secondary' }} bg-opacity-10
                                                     text-{{ $sekolah->status === 'aktif' ? 'success' : 'secondary' }}" style="font-size:.72rem;">
                                            {{ ucfirst($sekolah->status ?? '-') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-muted" style="font-size:.82rem;">Informasi sekolah tidak tersedia.</div>
                    @endif
                </div>
            </div>

        </div>{{-- end col-lg-8 --}}
    </div>{{-- end row --}}
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modal-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.14);">
            <div class="modal-body text-center p-4">
                <div style="width:58px;height:58px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.4rem;color:#ef4444;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 style="font-weight:800;color:#1e293b;margin-bottom:6px;">Hapus Data Siswa?</h5>
                <p style="font-size:.84rem;color:#64748b;margin-bottom:4px;">
                    Anda akan menghapus data siswa <strong>{{ $siswa->nama_siswa }}</strong>.
                </p>
                <p style="font-size:.8rem;color:#94a3b8;">Akun akan dikembalikan ke masyarakat biasa. Tindakan ini tidak dapat dibatalkan.</p>
                <form action="{{ route('siswa.destroy', $siswa->id_siswa) }}" method="POST" class="mt-3">
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
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('modal-delete')).show();
}
</script>
@endsection
