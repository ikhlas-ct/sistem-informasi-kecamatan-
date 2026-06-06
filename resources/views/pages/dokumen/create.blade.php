@extends('layouts.user.user')

@section('title', 'Kirim Dokumen Bersama')

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

    /* ── Penerima ── */
    .penerima-toolbar { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px; }
    .btn-grup { font-size:.8rem; font-weight:600; border-radius:20px; padding:5px 14px;
        border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; cursor:pointer; }
    .btn-grup:hover, .btn-grup.active { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }
    .nagari-select-wrap { display:none; margin-bottom:10px; }
    .nagari-select-wrap.show { display:block; }

    .user-list-box { border:1.5px solid #e2e8f0; border-radius:12px; background:#fafbfc;
        max-height:260px; overflow-y:auto; }
    .user-list-box .user-item { display:flex; align-items:center; gap:10px; padding:9px 14px;
        border-bottom:1px solid #f1f5f9; transition:background .1s; cursor:pointer; }
    .user-list-box .user-item:last-child { border-bottom:none; }
    .user-list-box .user-item:hover { background:#eff6ff; }
    .user-list-box .user-item input[type=checkbox] { width:16px; height:16px; accent-color:var(--accent); }
    .user-item-name { font-size:.83rem; font-weight:600; color:#1e293b; }
    .user-item-sub  { font-size:.72rem; color:#94a3b8; }

    /* Penerima terpilih */
    .penerima-tags { display:flex; flex-wrap:wrap; gap:6px; min-height:36px;
        border:1.5px dashed #e2e8f0; border-radius:10px; padding:8px 10px; background:#f8fafc; }
    .penerima-tag { display:inline-flex; align-items:center; gap:6px; background:#e8f0fe;
        color:#1a73e8; border-radius:20px; padding:4px 10px; font-size:.78rem; font-weight:600; }
    .penerima-tag .remove-tag { cursor:pointer; font-size:.7rem; color:#6b9ef0; }
    .penerima-tag .remove-tag:hover { color:#dc2626; }

    /* Izin per penerima (di dalam tag-nya) */
    .izin-pill { font-size:.66rem; padding:1px 7px; border-radius:20px; font-weight:700; cursor:pointer;
        border:1.5px solid transparent; transition:all .15s; }
    .izin-pill.on  { background:#dcfce7; color:#16a34a; border-color:#bbf7d0; }
    .izin-pill.off { background:#fee2e2; color:#dc2626; border-color:#fecaca; }

    /* Upload lampiran */
    .drop-zone { border:2px dashed #ced4da; border-radius:12px; padding:20px; text-align:center;
        cursor:pointer; background:#fafbfc; transition:border-color .2s,background .2s; }
    .drop-zone:hover, .drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    .drop-zone i { font-size:1.6rem; color:#94a3b8; display:block; margin-bottom:6px; }
    .drop-zone span { font-size:.78rem; color:#94a3b8; }
    .file-preview-list { margin-top:10px; display:flex; flex-wrap:wrap; gap:8px; }
    .file-chip { display:inline-flex; align-items:center; gap:6px; background:#f1f5f9;
        border-radius:8px; padding:5px 10px; font-size:.75rem; color:#475569; max-width:200px; }
    .file-chip i { color:#64748b; }
    .file-chip .rm { cursor:pointer; color:#94a3b8; font-size:.65rem; }
    .file-chip .rm:hover { color:#dc2626; }

    /* Link rows */
    .link-row { display:flex; gap:8px; margin-bottom:8px; align-items:center; }
    .link-row .form-control { flex:1; }
    .btn-add-link { font-size:.8rem; border-radius:10px; border:1.5px dashed #e2e8f0;
        background:#f8fafc; color:#64748b; padding:7px 14px; transition:all .15s; }
    .btn-add-link:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-light); }

    .btn-submit { background:linear-gradient(135deg,#1a73e8,#1557b0); border:none; border-radius:10px;
        font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0;
        color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; font-weight:600; }

    /* Loading spinner */
    .spinner-sm { width:16px; height:16px; border:2px solid #e2e8f0; border-top-color:var(--accent);
        border-radius:50%; animation:spin .6s linear infinite; display:none; }
    @keyframes spin { to { transform:rotate(360deg); } }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-paper-plane"></i></div>
            <div>
                <h5 class="ph-title">Kirim Dokumen Bersama</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dokumen.index') }}">Dokumen Bersama</a></li>
                    <li><span class="bc-active">Kirim Baru</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('dokumen.index') }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert mb-3" style="background:#fee2e2;color:#991b1b;border-radius:10px;">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" id="formKirim">
        @csrf
        <div class="row g-3">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Info Dokumen --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-alt"></i> Informasi Dokumen</div>

                        <div class="mb-3">
                            <label for="judul">Judul <span class="required-mark">*</span></label>
                            <input type="text" id="judul" name="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}" placeholder="Masukkan judul dokumen…" maxlength="255">
                            <div id="judul-counter" class="char-counter">0 / 255</div>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label for="deskripsi">Deskripsi / Pesan</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Tulis keterangan atau pesan singkat…">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Lampiran File --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-paperclip"></i> Lampiran File</div>
                        <label for="drop-file" class="drop-zone" id="drop-file-zone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret file ke sini<br><small>PDF, DOCX, XLSX, ZIP – maks 20 MB per file</small></span>
                        </label>
                        <input type="file" id="drop-file" name="lampiran[]" class="d-none" multiple
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                        <div class="file-preview-list" id="file-preview"></div>
                    </div>
                </div>

                {{-- Lampiran Foto --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-images"></i> Foto</div>
                        <label for="drop-foto" class="drop-zone" id="drop-foto-zone">
                            <i class="fas fa-image"></i>
                            <span>Klik atau seret foto ke sini<br><small>JPG, PNG, WEBP – maks 5 MB per foto</small></span>
                        </label>
                        <input type="file" id="drop-foto" name="foto[]" class="d-none" multiple
                               accept="image/jpeg,image/png,image/webp">
                        <div class="file-preview-list" id="foto-preview"></div>
                    </div>
                </div>

                {{-- Link Eksternal --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-link"></i> Link Eksternal (Drive, dll.)</div>
                        <div id="link-container">
                            <div class="link-row">
                                <input type="url"  name="links[0][url]"   class="form-control" placeholder="https://drive.google.com/…">
                                <input type="text" name="links[0][judul]" class="form-control" placeholder="Label (opsional)">
                                <button type="button" class="btn btn-sm btn-light btn-rm-link"
                                        style="border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="btn-add-link" class="btn btn-add-link mt-1">
                            <i class="fas fa-plus me-1"></i> Tambah Link
                        </button>
                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Pilih Penerima --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-users"></i> Penerima <span class="required-mark">*</span></div>

                        {{-- Tombol grup --}}
                        <div class="penerima-toolbar">
                            <button type="button" class="btn-grup" data-tipe="kecamatan">
                                <i class="fas fa-building me-1"></i> Kecamatan
                            </button>
                            <button type="button" class="btn-grup" data-tipe="nagari">
                                <i class="fas fa-map-marker-alt me-1"></i> Nagari (Pegawai)
                            </button>
                            <button type="button" class="btn-grup" data-tipe="masyarakat">
                                <i class="fas fa-users me-1"></i> Masyarakat
                            </button>
                        </div>

                        {{-- Pilih nagari (muncul jika tipe = nagari / masyarakat) --}}
                        <div class="nagari-select-wrap" id="nagari-wrap">
                            <select id="nagari-select" class="form-select">
                                <option value="">-- Pilih Nagari --</option>
                                @foreach($nagaris as $nagari)
                                    <option value="{{ $nagari->id }}">{{ $nagari->nama_nagari }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Loading --}}
                        <div class="d-flex align-items-center gap-2 mb-2" id="loading-wrap" style="display:none!important;">
                            <div class="spinner-sm" id="user-spinner" style="display:block;"></div>
                            <span style="font-size:.78rem;color:#94a3b8;">Memuat daftar…</span>
                        </div>

                        {{-- Daftar user --}}
                        <div class="user-list-box mb-3" id="user-list-box" style="display:none;">
                            {{-- diisi JS --}}
                        </div>

                        {{-- Tag penerima terpilih --}}
                        <label class="mb-1">Dipilih:</label>
                        <div class="penerima-tags" id="penerima-tags">
                            <span style="font-size:.75rem;color:#94a3b8;align-self:center;" id="tags-placeholder">
                                Belum ada penerima dipilih
                            </span>
                        </div>
                        @error('penerima')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror

                        {{-- Hidden inputs (diisi JS) --}}
                        <div id="penerima-inputs"></div>
                    </div>
                </div>

                {{-- Tombol submit --}}
                <div class="d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Dokumen
                    </button>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-cancel text-center">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(function () {

    // ── Char counter judul ───────────────────────────────────────
    const judul = document.getElementById('judul');
    const counter = document.getElementById('judul-counter');
    if (judul && counter) {
        const update = () => {
            const n = judul.value.length;
            counter.textContent = n + ' / 255';
            counter.className = 'char-counter' + (n >= 255 ? ' danger' : n >= 210 ? ' warning' : '');
        };
        judul.addEventListener('input', update);
        update();
    }

    // ── File lampiran ────────────────────────────────────────────
    setupDropZone('drop-file-zone', 'drop-file', 'file-preview');
    setupDropZone('drop-foto-zone', 'drop-foto', 'foto-preview');

    function setupDropZone(zoneId, inputId, previewId) {
        const zone    = document.getElementById(zoneId);
        const input   = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!zone || !input || !preview) return;

        let dt = new DataTransfer();

        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            [...e.dataTransfer.files].forEach(f => addFile(f));
        });
        input.addEventListener('change', () => {
            [...input.files].forEach(f => addFile(f));
        });

        function addFile(file) {
            dt.items.add(file);
            input.files = dt.files;
            const chip = document.createElement('div');
            chip.className = 'file-chip';
            chip.innerHTML = `<i class="fas fa-file"></i>
                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${file.name}</span>
                <span class="rm" title="Hapus">✕</span>`;
            chip.querySelector('.rm').addEventListener('click', () => {
                const newDt = new DataTransfer();
                [...input.files].filter(f => f.name !== file.name).forEach(f => newDt.items.add(f));
                dt = newDt;
                input.files = dt.files;
                chip.remove();
            });
            preview.appendChild(chip);
        }
    }

    // ── Link dinamis ─────────────────────────────────────────────
    let linkIdx = 1;
    document.getElementById('btn-add-link').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'link-row';
        row.innerHTML = `
            <input type="url"  name="links[${linkIdx}][url]"   class="form-control" placeholder="https://drive.google.com/…">
            <input type="text" name="links[${linkIdx}][judul]" class="form-control" placeholder="Label (opsional)">
            <button type="button" class="btn btn-sm btn-light btn-rm-link"
                style="border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-times text-danger"></i>
            </button>`;
        row.querySelector('.btn-rm-link').addEventListener('click', () => row.remove());
        document.getElementById('link-container').appendChild(row);
        linkIdx++;
    });
    document.querySelector('.btn-rm-link').addEventListener('click', function () {
        this.closest('.link-row').remove();
    });

    // ── Pemilihan penerima ────────────────────────────────────────
    // State: Map<id_user, { id, nama, label, sub, izin_download, izin_lihat }>
    const selected = new Map();
    let activeTipe = null;

    document.querySelectorAll('.btn-grup').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.btn-grup').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeTipe = btn.dataset.tipe;
            const nagariWrap = document.getElementById('nagari-wrap');
            if (['nagari', 'masyarakat'].includes(activeTipe)) {
                nagariWrap.classList.add('show');
                const idNagari = document.getElementById('nagari-select').value;
                if (idNagari) loadUsers(activeTipe, idNagari);
                else document.getElementById('user-list-box').style.display = 'none';
            } else {
                nagariWrap.classList.remove('show');
                loadUsers(activeTipe, null);
            }
        });
    });

    document.getElementById('nagari-select').addEventListener('change', function () {
        if (activeTipe && this.value) loadUsers(activeTipe, this.value);
    });

    function loadUsers(tipe, idNagari) {
        const box = document.getElementById('user-list-box');
        const loadingWrap = document.getElementById('loading-wrap');
        box.style.display = 'none';
        loadingWrap.style.display = 'flex';

        let url = `{{ route('dokumen.ajax.users') }}?tipe=${tipe}`;
        if (idNagari) url += `&id_nagari=${idNagari}`;

        $.getJSON(url, function (users) {
            loadingWrap.style.display = 'none';
            box.innerHTML = '';
            if (!users.length) {
                box.innerHTML = '<div style="padding:14px;text-align:center;font-size:.78rem;color:#94a3b8;">Tidak ada user ditemukan</div>';
                box.style.display = 'block';
                return;
            }
            users.forEach(u => {
                const checked = selected.has(u.id) ? 'checked' : '';
                const item = document.createElement('label');
                item.className = 'user-item';
                item.innerHTML = `
                    <input type="checkbox" value="${u.id}" ${checked}
                        class="user-cb" data-nama="${u.nama}" data-label="${u.label}" data-sub="${u.sub}">
                    <div>
                        <div class="user-item-name">${u.nama}</div>
                        <div class="user-item-sub">${u.label} · ${u.sub}</div>
                    </div>`;
                item.querySelector('.user-cb').addEventListener('change', function () {
                    if (this.checked) {
                        selected.set(u.id, { id: u.id, nama: u.nama, label: u.label,
                            sub: u.sub, izin_download: true, izin_lihat: true });
                    } else {
                        selected.delete(u.id);
                    }
                    renderTags();
                });
                box.appendChild(item);
            });
            box.style.display = 'block';
        }).fail(() => {
            loadingWrap.style.display = 'none';
            box.innerHTML = '<div style="padding:14px;text-align:center;font-size:.78rem;color:#dc2626;">Gagal memuat data</div>';
            box.style.display = 'block';
        });
    }

    function renderTags() {
        const container = document.getElementById('penerima-tags');
        const inputs    = document.getElementById('penerima-inputs');
        const placeholder = document.getElementById('tags-placeholder');

        container.innerHTML = '';
        inputs.innerHTML    = '';

        if (!selected.size) {
            container.appendChild(placeholder);
            return;
        }

        let idx = 0;
        selected.forEach((u, id) => {
            // Tag
            const tag = document.createElement('div');
            tag.className = 'penerima-tag';
            tag.dataset.id = id;
            tag.innerHTML = `
                <span>${u.nama}</span>
                <span class="izin-pill ${u.izin_lihat ? 'on' : 'off'}" data-field="izin_lihat" title="Toggle izin lihat">
                    <i class="fas fa-eye"></i> ${u.izin_lihat ? 'Lihat' : 'No Lihat'}
                </span>
                <span class="izin-pill ${u.izin_download ? 'on' : 'off'}" data-field="izin_download" title="Toggle izin download">
                    <i class="fas fa-download"></i> ${u.izin_download ? 'Unduh' : 'No Unduh'}
                </span>
                <span class="remove-tag" title="Hapus">✕</span>`;
            tag.querySelectorAll('.izin-pill').forEach(pill => {
                pill.addEventListener('click', e => {
                    e.stopPropagation();
                    const field = pill.dataset.field;
                    u[field] = !u[field];
                    selected.set(id, u);
                    renderTags();
                });
            });
            tag.querySelector('.remove-tag').addEventListener('click', () => {
                selected.delete(id);
                // uncheck di list jika masih tampil
                const cb = document.querySelector(`.user-cb[value="${id}"]`);
                if (cb) cb.checked = false;
                renderTags();
            });
            container.appendChild(tag);

            // Hidden inputs
            inputs.innerHTML += `
                <input type="hidden" name="penerima[${idx}][id_user]" value="${id}">
                <input type="hidden" name="penerima[${idx}][izin_download]" value="${u.izin_download ? 1 : 0}">
                <input type="hidden" name="penerima[${idx}][izin_lihat]"    value="${u.izin_lihat    ? 1 : 0}">`;
            idx++;
        });
    }

    // ── Validasi submit ──────────────────────────────────────────
    document.getElementById('formKirim').addEventListener('submit', function (e) {
        if (!selected.size) {
            e.preventDefault();
            alert('Pilih minimal satu penerima sebelum mengirim dokumen.');
        }
    });
});
</script>
@endsection
