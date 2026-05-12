@extends('layouts.user.user')

@section('title', 'Edit Pengaduan – ' . Str::limit($pengaduan->judul_pengaduan, 40))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --accent: #d97706;
        --accent-light: #fffbeb;
        --accent-shadow: rgba(217,119,6,.15);
    }

    .container { padding-left: 28px; padding-right: 24px; }

    /* ── Page Header ── */
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
    .ph-icon {
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
        background: var(--accent-light); color: var(--accent);
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        list-style: none; padding: 0; margin: 4px 0 0; flex-wrap: wrap;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: var(--accent); text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }
    .info-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f1f5f9; border-radius: 8px; padding: 4px 10px;
        font-size: .75rem; color: #64748b;
    }

    /* ── Section cards ── */
    .section-card { border: none; border-radius: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
    .section-card .card-body { padding: 20px 22px; }
    .section-divider {
        border-left: 4px solid var(--accent); background: #f8f9fa;
        padding: 7px 13px; border-radius: 0 6px 6px 0;
        font-weight: 700; font-size: .82rem; color: var(--accent);
        display: flex; align-items: center; gap: 8px; margin-bottom: 1.1rem;
    }

    /* ── Form controls ── */
    label { font-size: .83rem; font-weight: 600; color: #475569; }
    .required-mark { color: #dc3545; }
    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 8px 12px; color: #334155;
        background: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent); background: #fff;
        box-shadow: 0 0 0 3px var(--accent-shadow);
    }
    .form-control::placeholder { color: #b0bec5; }
    .form-text { font-size: .75rem; color: #94a3b8; margin-top: 4px; }
    .char-counter { font-size: .72rem; color: #94a3b8; text-align: right; margin-top: 3px; }
    .char-counter.warning { color: #d97706; }
    .char-counter.danger  { color: #dc2626; font-weight: 600; }

    /* ── Notices ── */
    .pending-notice {
        background: #fef9c3; border-radius: 10px; padding: 10px 14px;
        font-size: .83rem; color: #854d0e;
        display: flex; align-items: flex-start; gap: 8px;
    }

    /* ── Lampiran yang sudah ada ── */
    .existing-lampiran-grid {
        display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;
    }
    .existing-item {
        position: relative; width: 90px; min-height: 90px;
        border-radius: 10px; overflow: hidden;
        border: 1.5px solid #e2e8f0; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: opacity .2s;
    }
    .existing-item.marked-delete {
        opacity: .35;
        border-color: #dc2626;
    }
    .existing-item img {
        width: 100%; height: 90px; object-fit: cover;
    }
    .existing-item .file-icon {
        display: flex; flex-direction: column; align-items: center;
        gap: 4px; font-size: .65rem; color: #64748b;
        text-align: center; padding: 8px; word-break: break-all;
    }
    .existing-item .file-icon i { font-size: 1.6rem; color: #4f46e5; }
    .existing-item .delete-toggle {
        position: absolute; top: 3px; right: 3px;
        width: 22px; height: 22px; border-radius: 50%;
        background: #dc2626; color: #fff; border: none;
        font-size: .65rem; display: flex; align-items: center;
        justify-content: center; cursor: pointer; transition: transform .15s;
    }
    .existing-item.marked-delete .delete-toggle {
        background: #64748b;
    }
    .existing-item .delete-toggle:hover { transform: scale(1.15); }
    .delete-label {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: rgba(220,38,38,.85); color: #fff;
        font-size: .6rem; text-align: center; padding: 2px 0;
        display: none;
    }
    .existing-item.marked-delete .delete-label { display: block; }

    /* ── Drop Zone & New Previews ── */
    .lampiran-drop-zone {
        width: 100%; min-height: 110px; border: 2px dashed #ced4da;
        border-radius: 12px; display: flex; flex-direction: column;
        align-items: center; justify-content: center; cursor: pointer;
        transition: border-color .2s, background .2s; background: #fafbfc;
        padding: 16px; text-align: center;
    }
    .lampiran-drop-zone:hover, .lampiran-drop-zone.drag-over {
        border-color: var(--accent); background: var(--accent-light);
    }
    .lampiran-drop-zone i { font-size: 1.6rem; color: #94a3b8; margin-bottom: 6px; }
    .lampiran-drop-zone span { font-size: .75rem; color: #94a3b8; }

    #new-preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
    .preview-item {
        position: relative; width: 90px; height: 90px;
        border-radius: 10px; overflow: hidden;
        border: 1.5px solid #e2e8f0; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .preview-item .file-icon {
        display: flex; flex-direction: column; align-items: center;
        gap: 4px; font-size: .65rem; color: #64748b; text-align: center;
        padding: 6px; word-break: break-all;
    }
    .preview-item .file-icon i { font-size: 1.6rem; color: #4f46e5; }
    .preview-item .remove-btn {
        position: absolute; top: 3px; right: 3px;
        width: 20px; height: 20px; border-radius: 50%;
        background: #dc2626; color: #fff; border: none;
        font-size: .6rem; display: flex; align-items: center;
        justify-content: center; cursor: pointer; transition: transform .15s;
    }
    .preview-item .remove-btn:hover { transform: scale(1.15); }

    /* ── Quota bar ── */
    .quota-bar {
        font-size: .75rem; color: #64748b; margin-top: 8px;
        display: flex; align-items: center; gap: 8px;
    }
    .quota-bar .q-track {
        flex: 1; height: 4px; border-radius: 4px;
        background: #e2e8f0; overflow: hidden;
    }
    .quota-bar .q-fill { height: 4px; border-radius: 4px; background: #16a34a; transition: width .3s, background .3s; }

    /* ── Buttons ── */
    .btn-update {
        background: linear-gradient(135deg, var(--accent), #b45309);
        border: none; border-radius: 10px; font-weight: 600;
        font-size: .88rem; padding: 10px 28px; color: #fff; transition: all .2s;
    }
    .btn-update:hover { transform: translateY(-1px); filter: brightness(1.07); color: #fff; }
    .btn-cancel {
        border-radius: 10px; font-size: .85rem;
        border: 1.5px solid #e2e8f0; color: #64748b; padding: 9px 20px;
    }
    .btn-cancel:hover { background: #f8fafc; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Pengaduan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="info-badge">
                <i class="fas fa-calendar-alt"></i>
                Dibuat: {{ $pengaduan->created_at?->translatedFormat('d M Y') }}
            </span>
            <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Notice --}}
    <div class="pending-notice mb-3">
        <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
        <span>Anda hanya dapat mengedit pengaduan yang masih berstatus <strong>Pending</strong>. Centang lampiran lama untuk menghapusnya, atau tambahkan lampiran baru di bawah.</span>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert d-flex align-items-start gap-2 mb-3"
         style="background:#fee2e2;color:#991b1b;border-radius:12px;border:none;font-size:.84rem;">
        <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
        <div>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('pengaduan.update', $pengaduan->id_pengaduan) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Informasi Pengaduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-edit"></i> Informasi Pengaduan
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="judul_pengaduan">Judul Pengaduan <span class="required-mark">*</span></label>
                            <input type="text" id="judul_pengaduan" name="judul_pengaduan"
                                   class="form-control @error('judul_pengaduan') is-invalid @enderror"
                                   value="{{ old('judul_pengaduan', $pengaduan->judul_pengaduan) }}"
                                   placeholder="Tuliskan judul singkat pengaduan…" maxlength="255">
                            <div id="judul-counter" class="char-counter">0 / 255</div>
                            @error('judul_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hal --}}
                        <div class="mb-3">
                            <label for="hal_pengaduan">Hal Pengaduan <span class="required-mark">*</span></label>
                            <input type="text" id="hal_pengaduan" name="hal_pengaduan"
                                   class="form-control @error('hal_pengaduan') is-invalid @enderror"
                                   value="{{ old('hal_pengaduan', $pengaduan->hal_pengaduan) }}"
                                   placeholder="Contoh: Kerusakan Jalan, Pelayanan Publik…" maxlength="255">
                            <div id="hal-counter" class="char-counter">0 / 255</div>
                            @error('hal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi">Deskripsi <span class="required-mark">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="6"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Jelaskan pengaduan Anda secara lengkap…">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-0">
                            <label for="tanggal_pengaduan">Tanggal Pengaduan <span class="required-mark">*</span></label>
                            <input type="date" id="tanggal_pengaduan" name="tanggal_pengaduan"
                                   class="form-control @error('tanggal_pengaduan') is-invalid @enderror"
                                   value="{{ old('tanggal_pengaduan', $pengaduan->tanggal_pengaduan) }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('tanggal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-map-marker-alt"></i> Lokasi Kejadian
                        </div>

                        <div class="mb-3">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat"
                                   class="form-control @error('alamat') is-invalid @enderror"
                                   value="{{ old('alamat', $pengaduan->alamat) }}"
                                   placeholder="Masukkan alamat lokasi kejadian…">
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Peta Leaflet --}}
                        <div class="mb-2">
                            <label>Titik Lokasi di Peta</label>
                            <div class="form-text mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Klik pada peta untuk memindahkan lokasi, atau seret marker yang sudah ada.
                            </div>
                            <div id="map" style="width:100%;height:300px;border-radius:10px;border:1.5px solid #e2e8f0;z-index:0;"></div>
                        </div>

                        {{-- Tombol GPS --}}
                        <button type="button" id="btn-lokasi" class="btn btn-sm mb-3"
                                style="background:#e0f2fe;color:#0369a1;border-radius:8px;font-size:.8rem;">
                            <i class="fas fa-crosshairs me-1"></i> Gunakan Lokasi Saya
                        </button>
                        <span id="lokasi-status" style="font-size:.75rem;color:#94a3b8;margin-left:6px;"></span>

                        {{-- Koordinat readonly --}}
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="latitude">Latitude</label>
                                <input type="number" step="any" id="latitude" name="latitude"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude', $pengaduan->latitude) }}"
                                       placeholder="-0.9471…" readonly
                                       style="background:#f1f5f9;cursor:not-allowed;">
                                @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label for="longitude">Longitude</label>
                                <input type="number" step="any" id="longitude" name="longitude"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude', $pengaduan->longitude) }}"
                                       placeholder="100.3503…" readonly
                                       style="background:#f1f5f9;cursor:not-allowed;">
                                @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-text mt-1">Koordinat terisi otomatis saat Anda klik peta atau gunakan GPS.</div>
                    </div>
                </div>


                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-paperclip"></i> Lampiran
                            <span style="font-weight:400;color:#94a3b8;font-size:.75rem;margin-left:4px;">
                                (Maks. 10 file total)
                            </span>
                        </div>

                        {{-- Lampiran yang sudah ada --}}
                        @if($pengaduan->lampiran_pengaduan->count() > 0)
                            <label class="mb-2" style="font-size:.78rem;color:#64748b;">
                                <i class="fas fa-folder-open me-1"></i>
                                Lampiran saat ini — klik <i class="fas fa-times" style="color:#dc2626;"></i> untuk tandai hapus
                            </label>
                            <div class="existing-lampiran-grid" id="existing-grid">
                                @foreach($pengaduan->lampiran_pengaduan as $lmp)
                                    <div class="existing-item" id="existing-{{ $lmp->id }}" data-id="{{ $lmp->id }}">
                                        @if($lmp->tipe === 'gambar')
                                            <img src="{{ asset('storage/' . $lmp->path) }}" alt="Lampiran">
                                        @else
                                            <div class="file-icon">
                                                <i class="fas fa-file-alt"></i>
                                                <span>{{ strtoupper(pathinfo($lmp->path, PATHINFO_EXTENSION)) }}</span>
                                                <small style="font-size:.55rem;">{{ Str::limit(basename($lmp->path), 12) }}</small>
                                            </div>
                                        @endif
                                        <button type="button" class="delete-toggle"
                                                onclick="toggleDeleteLampiran({{ $lmp->id }})"
                                                title="Tandai hapus">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="delete-label">Akan dihapus</div>
                                        {{-- Hidden input untuk tandai hapus --}}
                                        <input type="checkbox" name="hapus_lampiran[]"
                                               value="{{ $lmp->id }}"
                                               id="del-{{ $lmp->id }}"
                                               class="d-none delete-check">
                                    </div>
                                @endforeach
                            </div>
                            <div style="font-size:.75rem;color:#94a3b8;margin-bottom:12px;">
                                <span id="existing-count">{{ $pengaduan->lampiran_pengaduan->count() }}</span> file saat ini ·
                                <span id="marked-count" style="color:#dc2626;">0</span> ditandai hapus
                            </div>
                        @else
                            <p style="font-size:.8rem;color:#94a3b8;" class="mb-3">Belum ada lampiran.</p>
                        @endif

                        {{-- Tambah Lampiran Baru --}}
                        <label style="font-size:.78rem;color:#64748b;" class="mb-2">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Lampiran Baru
                        </label>

                        <label for="lampiran-input" class="lampiran-drop-zone" id="lampiran-drop-zone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>
                                Klik atau seret file ke sini<br>
                                <small>Gambar: JPG, JPEG, WEBP, PNG (maks. 10 MB/file) · File: PDF, DOC, DOCX</small>
                            </span>
                        </label>
                        <input type="file" id="lampiran-input" name="lampiran[]"
                               class="d-none"
                               accept="image/jpeg,image/jpg,image/webp,image/png,application/pdf,.doc,.docx"
                               multiple>

                        @error('lampiran')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                        @error('lampiran.*')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror

                        {{-- Quota Bar --}}
                        <div class="quota-bar">
                            <span id="quota-text">Total: 0 / 10 file</span>
                            <div class="q-track">
                                <div class="q-fill" id="quota-fill" style="width:0%;"></div>
                            </div>
                        </div>

                        {{-- Preview lampiran baru --}}
                        <div id="new-preview-container"></div>
                    </div>
                </div>

            </div>{{-- end kolom kiri --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Tombol Aksi --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </div>
                        <p style="font-size:.82rem;color:#64748b;line-height:1.6;">
                            Perubahan akan disimpan. Pastikan informasi sudah benar sebelum menyimpan.
                        </p>
                        <div class="d-flex gap-2 flex-column mt-2">
                            <button type="submit" class="btn btn-update w-100">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel text-center">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Info Status --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-info-circle"></i> Info Pengaduan
                        </div>
                        <table style="font-size:.8rem;color:#64748b;width:100%;border-collapse:separate;border-spacing:0 6px;">
                            <tr>
                                <td style="font-weight:600;color:#475569;width:45%;">Status</td>
                                <td>
                                    <span class="badge"
                                          style="background:#fef9c3;color:#854d0e;font-size:.75rem;padding:4px 10px;border-radius:20px;">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:#475569;">Dibuat</td>
                                <td>{{ $pengaduan->created_at?->translatedFormat('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:#475569;">Diperbarui</td>
                                <td>{{ $pengaduan->updated_at?->translatedFormat('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Panduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-lightbulb"></i> Panduan Lampiran
                        </div>
                        <ul style="font-size:.8rem;color:#64748b;padding-left:1.1rem;line-height:1.8;margin:0;">
                            <li>Klik ikon <i class="fas fa-times" style="color:#dc2626;"></i> pada lampiran lama untuk menandai hapus.</li>
                            <li>Upload file baru melalui area drop zone.</li>
                            <li>Total lampiran maks. <strong>10 file</strong>.</li>
                            <li>Format gambar: JPG, JPEG, WEBP, PNG.</li>
                            <li>Ukuran tiap file maks. <strong>10 MB</strong>.</li>
                        </ul>
                    </div>
                </div>

            </div>{{-- end kolom kanan --}}
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const MAX_FILES  = 10;
    const MAX_SIZE   = 10 * 1024 * 1024;
    const ACCEPT_IMG = ['image/jpeg','image/jpg','image/webp','image/png'];
    const ACCEPT_DOC = ['application/pdf','application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    let newFiles    = [];
    let markedCount = 0;

    const existingTotal = {{ $pengaduan->lampiran_pengaduan->count() }};
    const input         = document.getElementById('lampiran-input');
    const dropZone      = document.getElementById('lampiran-drop-zone');
    const newPreview    = document.getElementById('new-preview-container');
    const quotaText     = document.getElementById('quota-text');
    const quotaFill     = document.getElementById('quota-fill');
    const markedSpan    = document.getElementById('marked-count');

    // ── Toggle delete existing ──
    window.toggleDeleteLampiran = function (id) {
        const el      = document.getElementById(`existing-${id}`);
        const check   = document.getElementById(`del-${id}`);
        const isMarked = el.classList.toggle('marked-delete');
        check.checked = isMarked;
        markedCount   = document.querySelectorAll('.delete-check:checked').length;
        if (markedSpan) markedSpan.textContent = markedCount;
        updateQuota();
    };

    // ── Drag & drop ──
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        addFiles([...e.dataTransfer.files]);
    });
    input.addEventListener('change', () => {
        addFiles([...input.files]);
        input.value = '';
    });

    function addFiles(incoming) {
        let errs = [];
        incoming.forEach(file => {
            const currentTotal = (existingTotal - markedCount) + newFiles.length;
            if (currentTotal >= MAX_FILES) {
                errs.push(`Maksimal ${MAX_FILES} file total.`);
                return;
            }
            if (file.size > MAX_SIZE) {
                errs.push(`"${file.name}" melebihi 10 MB.`);
                return;
            }
            const allowed = [...ACCEPT_IMG, ...ACCEPT_DOC];
            if (!allowed.includes(file.type)) {
                errs.push(`"${file.name}" format tidak didukung.`);
                return;
            }
            const isDupe = newFiles.some(f => f.name === file.name && f.size === file.size);
            if (!isDupe) newFiles.push(file);
        });
        if (errs.length) alert(errs.join('\n'));
        renderNewPreviews();
        syncInput();
        updateQuota();
    }

    function renderNewPreviews() {
        newPreview.innerHTML = '';
        newFiles.forEach((file, idx) => {
            const item = document.createElement('div');
            item.className = 'preview-item';

            if (ACCEPT_IMG.includes(file.type)) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                item.appendChild(img);
            } else {
                const icon = document.createElement('div');
                icon.className = 'file-icon';
                const ext = file.name.split('.').pop().toUpperCase();
                icon.innerHTML = `<i class="fas fa-file-alt"></i><span>${ext}</span><small style="font-size:.55rem;">${trimName(file.name,14)}</small>`;
                item.appendChild(icon);
            }

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'remove-btn';
            btn.innerHTML = '<i class="fas fa-times"></i>';
            btn.addEventListener('click', () => {
                newFiles.splice(idx, 1);
                renderNewPreviews();
                syncInput();
                updateQuota();
            });
            item.appendChild(btn);
            newPreview.appendChild(item);
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        newFiles.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function updateQuota() {
        const kept  = existingTotal - markedCount;
        const total = kept + newFiles.length;
        const pct   = (total / MAX_FILES) * 100;
        quotaText.textContent = `Total: ${total} / ${MAX_FILES} file`;
        quotaFill.style.width      = Math.min(pct, 100) + '%';
        quotaFill.style.background = pct >= 100 ? '#dc2626' : pct >= 70 ? '#d97706' : '#16a34a';
    }

    function trimName(name, max) {
        return name.length > max ? name.substring(0, max) + '…' : name;
    }

    // ── Char counters ──
    function initCounter(inputId, counterId, max) {
        const el = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (!el || !counter) return;
        const update = () => {
            const len = el.value.length;
            counter.textContent = `${len} / ${max}`;
            counter.className = 'char-counter' + (len > max * .9 ? ' danger' : len > max * .75 ? ' warning' : '');
        };
        el.addEventListener('input', update);
        update();
    }
    initCounter('judul_pengaduan', 'judul-counter', 255);
    initCounter('hal_pengaduan',   'hal-counter',   255);

    // ── Geolocation ──
    document.getElementById('btn-lokasi')?.addEventListener('click', () => {
        const status = document.getElementById('lokasi-status');
        if (!navigator.geolocation) { status.textContent = 'Browser tidak mendukung geolokasi.'; return; }
        status.textContent = 'Mengambil lokasi…';
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            setMarker(lat, lng);
            map.setView([lat, lng], 17);
            status.textContent = 'Lokasi berhasil diambil ✓';
            status.style.color = '#16a34a';
        }, () => { status.textContent = 'Gagal mengambil lokasi. Pastikan izin lokasi diaktifkan.'; status.style.color = '#dc2626'; });
    });

    // init quota
    updateQuota();
})();
</script>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const savedLat = {{ old('latitude', $pengaduan->latitude ?? -0.9471) }};
    const savedLng = {{ old('longitude', $pengaduan->longitude ?? 100.3503) }};
    const hasCoord = {{ ($pengaduan->latitude && $pengaduan->longitude) ? 'true' : 'false' }};

    const map  = L.map('map').setView([savedLat, savedLng], hasCoord ? 15 : 10);
    let marker = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
    });

    window.setMarker = function (lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: redIcon, draggable: true }).addTo(map);
        marker.bindPopup('<b>Lokasi Pengaduan</b><br>Seret untuk menyesuaikan posisi.').openPopup();
        document.getElementById('latitude').value  = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);

        marker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            document.getElementById('latitude').value  = pos.lat.toFixed(8);
            document.getElementById('longitude').value = pos.lng.toFixed(8);
        });
    };

    map.on('click', function (e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // Pasang marker dari data yang sudah ada
    if (hasCoord) setMarker(savedLat, savedLng);
})();
</script>
@endsection
