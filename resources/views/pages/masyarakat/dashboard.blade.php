@extends('layouts.user.user')

@section('title', 'Dashboard')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, label, .btn, input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }

    .container { padding-left: 28px; padding-right: 24px; }

    /* ── Page Header ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 22px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.5rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: #6366f1;
    }
    .ph-left  { display: flex; align-items: center; gap: 12px; }
    .ph-icon  { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; background: #ede9fe; color: #6366f1; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.2; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; list-style: none; padding: 0; margin: 4px 0 0; flex-wrap: wrap; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ── Badge role ── */
    .role-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600;
    }
    .badge-masyarakat { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-admin      { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-siswa      { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }

    /* ── Stat Cards ── */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }

    .stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        border: 1px solid #e9ecef; box-shadow: 0 1px 6px rgba(0,0,0,.05);
        display: flex; align-items: center; gap: 14px; position: relative; overflow: hidden;
        transition: transform .18s, box-shadow .18s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.09); }
    .stat-card::after {
        content: ''; position: absolute; right: -14px; top: -14px;
        width: 70px; height: 70px; border-radius: 50%;
        background: currentColor; opacity: .05;
    }

    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;
    }
    .stat-info .stat-val  { font-size: 1.6rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-info .stat-lbl  { font-size: .73rem; font-weight: 600; color: #94a3b8; margin-top: 2px; }

    .si-indigo { background: #ede9fe; color: #6366f1; }
    .si-green  { background: #dcfce7; color: #16a34a; }
    .si-yellow { background: #fef9c3; color: #ca8a04; }
    .si-red    { background: #fee2e2; color: #dc2626; }
    .si-blue   { background: #dbeafe; color: #2563eb; }
    .si-purple { background: #f3e8ff; color: #9333ea; }

    /* ── Section card ── */
    .section-card { border: none; border-radius: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
    .section-card .card-body { padding: 20px 22px; }
    .section-divider {
        border-left: 4px solid #6366f1; background: #f8f9fa;
        padding: 7px 13px; border-radius: 0 6px 6px 0;
        font-weight: 700; font-size: .82rem; color: #6366f1;
        display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;
    }

    /* ── Table ── */
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl th { font-size: .72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .03em; padding: 8px 12px; background: #f8fafc; border-bottom: 1px solid #e9ecef; }
    .tbl td { font-size: .82rem; color: #334155; padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .tbl tr:last-child td { border-bottom: none; }
    .tbl tr:hover td { background: #f8fafc; }

    /* ── Status badge ── */
    .sbadge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px; font-size: .7rem; font-weight: 600;
    }
    .sbadge-green  { background: #dcfce7; color: #16a34a; }
    .sbadge-yellow { background: #fef9c3; color: #b45309; }
    .sbadge-red    { background: #fee2e2; color: #dc2626; }
    .sbadge-blue   { background: #dbeafe; color: #2563eb; }
    .sbadge-gray   { background: #f1f5f9; color: #64748b; }

    /* ── Greeting ── */
    .greeting-card {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 14px; padding: 20px 24px; color: #fff;
        margin-bottom: 1.5rem; display: flex; align-items: center;
        justify-content: space-between; gap: 12px; flex-wrap: wrap;
        box-shadow: 0 4px 16px rgba(99,102,241,.3);
    }
    .greeting-card .greet-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 3px; }
    .greeting-card .greet-sub   { font-size: .8rem; opacity: .85; }
    .greeting-card .greet-icon  { font-size: 2.6rem; opacity: .25; }

    /* ── Dokumen unread dot ── */
    .unread-dot { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block; margin-left: 4px; }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 28px 16px; }
    .empty-state i { font-size: 2rem; color: #e2e8f0; margin-bottom: 8px; }
    .empty-state p { font-size: .8rem; color: #94a3b8; margin: 0; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-home"></i></div>
            <div>
                <h5 class="ph-title">Dashboard</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Dashboard</span></li>
                </ol>
            </div>
        </div>
        <div>
            @if(auth()->user()->isAdminSekolah())
                <span class="role-badge badge-admin"><i class="fas fa-user-shield"></i> Admin Sekolah</span>
            @elseif(auth()->user()->isSiswaSekolah())
                <span class="role-badge badge-siswa"><i class="fas fa-user-graduate"></i> Siswa</span>
            @else
                <span class="role-badge badge-masyarakat"><i class="fas fa-users"></i> Masyarakat</span>
            @endif
        </div>
    </div>

    {{-- Greeting --}}
    <div class="greeting-card">
        <div>
            <div class="greet-title">
                Halo, {{ $masyarakat?->nama_masyarakat ?? auth()->user()->nip_nik }} 👋
            </div>
            <div class="greet-sub">
                @if(auth()->user()->isAdminSekolah())
                    Selamat datang di panel Admin Sekolah. Kelola mading dan data sekolahmu di sini.
                @elseif(auth()->user()->isSiswaSekolah())
                    Selamat datang! Lihat dan kelola mading yang sudah kamu buat.
                @else
                    Selamat datang! Pantau pengaduan dan dokumen kamu di sini.
                @endif
            </div>
        </div>
        <i class="fas fa-sun greet-icon"></i>
    </div>

    {{-- ══════════════════════════════════════════════════
         STAT CARDS — berbeda per sub-role
    ══════════════════════════════════════════════════ --}}

    @if(auth()->user()->isAdminSekolah())
        {{-- ADMIN SEKOLAH --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon si-indigo"><i class="fas fa-newspaper"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalMading ?? 0 }}</div>
                    <div class="stat-lbl">Total Mading</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-yellow"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $madingPending ?? 0 }}</div>
                    <div class="stat-lbl">Menunggu Review</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $madingPublik ?? 0 }}</div>
                    <div class="stat-lbl">Mading Publik</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="fas fa-folder-open"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalDokumenDiterima ?? 0 }}</div>
                    <div class="stat-lbl">Dokumen Diterima</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-red"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $dokumenBelumDibaca ?? 0 }}</div>
                    <div class="stat-lbl">Belum Dibaca</div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->isSiswaSekolah())
        {{-- SISWA --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon si-indigo"><i class="fas fa-pen-nib"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $madingSaya ?? 0 }}</div>
                    <div class="stat-lbl">Mading Saya</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $madingPublikSaya ?? 0 }}</div>
                    <div class="stat-lbl">Sudah Publik</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-yellow"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $madingPendingSaya ?? 0 }}</div>
                    <div class="stat-lbl">Menunggu Approval</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="fas fa-folder-open"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalDokumenDiterima ?? 0 }}</div>
                    <div class="stat-lbl">Dokumen Diterima</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-red"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $dokumenBelumDibaca ?? 0 }}</div>
                    <div class="stat-lbl">Belum Dibaca</div>
                </div>
            </div>
        </div>

    @else
        {{-- MASYARAKAT BIASA --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon si-indigo"><i class="fas fa-bullhorn"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalPengaduan ?? 0 }}</div>
                    <div class="stat-lbl">Total Pengaduan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-yellow"><i class="fas fa-spinner"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $pengaduanProses ?? 0 }}</div>
                    <div class="stat-lbl">Sedang Diproses</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $pengaduanSelesai ?? 0 }}</div>
                    <div class="stat-lbl">Selesai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="fas fa-folder-open"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalDokumenDiterima ?? 0 }}</div>
                    <div class="stat-lbl">Dokumen Diterima</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-red"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <div class="stat-val">{{ $dokumenBelumDibaca ?? 0 }}</div>
                    <div class="stat-lbl">Belum Dibaca</div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════
         TABEL BAWAH — berbeda per sub-role
    ══════════════════════════════════════════════════ --}}

    <div class="row g-3">

        {{-- Kolom kiri: konten utama per sub-role --}}
        <div class="col-lg-7">

            @if(auth()->user()->isAdminSekolah())
            {{-- ADMIN: Tabel mading terbaru --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-newspaper"></i> Mading Terbaru</div>
                    @if(isset($madingTerbaru) && $madingTerbaru->count())
                        <div class="table-responsive">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Sekolah</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($madingTerbaru as $m)
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:#1e293b;">
                                                {{ Str::limit($m->judul, 35) }}
                                            </div>
                                            <div style="font-size:.7rem; color:#94a3b8;">
                                                {{ $m->jenis }}
                                            </div>
                                        </td>
                                        <td>{{ $m->sekolah?->nama_sekolah ?? '-' }}</td>
                                        <td>
                                            @if($m->approval_status === 'approved')
                                                <span class="sbadge sbadge-green"><i class="fas fa-circle" style="font-size:.4rem;"></i> Approved</span>
                                            @elseif($m->approval_status === 'pending')
                                                <span class="sbadge sbadge-yellow"><i class="fas fa-circle" style="font-size:.4rem;"></i> Pending</span>
                                            @else
                                                <span class="sbadge sbadge-red"><i class="fas fa-circle" style="font-size:.4rem;"></i> Ditolak</span>
                                            @endif
                                        </td>
                                        <td style="color:#94a3b8;">{{ $m->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <p>Belum ada mading.</p>
                        </div>
                    @endif
                </div>
            </div>

            @elseif(auth()->user()->isSiswaSekolah())
            {{-- SISWA: Tabel mading milik siswa --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-pen-nib"></i> Mading Saya</div>
                    @if(isset($madingTerbaru) && $madingTerbaru->count())
                        <div class="table-responsive">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($madingTerbaru as $m)
                                    <tr>
                                        <td style="font-weight:600; color:#1e293b;">{{ Str::limit($m->judul, 35) }}</td>
                                        <td>
                                            <span class="sbadge sbadge-blue">{{ $m->jenis }}</span>
                                        </td>
                                        <td>
                                            @if($m->approval_status === 'approved')
                                                <span class="sbadge sbadge-green"><i class="fas fa-circle" style="font-size:.4rem;"></i> Approved</span>
                                            @elseif($m->approval_status === 'pending')
                                                <span class="sbadge sbadge-yellow"><i class="fas fa-circle" style="font-size:.4rem;"></i> Pending</span>
                                            @else
                                                <span class="sbadge sbadge-red"><i class="fas fa-circle" style="font-size:.4rem;"></i> Ditolak</span>
                                            @endif
                                        </td>
                                        <td style="color:#94a3b8;">{{ $m->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-pen-nib"></i>
                            <p>Kamu belum membuat mading.</p>
                        </div>
                    @endif
                </div>
            </div>

            @else
            {{-- MASYARAKAT BIASA: Tabel pengaduan --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-bullhorn"></i> Pengaduan Terbaru</div>
                    @if(isset($pengaduanTerbaru) && $pengaduanTerbaru->count())
                        <div class="table-responsive">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Hal</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengaduanTerbaru as $p)
                                    <tr>
                                        <td style="font-weight:600; color:#1e293b;">{{ Str::limit($p->judul_pengaduan, 30) }}</td>
                                        <td style="color:#64748b;">{{ Str::limit($p->hal_pengaduan, 25) }}</td>
                                        <td>
                                            @php $st = $p->status; @endphp
                                            @if($st === 'selesai')
                                                <span class="sbadge sbadge-green"><i class="fas fa-circle" style="font-size:.4rem;"></i> Selesai</span>
                                            @elseif($st === 'proses')
                                                <span class="sbadge sbadge-blue"><i class="fas fa-circle" style="font-size:.4rem;"></i> Proses</span>
                                            @elseif($st === 'ditolak')
                                                <span class="sbadge sbadge-red"><i class="fas fa-circle" style="font-size:.4rem;"></i> Ditolak</span>
                                            @else
                                                <span class="sbadge sbadge-gray"><i class="fas fa-circle" style="font-size:.4rem;"></i> Dikirim</span>
                                            @endif
                                        </td>
                                        <td style="color:#94a3b8;">{{ \Carbon\Carbon::parse($p->tanggal_pengaduan)->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-bullhorn"></i>
                            <p>Belum ada pengaduan.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Kolom kanan: Dokumen Diterima (sama semua sub-role) --}}
        <div class="col-lg-5">
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider">
                        <i class="fas fa-folder-open"></i> Dokumen Diterima
                        @if(($dokumenBelumDibaca ?? 0) > 0)
                            <span class="unread-dot"></span>
                            <span style="font-size:.7rem; color:#ef4444; margin-left:2px;">{{ $dokumenBelumDibaca }} baru</span>
                        @endif
                    </div>
                    @if($dokumenDiterima->count())
                        <div class="table-responsive">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Judul Dokumen</th>
                                        <th>Izin</th>
                                        <th>Baca</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokumenDiterima as $dp)
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:#1e293b;">
                                                {{ Str::limit($dp->dokumen?->judul ?? '-', 28) }}
                                            </div>
                                            <div style="font-size:.7rem; color:#94a3b8;">
                                                {{ $dp->created_at->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                                @if($dp->izin_lihat)
                                                    <span class="sbadge sbadge-blue" title="Boleh lihat"><i class="fas fa-eye"></i></span>
                                                @endif
                                                @if($dp->izin_download)
                                                    <span class="sbadge sbadge-green" title="Boleh download"><i class="fas fa-download"></i></span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($dp->sudah_dibaca)
                                                <span class="sbadge sbadge-gray"><i class="fas fa-check"></i> Dibaca</span>
                                            @else
                                                <span class="sbadge sbadge-red"><i class="fas fa-circle" style="font-size:.4rem;"></i> Baru</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>Belum ada dokumen yang diterima.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- end row --}}

</div>
@endsection

@section('scripts')
<script>
    // Auto-hilangkan alert setelah 4 detik
    setTimeout(function () {
        document.querySelectorAll('.alert').forEach(function (el) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        });
    }, 4000);
</script>
@endsection
