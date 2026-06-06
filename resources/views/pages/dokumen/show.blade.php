@extends('layouts.user.user')

@section('title', 'Dokumen – ' . Str::limit($dokumen->judul, 50))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,h4,h5,label,.btn { font-family:'Plus Jakarta Sans',sans-serif; }
    :root { --accent:#1a73e8; --accent-light:#e8f0fe; --accent-shadow:rgba(26,115,232,.15); }
    .container { padding-left:28px; padding-right:24px; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 22px;
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

    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px;
        border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent);
        display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    .meta-row { display:flex; gap:6px; align-items:flex-start; margin-bottom:10px; font-size:.83rem; }
    .meta-label { min-width:110px; color:#94a3b8; font-weight:600; }
    .meta-value { color:#1e293b; }

    /* Lampiran */
    .lampiran-item { display:flex; align-items:center; gap:12px; padding:10px 14px;
        border:1.5px solid #e2e8f0; border-radius:10px; margin-bottom:8px;
        background:#fafbfc; transition:border-color .15s; }
    .lampiran-item:hover { border-color:var(--accent); }
    .lamp-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center;
        justify-content:center; font-size:.9rem; flex-shrink:0; }
    .lamp-icon.file { background:#dbeafe; color:#1a73e8; }
    .lamp-icon.foto { background:#fce7f3; color:#ec4899; }
    .lamp-name { font-size:.83rem; font-weight:600; color:#1e293b;
        overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; }
    .lamp-size { font-size:.72rem; color:#94a3b8; }

    /* Foto grid */
    .foto-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:8px; }
    .foto-grid img { width:100%; height:100px; object-fit:cover; border-radius:10px;
        cursor:pointer; transition:transform .15s,box-shadow .15s; }
    .foto-grid img:hover { transform:scale(1.03); box-shadow:0 4px 12px rgba(0,0,0,.15); }

    /* Link */
    .link-item { display:flex; align-items:center; gap:10px; padding:10px 14px;
        border:1.5px solid #e2e8f0; border-radius:10px; margin-bottom:8px; background:#fafbfc; }
    .link-item a { font-size:.83rem; color:var(--accent); font-weight:600; text-decoration:none;
        overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; }
    .link-item a:hover { text-decoration:underline; }

    /* Penerima table */
    .penerima-table { width:100%; font-size:.82rem; border-collapse:separate; border-spacing:0 4px; }
    .penerima-table th { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px;
        color:#64748b; padding:6px 12px; }
    .penerima-table td { padding:8px 12px; background:#f8fafc; }
    .penerima-table tr td:first-child { border-radius:8px 0 0 8px; }
    .penerima-table tr td:last-child  { border-radius:0 8px 8px 0; }
    .badge-izin { font-size:.7rem; padding:2px 8px; border-radius:20px; font-weight:700; }
    .badge-yes { background:#dcfce7; color:#16a34a; }
    .badge-no  { background:#fee2e2; color:#dc2626; }
    .badge-baca-ya { background:#dcfce7; color:#16a34a; font-size:.7rem; padding:2px 8px; border-radius:20px; font-weight:700; }
    .badge-baca-no { background:#e0f2fe; color:#0369a1; font-size:.7rem; padding:2px 8px; border-radius:20px; font-weight:700; }

    .btn-sm-action { width:28px; height:28px; border-radius:7px; display:inline-flex; align-items:center;
        justify-content:center; font-size:.72rem; border:none; transition:all .15s; }
    .btn-download { background:#e0f2fe; color:#0369a1; }
    .btn-download:hover { background:#0369a1; color:#fff; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-file-alt"></i></div>
            <div>
                <h5 class="ph-title">{{ Str::limit($dokumen->judul, 70) }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dokumen.index') }}">Dokumen Bersama</a></li>
                    <li><span class="bc-active">Detail</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($isPengirim)
                <a href="{{ route('dokumen.edit', $dokumen->id) }}"
                   class="btn btn-sm btn-warning" style="border-radius:10px;font-size:.82rem;font-weight:600;">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('dokumen.index') }}" class="btn btn-sm btn-light"
               style="border-radius:10px;border:1.5px solid #e2e8f0;font-size:.82rem;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert mb-3" style="background:#dcfce7;color:#166534;border-radius:10px;font-size:.83rem;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-3">

        {{-- ── Kolom Utama ── --}}
        <div class="col-lg-8">

            {{-- Info Dokumen --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-info-circle"></i> Informasi Dokumen</div>
                    <div class="meta-row">
                        <span class="meta-label">Pengirim</span>
                        <span class="meta-value fw-semibold">
                            {{ $dokumen->pengirim?->namaTampil() ?? '-' }}
                            <span style="font-size:.72rem;background:#e8f0fe;color:#1a73e8;
                                border-radius:20px;padding:2px 8px;margin-left:4px;font-weight:700;">
                                {{ $dokumen->pengirim?->getRoleLabel() }}
                            </span>
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Tanggal Kirim</span>
                        <span class="meta-value">{{ $dokumen->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Status</span>
                        <span class="meta-value">
                            @if($dokumen->status === 'aktif')
                                <span style="font-size:.75rem;background:#dcfce7;color:#16a34a;
                                    border-radius:20px;padding:2px 10px;font-weight:700;">
                                    <i class="fas fa-check me-1"></i>Aktif
                                </span>
                            @else
                                <span style="font-size:.75rem;background:#f1f5f9;color:#64748b;
                                    border-radius:20px;padding:2px 10px;font-weight:700;">
                                    <i class="fas fa-archive me-1"></i>Arsip
                                </span>
                            @endif
                        </span>
                    </div>
                    @if($penerima && !$isPengirim)
                    <div class="meta-row">
                        <span class="meta-label">Izin Anda</span>
                        <span class="meta-value d-flex gap-2">
                            <span class="badge-izin {{ $penerima->izin_lihat ? 'badge-yes' : 'badge-no' }}">
                                <i class="fas fa-eye me-1"></i>{{ $penerima->izin_lihat ? 'Lihat' : 'Tidak Bisa Lihat' }}
                            </span>
                            <span class="badge-izin {{ $penerima->izin_download ? 'badge-yes' : 'badge-no' }}">
                                <i class="fas fa-download me-1"></i>{{ $penerima->izin_download ? 'Unduh' : 'Tidak Bisa Unduh' }}
                            </span>
                        </span>
                    </div>
                    @endif

                    @if($dokumen->deskripsi)
                        <div class="mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
                            <label class="mb-2" style="font-size:.8rem;font-weight:700;color:#64748b;">
                                Deskripsi / Pesan
                            </label>
                            <div style="font-size:.87rem;color:#334155;white-space:pre-wrap;line-height:1.7;">
                                {{ $dokumen->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Lampiran File --}}
            @if($dokumen->lampiranFile->count())
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider">
                        <i class="fas fa-paperclip"></i>
                        Lampiran File ({{ $dokumen->lampiranFile->count() }})
                    </div>
                    @foreach($dokumen->lampiranFile as $lamp)
                        <div class="lampiran-item">
                            <div class="lamp-icon file"><i class="fas fa-file-alt"></i></div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="lamp-name">{{ $lamp->nama_asli }}</div>
                                <div class="lamp-size">{{ $lamp->ukuranMb() }} MB</div>
                            </div>
                            @if($isPengirim || $penerima?->bolehDownload())
                                <a href="{{ route('dokumen.lampiran.download', $lamp->id) }}"
                                   class="btn btn-sm-action btn-download" title="Unduh">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Foto --}}
            @if($dokumen->lampiranFoto->count())
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider">
                        <i class="fas fa-images"></i>
                        Foto ({{ $dokumen->lampiranFoto->count() }})
                    </div>
                    <div class="foto-grid">
                        @foreach($dokumen->lampiranFoto as $foto)
                            <img src="{{ $foto->url() }}" alt="{{ $foto->nama_asli }}"
                                 data-url="{{ $foto->url() }}" class="foto-thumb">
                        @endforeach
                    </div>
                    @if($isPengirim || $penerima?->bolehDownload())
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($dokumen->lampiranFoto as $foto)
                                <a href="{{ route('dokumen.lampiran.download', $foto->id) }}"
                                   class="btn btn-sm" style="border-radius:8px;background:#e0f2fe;
                                       color:#0369a1;font-size:.75rem;border:none;">
                                    <i class="fas fa-download me-1"></i>{{ Str::limit($foto->nama_asli, 20) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Link eksternal --}}
            @if($dokumen->links->count())
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider">
                        <i class="fas fa-link"></i>
                        Link Eksternal ({{ $dokumen->links->count() }})
                    </div>
                    @foreach($dokumen->links as $link)
                        <div class="link-item">
                            <span style="width:32px;height:32px;border-radius:8px;background:#e8f0fe;
                                color:#1a73e8;display:flex;align-items:center;justify-content:center;
                                flex-shrink:0;font-size:.85rem;">
                                <i class="{{ $link->isGoogleDrive() ? 'fab fa-google-drive' : 'fas fa-external-link-alt' }}"></i>
                            </span>
                            <a href="{{ $link->url }}" target="_blank" rel="noopener">
                                {{ $link->labelTampil() }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── Kolom Kanan ── --}}
        <div class="col-lg-4">

            {{-- Hanya pengirim yang bisa melihat daftar penerima lengkap --}}
            @if($isPengirim)
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-users"></i>
                            Penerima ({{ $dokumen->penerimas->count() }})
                            <span style="margin-left:auto;font-size:.72rem;color:#94a3b8;font-weight:400;">
                                {{ $dokumen->jumlahSudahDibaca() }}/{{ $dokumen->jumlahPenerima() }} dibaca
                            </span>
                        </div>
                        <table class="penerima-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Lihat</th>
                                    <th>Unduh</th>
                                    <th>Baca</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumen->penerimas as $p)
                                    <tr>
                                        <td>
                                            <div style="font-size:.82rem;font-weight:600;color:#1e293b;">
                                                {{ $p->user?->namaTampil() ?? '-' }}
                                            </div>
                                            <div style="font-size:.7rem;color:#94a3b8;">
                                                {{ $p->user?->getRoleLabel() }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-izin {{ $p->izin_lihat ? 'badge-yes' : 'badge-no' }}">
                                                {{ $p->izin_lihat ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-izin {{ $p->izin_download ? 'badge-yes' : 'badge-no' }}">
                                                {{ $p->izin_download ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($p->sudah_dibaca)
                                                <span class="badge-baca-ya">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            @else
                                                <span class="badge-baca-no">–</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{--
                    Penerima hanya melihat izin milik mereka sendiri,
                    tidak tahu siapa saja penerima lainnya.
                --}}
                @if($penerima)
                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider">
                                <i class="fas fa-key"></i> Izin Dokumen Anda
                            </div>

                            <div style="display:flex;flex-direction:column;gap:10px;">

                                {{-- Izin Lihat --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;
                                    background:#f8fafc;border-radius:10px;padding:10px 14px;
                                    border:1.5px solid #e2e8f0;">
                                    <div style="display:flex;align-items:center;gap:8px;font-size:.83rem;
                                        font-weight:600;color:#334155;">
                                        <i class="fas fa-eye" style="color:#1a73e8;width:16px;"></i>
                                        Lihat Dokumen
                                    </div>
                                    @if($penerima->izin_lihat)
                                        <span class="badge-izin badge-yes">
                                            <i class="fas fa-check me-1"></i>Diizinkan
                                        </span>
                                    @else
                                        <span class="badge-izin badge-no">
                                            <i class="fas fa-times me-1"></i>Tidak
                                        </span>
                                    @endif
                                </div>

                                {{-- Izin Download --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;
                                    background:#f8fafc;border-radius:10px;padding:10px 14px;
                                    border:1.5px solid #e2e8f0;">
                                    <div style="display:flex;align-items:center;gap:8px;font-size:.83rem;
                                        font-weight:600;color:#334155;">
                                        <i class="fas fa-download" style="color:#1a73e8;width:16px;"></i>
                                        Unduh Lampiran
                                    </div>
                                    @if($penerima->izin_download)
                                        <span class="badge-izin badge-yes">
                                            <i class="fas fa-check me-1"></i>Diizinkan
                                        </span>
                                    @else
                                        <span class="badge-izin badge-no">
                                            <i class="fas fa-times me-1"></i>Tidak
                                        </span>
                                    @endif
                                </div>

                                {{-- Status Baca --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;
                                    background:#f8fafc;border-radius:10px;padding:10px 14px;
                                    border:1.5px solid #e2e8f0;">
                                    <div style="display:flex;align-items:center;gap:8px;font-size:.83rem;
                                        font-weight:600;color:#334155;">
                                        <i class="fas fa-book-open" style="color:#1a73e8;width:16px;"></i>
                                        Status Baca
                                    </div>
                                    @if($penerima->sudah_dibaca)
                                        <span class="badge-baca-ya">
                                            <i class="fas fa-check-double me-1"></i>
                                            Dibaca {{ $penerima->dibaca_at?->translatedFormat('d M, H:i') }}
                                        </span>
                                    @else
                                        <span class="badge-baca-no">Baru dibuka</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>

    </div>
</div>

{{-- Lightbox foto (simple) --}}
<div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);
    z-index:9999;align-items:center;justify-content:center;">
    <img id="lb-img" src="" style="max-width:90vw;max-height:90vh;border-radius:8px;">
    <button onclick="document.getElementById('lightbox').style.display='none'"
        style="position:absolute;top:16px;right:20px;background:none;border:none;
            color:#fff;font-size:1.5rem;cursor:pointer;">✕</button>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.foto-thumb').forEach(img => {
    img.addEventListener('click', () => {
        document.getElementById('lb-img').src = img.dataset.url;
        document.getElementById('lightbox').style.display = 'flex';
    });
});
</script>
@endsection
