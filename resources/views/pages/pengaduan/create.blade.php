@extends('layouts.user.user')

@section('title', 'Buat Pengaduan Baru')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,h4,h5,label,.btn { font-family:'Plus Jakarta Sans',sans-serif; }
    :root { --accent:#1a73e8; --accent-light:#e8f0fe; --accent-shadow:rgba(26,115,232,.15); }
    .container { padding-left:28px; padding-right:24px; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 22px;
        display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
        border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center;
        justify-content:center; font-size:1.05rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
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

    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
        padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff;
        box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* Drop zone lampiran */
    .drop-zone { border:2px dashed #ced4da; border-radius:12px; padding:24px; text-align:center;
        cursor:pointer; background:#fafbfc; transition:border-color .2s,background .2s; }
    .drop-zone:hover, .drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    .drop-zone-icon { font-size:1.8rem; color:#94a3b8; display:block; margin-bottom:8px; }
    .drop-zone-text { font-size:.8rem; color:#94a3b8; }
    .drop-zone-hint { font-size:.72rem; color:#b0bec5; margin-top:4px; }

    .file-preview-list { margin-top:12px; display:flex; flex-wrap:wrap; gap:8px; }
    .file-chip { display:inline-flex; align-items:center; gap:6px; background:#f1f5f9;
        border-radius:8px; padding:6px 10px; font-size:.75rem; color:#475569; max-width:220px; }
    .file-chip i { color:#64748b; flex-shrink:0; }
    .file-chip .chip-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .file-chip .chip-rm { cursor:pointer; color:#94a3b8; font-size:.65rem; flex-shrink:0; }
    .file-chip .chip-rm:hover { color:#dc2626; }
    .file-chip.is-image i { color:#ec4899; }

    /* Map */
    #map { height:260px; border-radius:10px; border:1.5px solid #e2e8f0; }

    .btn-submit { background:linear-gradient(135deg,#1a73e8,#1557b0); border:none; border-radius:10px;
        font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0;
        color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-bullhorn"></i></div>
            <div>
                <h5 class="ph-title">Buat Pengaduan Baru</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li><span class="bc-active">Buat Baru</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="alert mb-3" style="background:#fee2e2;color:#991b1b;border-radius:10px;">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- ⚠️ enctype="multipart/form-data" wajib ada agar file bisa diupload --}}
    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" id="formPengaduan">
        @csrf
        <div class="row g-3">

            {{-- Kolom Kiri --}}
            <div class="col-lg-8">

                {{-- Info Pengaduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-alt"></i> Informasi Pengaduan</div>

                        <div class="mb-3">
                            <label for="judul_pengaduan">Judul Pengaduan <span class="required-mark">*</span></label>
                            <input type="text" id="judul_pengaduan" name="judul_pengaduan"
                                   class="form-control @error('judul_pengaduan') is-invalid @enderror"
                                   value="{{ old('judul_pengaduan') }}"
                                   placeholder="Ringkasan singkat pengaduan Anda">
                            @error('judul_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hal_pengaduan">Hal / Perihal <span class="required-mark">*</span></label>
                            <input type="text" id="hal_pengaduan" name="hal_pengaduan"
                                   class="form-control @error('hal_pengaduan') is-invalid @enderror"
                                   value="{{ old('hal_pengaduan') }}"
                                   placeholder="Contoh: Kerusakan Jalan, Sampah, dll.">
                            @error('hal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi">Deskripsi Lengkap <span class="required-mark">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Jelaskan masalah yang Anda adukan secara lengkap…">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pengaduan">Tanggal Kejadian <span class="required-mark">*</span></label>
                            <input type="date" id="tanggal_pengaduan" name="tanggal_pengaduan"
                                   class="form-control @error('tanggal_pengaduan') is-invalid @enderror"
                                   value="{{ old('tanggal_pengaduan', date('Y-m-d')) }}">
                            @error('tanggal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-paperclip"></i> Lampiran (opsional)</div>

                        {{-- Input file tersembunyi, dipicu oleh drop zone --}}
                        {{-- name="lampiran[]" agar Laravel menerima array file --}}
                        <input type="file" id="fileInput" name="lampiran[]" multiple
                               accept=".jpg,.jpeg,.webp,.png,.pdf,.doc,.docx"
                               style="display:none;">

                        <div class="drop-zone" id="dropZone">
                            <i class="fas fa-cloud-upload-alt drop-zone-icon"></i>
                            <div class="drop-zone-text">Klik atau seret file ke sini</div>
                            <div class="drop-zone-hint">JPG, PNG, WEBP, PDF, DOC, DOCX · Maks 10 MB/file · Maks 10 file</div>
                        </div>

                        <div class="file-preview-list" id="filePreview"></div>

                        @error('lampiran')
                            <div class="text-danger mt-2" style="font-size:.8rem;">{{ $message }}</div>
                        @enderror
                        @error('lampiran.*')
                            <div class="text-danger mt-2" style="font-size:.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-lg-4">

                {{-- Lokasi --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Lokasi (opsional)</div>

                        <div class="mb-3">
                            <label for="alamat">Alamat / Lokasi</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                      class="form-control"
                                      placeholder="Tulis alamat kejadian…">{{ old('alamat') }}</textarea>
                        </div>

                        <div id="map"></div>
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Klik peta untuk pin lokasi</div>

                        <input type="hidden" id="latitude"  name="latitude"  value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pengaduan
                    </button>
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel text-center">Batal</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
{{-- Leaflet map (CDN, tidak butuh auth) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Peta Leaflet ─────────────────────────────────────────────
    const lat0 = parseFloat(document.getElementById('latitude').value)  || -0.9471;
    const lng0 = parseFloat(document.getElementById('longitude').value) || 100.4172;
    const map  = L.map('map').setView([lat0, lng0], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let marker = null;
    if (document.getElementById('latitude').value) {
        marker = L.marker([lat0, lng0]).addTo(map);
    }

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        document.getElementById('latitude').value  = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        if (marker) marker.setLatLng([lat, lng]);
        else marker = L.marker([lat, lng]).addTo(map);
    });

    // ── File upload preview ───────────────────────────────────────
    const dropZone   = document.getElementById('dropZone');
    const fileInput  = document.getElementById('fileInput');
    const previewBox = document.getElementById('filePreview');
    const imageExts  = ['jpg','jpeg','png','webp','gif'];
    let   fileList   = new DataTransfer();   // ← kunci: kita kelola sendiri

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        addFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', () => addFiles(fileInput.files));

    function addFiles(newFiles) {
        for (const f of newFiles) {
            if (fileList.files.length >= 10) { alert('Maksimal 10 file lampiran.'); break; }
            fileList.items.add(f);
        }
        fileInput.files = fileList.files;
        renderChips();
    }

    function renderChips() {
        previewBox.innerHTML = '';
        for (let i = 0; i < fileList.files.length; i++) {
            const f   = fileList.files[i];
            const ext = f.name.split('.').pop().toLowerCase();
            const isImg = imageExts.includes(ext);

            const chip = document.createElement('div');
            chip.className = 'file-chip' + (isImg ? ' is-image' : '');
            chip.innerHTML = `
                <i class="fas ${isImg ? 'fa-image' : 'fa-file-alt'}"></i>
                <span class="chip-name">${f.name}</span>
                <span class="chip-size text-muted" style="font-size:.65rem;white-space:nowrap;">
                    ${(f.size / 1024).toFixed(0)} KB
                </span>
                <span class="chip-rm">✕</span>`;
            chip.querySelector('.chip-rm').addEventListener('click', () => {
                const dt = new DataTransfer();
                for (let j = 0; j < fileList.files.length; j++) {
                    if (j !== i) dt.items.add(fileList.files[j]);
                }
                fileList = dt;
                fileInput.files = fileList.files;
                renderChips();
            });
            previewBox.appendChild(chip);
        }
    }
});
</script>
@endsection
