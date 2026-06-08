@extends('layouts.user.user')

@section('title', 'Edit Pengaduan')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,h4,h5,label,.btn { font-family:'Plus Jakarta Sans',sans-serif; }
    :root { --accent:#e96c1a; --accent-light:#fff4ed; --accent-shadow:rgba(233,108,26,.15); }
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

    /* Lampiran existing */
    .existing-item { display:flex; align-items:center; gap:10px; padding:9px 12px;
        border:1.5px solid #e2e8f0; border-radius:10px; margin-bottom:6px; background:#fafbfc;
        transition:border-color .15s; }
    .existing-item.marked-delete { border-color:#fca5a5; background:#fff5f5; opacity:.6; }
    .existing-item .lamp-icon { width:34px; height:34px; border-radius:8px; display:flex;
        align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
    .lamp-icon.gambar { background:#fce7f3; color:#ec4899; }
    .lamp-icon.file   { background:#dbeafe; color:#1a73e8; }
    .existing-item .lamp-name { font-size:.82rem; font-weight:600; color:#1e293b;
        overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; }
    .existing-item .lamp-size { font-size:.72rem; color:#94a3b8; white-space:nowrap; }
    .btn-rm-existing { width:26px; height:26px; border-radius:7px; background:#fee2e2; color:#dc2626;
        border:none; display:flex; align-items:center; justify-content:center;
        font-size:.72rem; cursor:pointer; flex-shrink:0; transition:all .15s; }
    .btn-rm-existing:hover { background:#dc2626; color:#fff; }

    /* Drop zone */
    .drop-zone { border:2px dashed #ced4da; border-radius:12px; padding:20px; text-align:center;
        cursor:pointer; background:#fafbfc; transition:border-color .2s,background .2s; }
    .drop-zone:hover, .drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    .drop-zone-icon { font-size:1.5rem; color:#94a3b8; display:block; margin-bottom:6px; }
    .drop-zone-text { font-size:.78rem; color:#94a3b8; }

    .file-preview-list { margin-top:10px; display:flex; flex-wrap:wrap; gap:8px; }
    .file-chip { display:inline-flex; align-items:center; gap:6px; background:#f1f5f9;
        border-radius:8px; padding:5px 10px; font-size:.75rem; color:#475569; max-width:220px; }
    .file-chip .chip-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .file-chip .chip-rm { cursor:pointer; color:#94a3b8; font-size:.65rem; }
    .file-chip .chip-rm:hover { color:#dc2626; }

    #map { height:240px; border-radius:10px; border:1.5px solid #e2e8f0; }

    /* Tombol lokasi sekarang */
    .btn-locate { display:flex; align-items:center; gap:7px; width:100%; justify-content:center;
        border:1.5px solid var(--accent); border-radius:10px; padding:7px 14px;
        font-size:.8rem; font-weight:600; color:var(--accent); background:#fff;
        cursor:pointer; transition:all .2s; margin-bottom:10px; }
    .btn-locate:hover { background:var(--accent-light); }
    .btn-locate:disabled { opacity:.6; cursor:not-allowed; }
    .btn-locate .spinner { display:none; width:14px; height:14px; border:2px solid var(--accent);
        border-top-color:transparent; border-radius:50%; animation:spin .7s linear infinite; }
    .btn-locate.loading .spinner { display:inline-block; }
    .btn-locate.loading .loc-icon { display:none; }
    @keyframes spin { to { transform:rotate(360deg); } }

    .btn-submit { background:linear-gradient(135deg,var(--accent),#c45a10); border:none; border-radius:10px;
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
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Pengaduan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li><a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}">Detail</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}" class="btn btn-cancel btn-sm">
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

    {{-- ⚠️ enctype="multipart/form-data" WAJIB ada agar file bisa diupload --}}
    <form action="{{ route('pengaduan.update', $pengaduan->id_pengaduan) }}"
          method="POST" enctype="multipart/form-data" id="formEdit">
        @csrf
        @method('PUT')

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
                                   value="{{ old('judul_pengaduan', $pengaduan->judul_pengaduan) }}">
                            @error('judul_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hal_pengaduan">Hal / Perihal <span class="required-mark">*</span></label>
                            <input type="text" id="hal_pengaduan" name="hal_pengaduan"
                                   class="form-control @error('hal_pengaduan') is-invalid @enderror"
                                   value="{{ old('hal_pengaduan', $pengaduan->hal_pengaduan) }}">
                            @error('hal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi">Deskripsi Lengkap <span class="required-mark">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pengaduan">Tanggal Kejadian <span class="required-mark">*</span></label>
                            <input type="date" id="tanggal_pengaduan" name="tanggal_pengaduan"
                                   class="form-control @error('tanggal_pengaduan') is-invalid @enderror"
                                   value="{{ old('tanggal_pengaduan', \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('Y-m-d')) }}">
                            @error('tanggal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-paperclip"></i> Lampiran
                            <span style="font-weight:400;color:#94a3b8;font-size:.75rem;margin-left:4px;">
                                ({{ $pengaduan->lampiran_pengaduan->count() }} file tersimpan)
                            </span>
                        </div>

                        {{-- Lampiran yang sudah ada --}}
                        @if($pengaduan->lampiran_pengaduan->count())
                            <p style="font-size:.78rem;color:#64748b;margin-bottom:8px;">
                                Centang kotak merah untuk menghapus lampiran:
                            </p>
                            @foreach($pengaduan->lampiran_pengaduan as $lmp)
                                @php
                                    $namaFile = basename($lmp->path);
                                    $isGambar = $lmp->tipe === 'gambar';
                                    $urlFile  = asset('storage/' . $lmp->path);
                                @endphp
                                <div class="existing-item" id="existing-{{ $lmp->id }}">
                                    <div class="lamp-icon {{ $isGambar ? 'gambar' : 'file' }}">
                                        <i class="fas {{ $isGambar ? 'fa-image' : 'fa-file-alt' }}"></i>
                                    </div>

                                    {{-- Jika gambar, tampilkan thumbnail kecil --}}
                                    @if($isGambar)
                                        <img src="{{ $urlFile }}"
                                             style="width:40px;height:40px;object-fit:cover;border-radius:6px;flex-shrink:0;"
                                             onerror="this.style.display='none'">
                                    @endif

                                    <span class="lamp-name">{{ $namaFile }}</span>
                                    <a href="{{ $urlFile }}" target="_blank"
                                       class="btn btn-sm"
                                       style="background:#e0f2fe;color:#0369a1;border-radius:7px;
                                              font-size:.72rem;padding:3px 8px;white-space:nowrap;">
                                        <i class="fas fa-external-link-alt me-1"></i>Buka
                                    </a>

                                    {{-- ⚠️ name="hapus_lampiran[]" agar controller bisa baca array --}}
                                    <input type="checkbox"
                                           name="hapus_lampiran[]"
                                           value="{{ $lmp->id }}"
                                           id="del-{{ $lmp->id }}"
                                           class="cb-hapus"
                                           style="width:16px;height:16px;accent-color:#dc2626;flex-shrink:0;">
                                    <label for="del-{{ $lmp->id }}"
                                           class="btn-rm-existing mb-0"
                                           title="Centang untuk hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p style="font-size:.78rem;color:#94a3b8;margin-bottom:10px;">Belum ada lampiran tersimpan.</p>
                        @endif

                        {{-- Tambah lampiran baru --}}
                        <div style="margin-top:14px;">
                            <p style="font-size:.78rem;font-weight:600;color:#475569;margin-bottom:8px;">
                                Tambah File Baru:
                            </p>
                            <input type="file" id="fileInput" name="lampiran[]" multiple
                                   accept=".jpg,.jpeg,.webp,.png,.pdf,.doc,.docx"
                                   style="display:none;">
                            <div class="drop-zone" id="dropZone">
                                <i class="fas fa-cloud-upload-alt drop-zone-icon"></i>
                                <div class="drop-zone-text">Klik atau seret file baru ke sini</div>
                            </div>
                            <div class="file-preview-list" id="filePreview"></div>
                        </div>

                        @error('lampiran')
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
                        <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Lokasi</div>

                        <div class="mb-3">
                            <label for="alamat">Alamat / Lokasi</label>
                            <textarea id="alamat" name="alamat" rows="2" class="form-control"
                                      placeholder="Tulis alamat kejadian…">{{ old('alamat', $pengaduan->alamat) }}</textarea>
                        </div>

                        <button type="button" class="btn-locate" id="btnLocate">
                            <i class="fas fa-crosshairs loc-icon"></i>
                            <span class="spinner"></span>
                            <span class="loc-label">Gunakan Lokasi Sekarang</span>
                        </button>

                        <div id="map"></div>
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Klik peta untuk pindah pin</div>

                        <input type="hidden" id="latitude"  name="latitude"
                               value="{{ old('latitude',  $pengaduan->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude"
                               value="{{ old('longitude', $pengaduan->longitude) }}">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}"
                       class="btn btn-cancel text-center">Batal</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Peta ─────────────────────────────────────────────────────
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

    // ── Tombol Lokasi Sekarang ────────────────────────────────────
    const btnLocate = document.getElementById('btnLocate');
    btnLocate.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi.'); return;
        }
        btnLocate.classList.add('loading');
        btnLocate.disabled = true;
        btnLocate.querySelector('.loc-label').textContent = 'Mendapatkan lokasi…';

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                document.getElementById('latitude').value  = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
                map.setView([lat, lng], 17);
                if (marker) marker.setLatLng([lat, lng]);
                else marker = L.marker([lat, lng]).addTo(map);
                btnLocate.classList.remove('loading');
                btnLocate.disabled = false;
                btnLocate.querySelector('.loc-label').textContent = 'Lokasi Diperbarui ✓';
                setTimeout(() => {
                    btnLocate.querySelector('.loc-label').textContent = 'Gunakan Lokasi Sekarang';
                }, 3000);
            },
            function (err) {
                btnLocate.classList.remove('loading');
                btnLocate.disabled = false;
                btnLocate.querySelector('.loc-label').textContent = 'Gunakan Lokasi Sekarang';
                const msg = {
                    1: 'Izin lokasi ditolak. Mohon izinkan akses lokasi di browser.',
                    2: 'Lokasi tidak dapat ditentukan.',
                    3: 'Waktu habis. Coba lagi.'
                };
                alert(msg[err.code] || 'Gagal mendapatkan lokasi.');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    });

    // ── Visual feedback centang hapus ─────────────────────────────
    document.querySelectorAll('.cb-hapus').forEach(cb => {
        cb.addEventListener('change', function () {
            const item = document.getElementById('existing-' + this.value);
            if (item) item.classList.toggle('marked-delete', this.checked);
        });
    });

    // ── File upload preview ───────────────────────────────────────
    const dropZone   = document.getElementById('dropZone');
    const fileInput  = document.getElementById('fileInput');
    const previewBox = document.getElementById('filePreview');
    const imageExts  = ['jpg','jpeg','png','webp','gif'];
    let   fileList   = new DataTransfer();

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
        const existingCount = {{ $pengaduan->lampiran_pengaduan->count() }};
        const markedDelete  = document.querySelectorAll('.cb-hapus:checked').length;
        const maxBisa = 10 - (existingCount - markedDelete);

        for (const f of newFiles) {
            if (fileList.files.length >= maxBisa) {
                alert('Total lampiran tidak boleh lebih dari 10 file.'); break;
            }
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
            const chip = document.createElement('div');
            chip.className = 'file-chip';
            chip.innerHTML = `
                <i class="fas ${imageExts.includes(ext) ? 'fa-image' : 'fa-file-alt'}"></i>
                <span class="chip-name">${f.name}</span>
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
