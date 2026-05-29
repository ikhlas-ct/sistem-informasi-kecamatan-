@extends('layouts.user.user')

@section('title', 'Tulis Mading')

@php
    $isSekolah = Auth::user()->role === 'sekolah';
    $jenisOptions = [
        'karya'      => 'Karya Siswa',
        'pengumuman' => 'Pengumuman',
        'berita'     => 'Berita',
        'cerpen'     => 'Cerpen',
        'puisi'      => 'Puisi',
        'lainnya'    => 'Lainnya',
    ];
@endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }
    :root { --accent: #7c3aed; --accent-light: #f5f3ff; --accent-shadow: rgba(124,58,237,.15); }

    .container { padding-left:28px; padding-right:24px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 22px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.05rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Section cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    /* ── Form controls ── */
    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#b0bec5; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Summernote ── */
    .note-editor.note-frame { border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }
    .note-editor.note-frame .note-toolbar { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .note-editor.note-frame.focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-shadow); }
    .note-editor .note-editable { font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; min-height:320px; }

    /* ── Drop zone gambar ── */
    .gambar-drop-zone { width:100%; height:190px; border:2px dashed #ced4da; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s,background .2s; background:#fafbfc; position:relative; }
    .gambar-drop-zone:hover, .gambar-drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #gambar-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:none; }
    .gambar-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .gambar-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .gambar-placeholder span { font-size:.78rem; }

    /* ── Lampiran list ── */
    .lampiran-list { display:flex; flex-direction:column; gap:6px; margin-top:10px; }
    .lampiran-item { display:flex; align-items:center; justify-content:space-between; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:.78rem; color:#475569; }
    .lampiran-item .btn-remove-lampiran { background:none; border:none; color:#dc2626; font-size:.75rem; cursor:pointer; padding:0; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; white-space:nowrap; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    /* ── Pending notice ── */
    .pending-notice { background:#fef9c3; border-radius:10px; padding:10px 14px; font-size:.83rem; color:#854d0e; display:flex; align-items:flex-start; gap:8px; }

    /* ── Char counter ── */
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; font-weight:600; }

    /* ── Buttons ── */
    .btn-submit { background:linear-gradient(135deg,var(--accent),color-mix(in srgb,var(--accent) 80%,black)); border:none; border-radius:10px; font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h5 class="ph-title">Tulis Mading</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('mading.index') }}">Mading</a></li>
                    <li><span class="bc-active">Tulis</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('mading.index') }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Notif siswa: akan pending --}}
    @if(!$isSekolah)
        <div class="pending-notice mb-3">
            <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
            <span>Mading yang Anda tulis akan berstatus <strong>Menunggu Persetujuan</strong> dan akan ditayangkan setelah disetujui pihak sekolah.</span>
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert" style="background:#fee2e2;color:#991b1b;" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mading.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-pen"></i> Isi Mading
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="judul">Judul <span class="required-mark">*</span></label>
                            <input type="text" id="judul" name="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul') }}"
                                placeholder="Tulis judul mading…" maxlength="255">
                            <div id="judul-counter" class="char-counter">0 / 255</div>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Isi --}}
                        <div class="mb-3">
                            <label for="isi">Isi Mading <span class="required-mark">*</span></label>
                            <textarea id="isi" name="isi"
                                class="form-control summernote @error('isi') is-invalid @enderror">{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Gambar Sampul --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-image"></i> Gambar Sampul
                        </div>
                        <label for="gambar-input" id="gambar-drop-zone" class="gambar-drop-zone">
                            <img id="gambar-preview" src="" alt="Preview">
                            <div class="gambar-placeholder" id="gambar-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret gambar ke sini<br>
                                    <small>JPG, PNG, WEBP – maks 3 MB</small>
                                </span>
                            </div>
                        </label>
                        <input type="file" id="gambar-input" name="gambar"
                            class="d-none @error('gambar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp">
                        @error('gambar')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-paperclip"></i> Lampiran
                        </div>
                        <input type="file" id="lampiran-input" name="lampiran[]"
                            class="form-control @error('lampiran.*') is-invalid @enderror"
                            accept="image/*,application/pdf,video/*" multiple>
                        <div class="form-text">Boleh lebih dari satu file. Maks 5 MB per file.</div>
                        <div class="lampiran-list" id="lampiran-preview-list"></div>
                        @error('lampiran.*')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Pengaturan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-cog"></i> Pengaturan
                        </div>

                        {{-- Jenis --}}
                        <div class="mb-3">
                            <label for="jenis">Jenis Mading <span class="required-mark">*</span></label>
                            <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                @foreach($jenisOptions as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('jenis') === $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status: hanya sekolah --}}
                        @if($isSekolah)
                            <div class="mb-3">
                                <label>Status Publikasi <span class="required-mark">*</span></label>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach(['publish' => ['Publish','#16a34a','check-circle'], 'draft' => ['Draft','#64748b','file']] as $val => [$lbl, $col, $ico])
                                        <div>
                                            <input type="radio" id="status_{{ $val }}" name="status"
                                                value="{{ $val }}" class="status-radio"
                                                {{ old('status','publish') === $val ? 'checked' : '' }}>
                                            <label for="status_{{ $val }}" class="status-label">
                                                <i class="fas fa-{{ $ico }}" style="color:{{ $col }}"></i> {{ $lbl }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="fas fa-save me-2"></i>
                        {{ $isSekolah ? 'Simpan Mading' : 'Kirim untuk Disetujui' }}
                    </button>
                    <a href="{{ route('mading.index') }}" class="btn btn-cancel text-center">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection

@section('scripts')
@include('pages.mading.mading_summernote')
<script>
    // Preview daftar lampiran yang dipilih
    document.getElementById('lampiran-input')?.addEventListener('change', function () {
        const list = document.getElementById('lampiran-preview-list');
        list.innerHTML = '';
        Array.from(this.files).forEach(file => {
            list.innerHTML += `
                <div class="lampiran-item">
                    <span><i class="fas fa-file me-1"></i>${file.name}</span>
                    <small class="text-muted">${(file.size / 1024).toFixed(0)} KB</small>
                </div>`;
        });
    });
</script>
@endsection
