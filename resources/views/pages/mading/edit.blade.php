@extends('layouts.user.user')

@section('title', 'Edit Mading – ' . Str::limit($mading->judul, 40))

@php
    $isSekolah    = Auth::user()->isAdminSekolah();
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

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:20px 22px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    .note-editor.note-frame { border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }
    .note-editor.note-frame .note-toolbar { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .note-editor.note-frame.focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-shadow); }
    .note-editor .note-editable { font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; min-height:300px; }

    .gambar-wrap { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px; display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s; background:#fafbfc; position:relative; }
    .gambar-wrap:hover { border-color:var(--accent); }
    #gambar-preview { width:100%; height:100%; object-fit:cover; }
    .gambar-placeholder { text-align:center; color:#94a3b8; position:absolute; }
    .gambar-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .gambar-placeholder div { font-size:.78rem; }

    /* ── Lampiran existing ── */
    .lampiran-existing { display:flex; flex-direction:column; gap:5px; margin-bottom:10px; }
    .lampiran-item { display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:.78rem; color:#475569; }
    .lamp-icon { width:24px; height:24px; border-radius:5px; display:flex; align-items:center; justify-content:center; font-size:.68rem; flex-shrink:0; }
    .lamp-icon.image { background:#e0f2fe; color:#0369a1; }
    .lamp-icon.pdf   { background:#fee2e2; color:#dc2626; }
    .lamp-icon.video { background:#f3e8ff; color:#7c3aed; }
    .lamp-icon.other { background:#f1f5f9; color:#64748b; }
    .lamp-name { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .btn-del-lamp { background:none; border:none; color:#dc2626; font-size:.75rem; cursor:pointer; padding:0 2px; transition:color .15s; flex-shrink:0; }
    .btn-del-lamp:hover { color:#991b1b; }
    .lamp-deleting { opacity:.4; pointer-events:none; }

    /* ── Lampiran queue (baru) ── */
    .lampiran-dropzone { border:2px dashed #ced4da; border-radius:10px; padding:12px; text-align:center; cursor:pointer; transition:border-color .2s,background .2s; background:#fafbfc; font-size:.8rem; color:#94a3b8; }
    .lampiran-dropzone:hover, .lampiran-dropzone.drag-over { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }
    .lampiran-dropzone i { font-size:1.3rem; display:block; margin-bottom:3px; }
    .lampiran-queue { display:flex; flex-direction:column; gap:5px; margin-top:8px; }
    .lampiran-queue-item { display:flex; align-items:center; gap:8px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:6px 10px; font-size:.78rem; color:#166534; }
    .lampiran-queue-item .lq-icon { width:24px; height:24px; border-radius:5px; display:flex; align-items:center; justify-content:center; font-size:.68rem; flex-shrink:0; background:#dcfce7; color:#16a34a; }
    .lampiran-queue-item .lq-name { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .lampiran-queue-item .lq-size { font-size:.72rem; color:#4ade80; flex-shrink:0; }
    .lampiran-queue-item .lq-remove { background:none; border:none; color:#dc2626; cursor:pointer; padding:0 2px; font-size:.72rem; flex-shrink:0; }
    .lampiran-count { font-size:.74rem; color:#7c3aed; font-weight:600; margin-top:4px; }

    /* ── Submit overlay ── */
    .submit-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:9999; flex-direction:column; align-items:center; justify-content:center; }
    .submit-overlay.show { display:flex; }
    .submit-box { background:#fff; border-radius:14px; padding:28px 32px; text-align:center; box-shadow:0 8px 32px rgba(0,0,0,.15); min-width:260px; }
    .submit-spinner { width:40px; height:40px; border:4px solid #e2e8f0; border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite; margin:0 auto 12px; }
    @keyframes spin { to { transform:rotate(360deg); } }
    .submit-box p { margin:0; font-size:.85rem; color:#475569; font-weight:600; }
    .submit-box small { color:#94a3b8; font-size:.75rem; }

    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; white-space:nowrap; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }
    .pending-notice { background:#fef9c3; border-radius:10px; padding:10px 14px; font-size:.83rem; color:#854d0e; display:flex; align-items:flex-start; gap:8px; }
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; font-weight:600; }
    .info-badge { display:inline-flex; align-items:center; gap:5px; background:#f1f5f9; border-radius:8px; padding:4px 10px; font-size:.75rem; color:#64748b; }
    .btn-update { background:linear-gradient(135deg,var(--accent),color-mix(in srgb,var(--accent) 80%,black)); border:none; border-radius:10px; font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-update:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Submit overlay --}}
    <div class="submit-overlay" id="submit-overlay">
        <div class="submit-box">
            <div class="submit-spinner"></div>
            <p>Sedang menyimpan perubahan…</p>
            <small id="overlay-info">Mohon tunggu, jangan tutup halaman ini</small>
        </div>
    </div>

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Mading</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('mading.index') }}">Mading</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="info-badge">
                <i class="fas fa-calendar-alt"></i>
                Dibuat: {{ $mading->created_at?->translatedFormat('d M Y') }}
            </span>
            <a href="{{ route('mading.index') }}" class="btn btn-cancel btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(!$isSekolah && $mading->approval_status === 'approved')
        <div class="pending-notice mb-3">
            <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
            <span>Setelah disimpan, mading ini akan kembali berstatus <strong>Menunggu Persetujuan</strong> untuk ditinjau ulang.</span>
        </div>
    @endif

    @if($mading->approval_status === 'rejected' && $mading->alasan_penolakan)
        <div class="alert mb-3" style="background:#fee2e2;color:#991b1b;border-radius:12px;font-size:.84rem;">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Mading Ditolak:</strong> {{ $mading->alasan_penolakan }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#fee2e2;color:#991b1b;" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mading.update', $mading->id_mading) }}" method="POST"
          enctype="multipart/form-data" id="form-mading">
        @csrf
        @method('PUT')

        {{-- Input tersembunyi untuk file queue baru --}}
        <input type="file" id="lampiran-real" name="lampiran[]" multiple class="d-none" accept="image/*,application/pdf,video/*">

        <div class="row g-3">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-pen"></i> Isi Mading</div>
                        <div class="mb-3">
                            <label for="judul">Judul <span class="required-mark">*</span></label>
                            <input type="text" id="judul" name="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $mading->judul) }}"
                                placeholder="Judul mading…" maxlength="255">
                            <div id="judul-counter" class="char-counter">0 / 255</div>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="isi">Isi Mading <span class="required-mark">*</span></label>
                            <textarea id="isi" name="isi"
                                class="form-control summernote @error('isi') is-invalid @enderror">{!! old('isi', $mading->isi) !!}</textarea>
                            @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Gambar Sampul --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-image"></i> Gambar Sampul</div>
                        <label for="gambar-input" class="gambar-wrap">
                            @if($mading->gambar)
                                <img id="gambar-preview" src="{{ asset('storage/' . $mading->gambar) }}" alt="Gambar saat ini">
                            @else
                                <img id="gambar-preview" src="" alt="" style="display:none;">
                            @endif
                            <div class="gambar-placeholder" id="gambar-placeholder"
                                style="{{ $mading->gambar ? 'display:none;' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>Klik untuk ganti gambar<br><small>JPG, PNG, WEBP – maks 3 MB</small></div>
                            </div>
                        </label>
                        <input type="file" id="gambar-input" name="gambar"
                            class="d-none @error('gambar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp">
                        @if($mading->gambar)
                            <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
                        @endif
                        @error('gambar')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-paperclip"></i> Lampiran</div>

                        {{-- Lampiran yang sudah tersimpan --}}
                        @if($mading->lampiran->isNotEmpty())
                            <div class="form-text mb-2" style="color:#475569;font-weight:600;">Lampiran tersimpan:</div>
                            <div class="lampiran-existing" id="lampiran-existing">
                                @foreach($mading->lampiran as $lamp)
                                    @php
                                        $iconCls = match($lamp->tipe) {
                                            'image' => 'image', 'pdf' => 'pdf', 'video' => 'video', default => 'other'
                                        };
                                        $iconFa = match($lamp->tipe) {
                                            'image' => 'fa-image', 'pdf' => 'fa-file-pdf', 'video' => 'fa-film', default => 'fa-file'
                                        };
                                    @endphp
                                    <div class="lampiran-item" id="lamp-item-{{ $lamp->id }}">
                                        <span class="lamp-icon {{ $iconCls }}"><i class="fas {{ $iconFa }}"></i></span>
                                        <span class="lamp-name" title="{{ basename($lamp->path) }}">{{ basename($lamp->path) }}</span>
                                        <button type="button" class="btn-del-lamp"
                                            data-id="{{ $lamp->id }}" title="Hapus lampiran ini">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <hr style="border-color:#f1f5f9;margin:10px 0;">
                        @endif

                        {{-- Drop zone lampiran baru --}}
                        <div class="form-text mb-1" style="color:#475569;font-weight:600;">Tambah lampiran baru:</div>
                        <label for="lampiran-trigger" class="lampiran-dropzone" id="lampiran-dropzone">
                            <i class="fas fa-plus-circle"></i>
                            Klik atau seret file ke sini<br>
                            <small>Bisa tambah berkali-kali · Maks 5 MB/file</small>
                        </label>
                        <input type="file" id="lampiran-trigger" class="d-none" accept="image/*,application/pdf,video/*" multiple>

                        {{-- Antrian file baru --}}
                        <div class="lampiran-queue" id="lampiran-queue"></div>
                        <div class="lampiran-count d-none" id="lampiran-count"></div>

                        @error('lampiran.*')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Pengaturan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-cog"></i> Pengaturan</div>
                        <div class="mb-3">
                            <label for="jenis">Jenis Mading</label>
                            <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                @foreach($jenisOptions as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('jenis', $mading->jenis) === $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($isSekolah)
                            <div class="mb-3">
                                <label>Status Publikasi</label>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach(['publish' => ['Publish','#16a34a','check-circle'], 'draft' => ['Draft','#64748b','file']] as $val => [$lbl, $col, $ico])
                                    <div>
                                        <input type="radio" id="status_{{ $val }}" name="status"
                                            value="{{ $val }}" class="status-radio"
                                            {{ old('status', $mading->status) === $val ? 'checked' : '' }}>
                                        <label for="status_{{ $val }}" class="status-label">
                                            <i class="fas fa-{{ $ico }}" style="color:{{ $col }}"></i> {{ $lbl }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label>Status Saat Ini</label>
                                <div class="mt-1">
                                    @if($mading->approval_status === 'approved')
                                        <span class="badge" style="background:#dcfce7;color:#15803d;font-size:.8rem;padding:5px 12px;">
                                            <i class="fas fa-check-circle me-1"></i>Tayang
                                        </span>
                                    @elseif($mading->approval_status === 'pending')
                                        <span class="badge" style="background:#fef9c3;color:#854d0e;font-size:.8rem;padding:5px 12px;">
                                            <i class="fas fa-clock me-1"></i>Menunggu Persetujuan
                                        </span>
                                    @elseif($mading->approval_status === 'rejected')
                                        <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:.8rem;padding:5px 12px;">
                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                        </span>
                                    @else
                                        <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:.8rem;padding:5px 12px;">
                                            <i class="fas fa-file me-1"></i>Draft
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-update w-100" id="btn-submit">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
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
(function () {

    // ── 1. Hapus lampiran existing via AJAX ──────────────────────
    document.querySelectorAll('.btn-del-lamp').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id   = this.dataset.id;
            var item = document.getElementById('lamp-item-' + id);
            if (!confirm('Hapus lampiran ini?')) return;

            item.classList.add('lamp-deleting');
            fetch('/mading/lampiran/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    item.remove();
                    // Sembunyikan wrapper jika sudah kosong
                    var existing = document.getElementById('lampiran-existing');
                    if (existing && existing.children.length === 0) {
                        existing.previousElementSibling?.remove(); // label "tersimpan"
                        existing.nextElementSibling?.remove();     // hr
                        existing.remove();
                    }
                } else {
                    item.classList.remove('lamp-deleting');
                    alert('Gagal menghapus lampiran.');
                }
            })
            .catch(function () {
                item.classList.remove('lamp-deleting');
                alert('Terjadi kesalahan jaringan.');
            });
        });
    });

    // ── 2. File queue untuk lampiran baru ───────────────────────
    var fileQueue = [];

    function getFileIcon(file) {
        var t = file.type;
        if (t.startsWith('image/'))       return 'fa-image';
        if (t === 'application/pdf')      return 'fa-file-pdf';
        if (t.startsWith('video/'))       return 'fa-film';
        return 'fa-file';
    }

    function formatSize(bytes) {
        return bytes < 1024 * 1024
            ? (bytes / 1024).toFixed(0) + ' KB'
            : (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function renderQueue() {
        var list  = document.getElementById('lampiran-queue');
        var count = document.getElementById('lampiran-count');
        list.innerHTML = '';

        fileQueue.forEach(function (file, idx) {
            var item = document.createElement('div');
            item.className = 'lampiran-queue-item';
            item.innerHTML =
                '<span class="lq-icon"><i class="fas ' + getFileIcon(file) + '"></i></span>' +
                '<span class="lq-name" title="' + file.name + '">' + file.name + '</span>' +
                '<span class="lq-size">' + formatSize(file.size) + '</span>' +
                '<button type="button" class="lq-remove" data-idx="' + idx + '" title="Hapus dari antrian">' +
                    '<i class="fas fa-times"></i></button>';
            list.appendChild(item);
        });

        count.textContent = fileQueue.length > 0
            ? fileQueue.length + ' file baru siap dikirim'
            : '';
        count.classList.toggle('d-none', fileQueue.length === 0);

        list.querySelectorAll('.lq-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fileQueue.splice(parseInt(this.dataset.idx), 1);
                renderQueue();
            });
        });
    }

    function addFiles(files) {
        Array.from(files).forEach(function (file) {
            var dup = fileQueue.some(function (f) {
                return f.name === file.name && f.size === file.size;
            });
            if (dup) return;
            if (file.size > 5 * 1024 * 1024) {
                alert(file.name + ' melebihi batas 5 MB dan dilewati.');
                return;
            }
            fileQueue.push(file);
        });
        renderQueue();
    }

    document.getElementById('lampiran-trigger').addEventListener('change', function () {
        addFiles(this.files);
        this.value = '';
    });

    var dropzone = document.getElementById('lampiran-dropzone');
    dropzone.addEventListener('dragover',  function (e) { e.preventDefault(); dropzone.classList.add('drag-over'); });
    dropzone.addEventListener('dragleave', function ()  { dropzone.classList.remove('drag-over'); });
    dropzone.addEventListener('drop',      function (e) {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
        addFiles(e.dataTransfer.files);
    });

    // ── 3. Submit: masukkan queue ke input tersembunyi ────────────
    document.getElementById('form-mading').addEventListener('submit', function () {
        document.getElementById('submit-overlay').classList.add('show');

        if (fileQueue.length > 0) {
            document.getElementById('overlay-info').textContent =
                'Mengunggah ' + fileQueue.length + ' lampiran baru, mohon tunggu…';
            var dt = new DataTransfer();
            fileQueue.forEach(function (f) { dt.items.add(f); });
            document.getElementById('lampiran-real').files = dt.files;
        }
    });

})();
</script>
@endsection
