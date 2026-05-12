@extends('layouts.user.user')

@section('title', 'Detail Pengaduan – ' . Str::limit($pengaduan->judul_pengaduan, 40))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent: #e53e3e; --accent-light: #fff5f5; }

    .container { padding-left: 28px; padding-right: 24px; }

    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: var(--accent);
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; background: var(--accent-light); color: var(--accent); }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; }
    .ph-breadcrumb { display: flex; align-items: center; gap: 4px; list-style: none; padding: 0; margin: 4px 0 0; }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: var(--accent); text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    .section-card { border: none; border-radius: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
    .section-card .card-body { padding: 22px 24px; }
    .section-divider {
        border-left: 4px solid var(--accent); background: #f8f9fa;
        padding: 7px 13px; border-radius: 0 6px 6px 0;
        font-weight: 700; font-size: .82rem; color: var(--accent);
        display: flex; align-items: center; gap: 8px; margin-bottom: 1.1rem;
    }

    .detail-row { display: flex; gap: 8px; margin-bottom: 12px; font-size: .85rem; }
    .detail-label { font-weight: 600; color: #475569; min-width: 160px; flex-shrink: 0; }
    .detail-value { color: #334155; }

    .badge-pending  { background: #fef9c3; color: #854d0e; }
    .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai  { background: #dcfce7; color: #15803d; }
    .badge-ditolak  { background: #fee2e2; color: #991b1b; }
    .badge { font-size: .78rem; font-weight: 600; padding: 5px 12px; border-radius: 20px; }

    /* Lampiran grid */
    .lampiran-grid { display: flex; flex-wrap: wrap; gap: 12px; }
    .lampiran-card {
        width: 100px; height: 100px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; overflow: hidden;
        background: #f1f5f9; display: flex; align-items: center;
        justify-content: center; position: relative; cursor: pointer;
        transition: transform .2s, box-shadow .2s;
    }
    .lampiran-card:hover { transform: scale(1.04); box-shadow: 0 4px 14px rgba(0,0,0,.12); }
    .lampiran-card img { width: 100%; height: 100%; object-fit: cover; }
    .lampiran-card .file-thumb {
        display: flex; flex-direction: column; align-items: center;
        gap: 4px; font-size: .65rem; color: #64748b; text-align: center; padding: 8px;
    }
    .lampiran-card .file-thumb i { font-size: 2rem; color: #4f46e5; }
    .lampiran-card .file-type-badge {
        position: absolute; bottom: 4px; right: 4px;
        background: rgba(0,0,0,.55); color: #fff;
        font-size: .55rem; padding: 2px 5px; border-radius: 4px;
    }

    /* Balasan box */
    .balasan-box {
        background: #f0fdf4; border-left: 4px solid #16a34a;
        border-radius: 0 10px 10px 0; padding: 14px 16px;
        font-size: .85rem; color: #166534; line-height: 1.6;
    }
    .balasan-box .balasan-meta { font-size: .75rem; color: #4ade80; margin-bottom: 6px; }

    /* Belum ada balasan */
    .no-balasan {
        background: #f8fafc; border-radius: 10px; padding: 16px;
        text-align: center; font-size: .82rem; color: #94a3b8;
    }

    .btn-cancel { border-radius: 10px; font-size: .85rem; border: 1.5px solid #e2e8f0; color: #64748b; padding: 9px 20px; }
    .btn-edit-action {
        background: #fef9c3; color: #854d0e; border: none;
        border-radius: 10px; font-size: .85rem; padding: 9px 20px; font-weight: 600;
        transition: all .2s;
    }
    .btn-edit-action:hover { background: #d97706; color: #fff; }

    /* Lightbox */
    #lightbox-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.88); z-index: 9999;
        align-items: center; justify-content: center;
    }
    #lightbox-overlay.active { display: flex; }
    #lightbox-img { max-width: 90vw; max-height: 88vh; border-radius: 10px; }
    #lightbox-close {
        position: absolute; top: 16px; right: 20px;
        background: none; border: none; color: #fff;
        font-size: 1.6rem; cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Pengaduan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li><span class="bc-active">Detail</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($pengaduan->status === 'pending')
                <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}"
                   class="btn btn-edit-action btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kiri --}}
        <div class="col-lg-8">

            {{-- Info Pengaduan --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-file-alt"></i> Informasi Pengaduan</div>

                    <div class="detail-row">
                        <span class="detail-label">Judul</span>
                        <span class="detail-value fw-semibold">{{ $pengaduan->judul_pengaduan }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Hal Pengaduan</span>
                        <span class="detail-value">{{ $pengaduan->hal_pengaduan }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-value">
                            {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span>
                            <span class="badge badge-{{ $pengaduan->status }}">
                                @if($pengaduan->status === 'pending')     <i class="fas fa-clock me-1"></i>Pending
                                @elseif($pengaduan->status === 'diproses') <i class="fas fa-spinner me-1"></i>Diproses
                                @elseif($pengaduan->status === 'selesai')  <i class="fas fa-check me-1"></i>Selesai
                                @else                                      <i class="fas fa-ban me-1"></i>Ditolak
                                @endif
                            </span>
                        </span>
                    </div>

                    <hr style="border-color:#f1f5f9;margin:14px 0;">

                    <div class="mb-2" style="font-size:.82rem;font-weight:600;color:#475569;">Deskripsi</div>
                    <div style="font-size:.87rem;color:#334155;line-height:1.75;white-space:pre-line;">{{ $pengaduan->deskripsi }}</div>
                </div>
            </div>

            {{-- Lokasi --}}
            @if($pengaduan->alamat || $pengaduan->latitude)
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Lokasi Kejadian</div>

                    @if($pengaduan->alamat)
                    <div class="detail-row">
                        <span class="detail-label">Alamat</span>
                        <span class="detail-value">{{ $pengaduan->alamat }}</span>
                    </div>
                    @endif

                    @if($pengaduan->latitude && $pengaduan->longitude)
                    <div class="detail-row">
                        <span class="detail-label">Koordinat</span>
                        <span class="detail-value">
                            {{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}
                            <a href="https://maps.google.com/?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}"
                               target="_blank" style="font-size:.75rem;color:#0369a1;margin-left:6px;">
                                <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                            </a>
                        </span>
                    </div>

                    {{-- Peta Leaflet --}}
                    <div id="map-show" style="width:100%;height:280px;border-radius:10px;border:1.5px solid #e2e8f0;margin-top:10px;z-index:0;"></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Lampiran --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider">
                        <i class="fas fa-paperclip"></i> Lampiran
                        <span style="font-weight:400;color:#94a3b8;font-size:.75rem;">
                            ({{ $pengaduan->lampiran_pengaduan->count() }} file)
                        </span>
                    </div>

                    @if($pengaduan->lampiran_pengaduan->count() > 0)
                        <div class="lampiran-grid">
                            @foreach($pengaduan->lampiran_pengaduan as $lmp)
                                @if($lmp->tipe === 'gambar')
                                    <div class="lampiran-card"
                                         onclick="openLightbox('{{ asset('storage/' . $lmp->path) }}')">
                                        <img src="{{ asset('storage/' . $lmp->path) }}" alt="Lampiran">
                                        <span class="file-type-badge">
                                            {{ strtoupper(pathinfo($lmp->path, PATHINFO_EXTENSION)) }}
                                        </span>
                                    </div>
                                @else
                                    <a href="{{ asset('storage/' . $lmp->path) }}" target="_blank"
                                       class="lampiran-card" style="text-decoration:none;">
                                        <div class="file-thumb">
                                            <i class="fas fa-file-alt"></i>
                                            <span>{{ strtoupper(pathinfo($lmp->path, PATHINFO_EXTENSION)) }}</span>
                                            <small style="font-size:.55rem;">{{ Str::limit(basename($lmp->path), 14) }}</small>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="no-balasan">
                            <i class="fas fa-paperclip me-2"></i>Tidak ada lampiran.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Balasan --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-reply"></i> Balasan Petugas</div>

                    @if($pengaduan->balasanpengaduan)
                        <div class="balasan-box">
                            <div class="balasan-meta">
                                <i class="fas fa-user-shield me-1"></i>
                                Dibalas pada {{ $pengaduan->balasanpengaduan->created_at?->translatedFormat('d M Y, H:i') }}
                            </div>
                            {{ $pengaduan->balasanpengaduan->isi_balasan ?? $pengaduan->balasanpengaduan->balasan }}
                        </div>
                    @else
                        <div class="no-balasan">
                            <i class="fas fa-hourglass-half me-2"></i>
                            Belum ada balasan dari petugas. Harap tunggu proses ditinjau.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Kanan --}}
        <div class="col-lg-4">
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-info-circle"></i> Ringkasan</div>
                    <table style="font-size:.82rem;color:#64748b;width:100%;border-collapse:separate;border-spacing:0 8px;">
                        <tr>
                            <td style="font-weight:600;color:#475569;width:45%;">ID</td>
                            <td>#{{ $pengaduan->id_pengaduan }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Status</td>
                            <td>
                                <span class="badge badge-{{ $pengaduan->status }}" style="font-size:.72rem;">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Lampiran</td>
                            <td>{{ $pengaduan->lampiran_pengaduan->count() }} file</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Dibuat</td>
                            <td>{{ $pengaduan->created_at?->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Diperbarui</td>
                            <td>{{ $pengaduan->updated_at?->translatedFormat('d M Y') }}</td>
                        </tr>
                    </table>

                    @if($pengaduan->status === 'pending')
                        <hr style="border-color:#f1f5f9;">
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}"
                               class="btn btn-edit-action text-center">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Pengaduan
                            </a>
                            <button type="button" class="btn btn-sm btn-confirm-hapus"
                                    style="background:#fee2e2;color:#991b1b;border:none;border-radius:10px;font-size:.83rem;padding:8px;"
                                    data-id="{{ $pengaduan->id_pengaduan }}"
                                    data-judul="{{ $pengaduan->judul_pengaduan }}">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Pengaduan
                            </button>
                            <form id="form-hapus-{{ $pengaduan->id_pengaduan }}"
                                  action="{{ route('pengaduan.destroy', $pengaduan->id_pengaduan) }}"
                                  method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox-overlay" onclick="closeLightbox()">
    <button id="lightbox-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
    <img id="lightbox-img" src="" alt="Preview Lampiran">
</div>
@endsection

@section('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-overlay').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox-overlay').classList.remove('active');
    document.getElementById('lightbox-img').src = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

document.querySelectorAll('.btn-confirm-hapus').forEach(btn => {
    btn.addEventListener('click', function () {
        const id    = this.dataset.id;
        const judul = this.dataset.judul;
        swal({
            title: 'Hapus Pengaduan?',
            text: `"${judul}" akan dihapus permanen beserta semua lampirannya.`,
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
            dangerMode: true,
        }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
    });
});
</script>

@if($pengaduan->latitude && $pengaduan->longitude)
{{-- Leaflet hanya dimuat jika ada koordinat --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const lat = {{ $pengaduan->latitude }};
    const lng = {{ $pengaduan->longitude }};

    const map = L.map('map-show', { zoomControl: true, scrollWheelZoom: false })
                 .setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
    });

    L.marker([lat, lng], { icon: redIcon })
     .addTo(map)
     .bindPopup(`
        <div style="font-size:.82rem;line-height:1.5;">
            <b style="color:#e53e3e;">📍 Lokasi Pengaduan</b><br>
            @if($pengaduan->alamat)
                {{ $pengaduan->alamat }}<br>
            @endif
            <small style="color:#64748b;">{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}</small>
        </div>
     `)
     .openPopup();
})();
</script>
@endif
@endsection
