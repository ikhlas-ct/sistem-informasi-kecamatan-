@extends('layouts.user.user')

@section('title', 'Edit Balasan – ' . Str::limit($pengaduan->judul_pengaduan, 40))

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

    /* ── Section cards ── */
    .section-card { border: none; border-radius: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
    .section-card .card-body { padding: 20px 22px; }
    .section-divider {
        border-left: 4px solid var(--accent); background: #f8f9fa;
        padding: 7px 13px; border-radius: 0 6px 6px 0;
        font-weight: 700; font-size: .82rem; color: var(--accent);
        display: flex; align-items: center; gap: 8px; margin-bottom: 1.1rem;
    }

    /* ── Ringkasan Pengaduan ── */
    .pengaduan-preview {
        background: #f8fafc; border-radius: 10px; border: 1px solid #e9ecef;
        padding: 13px 16px; margin-bottom: 1.1rem;
    }
    .pengaduan-preview .pv-title { font-size: .88rem; font-weight: 700; color: #1e293b; }
    .pengaduan-preview .pv-meta  { font-size: .75rem; color: #64748b; margin-top: 3px; }
    .badge { font-size: .72rem; font-weight: 600; padding: 4px 9px; border-radius: 20px; }
    .badge-pending  { background: #fef9c3; color: #854d0e; }
    .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai  { background: #dcfce7; color: #15803d; }
    .badge-ditolak  { background: #fee2e2; color: #991b1b; }

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

    /* ── Status Selector ── */
    .status-selector { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }
    .status-option {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 13px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; cursor: pointer;
        transition: border-color .18s, background .18s; background: #f8fafc;
    }
    .status-option:has(input:checked) { border-color: var(--accent); background: var(--accent-light); }
    .status-option input[type="radio"] { accent-color: var(--accent); width: 15px; height: 15px; flex-shrink: 0; }
    .status-option .so-label { font-size: .82rem; font-weight: 600; color: #334155; }
    .status-option .so-dot {
        width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
    }
    .dot-pending  { background: #d97706; }
    .dot-diproses { background: #2563eb; }
    .dot-selesai  { background: #16a34a; }
    .dot-ditolak  { background: #dc2626; }

    /* ── Lampiran Existing ── */
    .existing-lampiran-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; }
    .existing-item {
        position: relative; width: 90px; min-height: 90px;
        border-radius: 10px; overflow: hidden;
        border: 1.5px solid #e2e8f0; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: opacity .2s;
    }
    .existing-item.marked-delete { opacity: .35; border-color: #dc2626; }
    .existing-item img { width: 100%; height: 90px; object-fit: cover; }
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
    .existing-item.marked-delete .delete-toggle { background: #64748b; }
    .existing-item .delete-toggle:hover { transform: scale(1.15); }
    .delete-label {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: rgba(220,38,38,.85); color: #fff;
        font-size: .6rem; text-align: center; padding: 2px 0; display: none;
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
    .quota-bar { font-size: .75rem; color: #64748b; margin-top: 8px; display: flex; align-items: center; gap: 8px; }
    .quota-bar .q-track { flex: 1; height: 4px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
    .quota-bar .q-fill  { height: 4px; border-radius: 4px; background: #16a34a; transition: width .3s, background .3s; }

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
                <h5 class="ph-title">Edit Balasan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li><a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}">Detail</a></li>
                    <li><span class="bc-active">Edit Balasan</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert d-flex align-items-start gap-2 mb-3"
         style="background:#fee2e2;color:#991b1b;border-radius:12px;border:none;font-size:.84rem;">
        <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
        <div>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('balasanpengaduan.update', $balasan->id_balasanpengaduan) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- ════════ KOLOM KIRI ════════ --}}
            <div class="col-lg-8">

                {{-- Ringkasan pengaduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-alt"></i> Pengaduan yang Dibalas</div>
                        <div class="pengaduan-preview">
                            <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                                <div>
                                    <div class="pv-title">{{ $pengaduan->judul_pengaduan }}</div>
                                    <div class="pv-meta">
                                        <i class="fas fa-tag me-1"></i>{{ $pengaduan->hal_pengaduan }}
                                        &nbsp;·&nbsp;
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d M Y') }}
                                        @if($pengaduan->masyarakat)
                                            &nbsp;·&nbsp;
                                            <i class="fas fa-user me-1"></i>{{ $pengaduan->masyarakat->nama_masyarakat }}
                                        @endif
                                    </div>
                                </div>
                                <span class="badge badge-{{ $pengaduan->status }} flex-shrink-0">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Isi Balasan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-pen"></i> Edit Isi Balasan</div>

                        <div class="mb-3">
                            <label for="tanggal_balasan">
                                Tanggal Balasan <span class="required-mark">*</span>
                            </label>
                            <input type="date" id="tanggal_balasan" name="tanggal_balasan"
                                   class="form-control @error('tanggal_balasan') is-invalid @enderror"
                                   value="{{ old('tanggal_balasan', \Carbon\Carbon::parse($balasan->tanggal_balasan)->format('Y-m-d')) }}"
                                   required>
                            @error('tanggal_balasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label for="balasan">
                                Isi Balasan <span class="required-mark">*</span>
                            </label>
                            <textarea id="balasan" name="balasan" rows="8"
                                      class="form-control @error('balasan') is-invalid @enderror"
                                      placeholder="Tulis balasan resmi untuk pengaduan ini..."
                                      maxlength="5000" required>{{ old('balasan', $balasan->balasan) }}</textarea>
                            @error('balasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="balasan-counter" class="char-counter">0 / 5000</div>
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-paperclip"></i> Lampiran Balasan
                            <span style="font-weight:400;color:#94a3b8;font-size:.76rem;margin-left:4px;">
                                — <span id="marked-count">0</span> ditandai hapus
                            </span>
                        </div>

                        @if($balasan->lampiran_balasan->count() > 0)
                        <div style="font-size:.78rem;font-weight:600;color:#64748b;margin-bottom:8px;">
                            Lampiran Saat Ini
                        </div>
                        <div class="existing-lampiran-grid">
                            @foreach($balasan->lampiran_balasan as $lmp)
                            <div class="existing-item" id="existing-{{ $lmp->id }}">
                                @if($lmp->tipe === 'gambar')
                                    <img src="{{ asset('storage/' . $lmp->path) }}" alt="Lampiran">
                                @else
                                    @php $ext = strtoupper(pathinfo($lmp->path, PATHINFO_EXTENSION)); @endphp
                                    <div class="file-icon">
                                        <i class="fas fa-file-alt"></i>
                                        <span>{{ $ext }}</span>
                                    </div>
                                @endif
                                <button type="button" class="delete-toggle"
                                        onclick="toggleDeleteLampiran({{ $lmp->id }})"
                                        title="Tandai hapus">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="delete-label">Hapus</div>
                                <input type="checkbox" name="hapus_lampiran[]"
                                       value="{{ $lmp->id }}"
                                       id="del-{{ $lmp->id }}"
                                       class="delete-check d-none">
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div style="font-size:.78rem;font-weight:600;color:#64748b;margin-bottom:8px;">
                            Tambah Lampiran Baru
                        </div>
                        <div id="lampiran-drop-zone" class="lampiran-drop-zone"
                             onclick="document.getElementById('lampiran-input').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span><strong>Klik atau seret file ke sini</strong></span>
                            <span>JPG, PNG, WEBP, PDF, DOC, DOCX · Maks. 10 MB per file</span>
                        </div>
                        <input type="file" id="lampiran-input" name="lampiran[]"
                               multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx"
                               style="display:none;">

                        <div id="new-preview-container"></div>

                        <div class="quota-bar">
                            <span id="quota-text">Total: 0 / 10 file</span>
                            <div class="q-track"><div class="q-fill" id="quota-fill" style="width:0%"></div></div>
                        </div>
                    </div>
                </div>

            </div>{{-- end kolom kiri --}}

            {{-- ════════ KOLOM KANAN ════════ --}}
            <div class="col-lg-4">

                {{-- ── Ubah Status Pengaduan ── --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-tags"></i> Status Pengaduan</div>
                        <p style="font-size:.78rem;color:#64748b;margin-bottom:.75rem;line-height:1.5;">
                            Status saat ini: <strong>{{ ucfirst($pengaduan->status) }}</strong>.
                            Pilih status baru jika perlu diubah.
                        </p>

                        @error('status')
                            <div style="font-size:.78rem;color:#dc2626;margin-bottom:.5rem;">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="status-selector">
                            @foreach($statusOptions as $value => $label)
                            <label class="status-option">
                                <input type="radio" name="status" value="{{ $value }}"
                                    {{ old('status', $pengaduan->status) === $value ? 'checked' : '' }}>
                                <span class="so-dot dot-{{ $value }}"></span>
                                <span class="so-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Simpan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-save"></i> Simpan Perubahan</div>
                        <p style="font-size:.82rem;color:#64748b;line-height:1.6;">
                            Perubahan pada balasan dan status akan langsung terlihat oleh pelapor.
                        </p>
                        <div class="d-flex gap-2 flex-column mt-3">
                            <button type="submit" class="btn btn-update w-100">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('pengaduan.show', $pengaduan->id_pengaduan) }}"
                               class="btn btn-cancel text-center">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Info petugas --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user-tie"></i> Petugas Pembalas</div>
                        <div style="font-size:.84rem;color:#1e293b;font-weight:600;">
                            {{ $pegawai->nama_pegawai }}
                        </div>
                        @if($pegawai->jabatan)
                        <div style="font-size:.76rem;color:#64748b;margin-top:2px;">
                            {{ $pegawai->jabatan }}
                        </div>
                        @endif
                        @if($pegawai->nagari)
                        <div style="font-size:.76rem;color:#64748b;margin-top:2px;">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $pegawai->nagari->nama_nagari }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Riwayat --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-clock"></i> Riwayat Balasan</div>
                        <table style="width:100%;font-size:.8rem;border-collapse:collapse;">
                            <tr>
                                <td style="color:#64748b;font-weight:600;padding:5px 4px;border-bottom:1px solid #f1f5f9;width:90px;">Dibuat</td>
                                <td style="color:#1e293b;padding:5px 4px;border-bottom:1px solid #f1f5f9;">{{ $balasan->created_at?->translatedFormat('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b;font-weight:600;padding:5px 4px;">Diperbarui</td>
                                <td style="color:#1e293b;padding:5px 4px;">{{ $balasan->updated_at?->translatedFormat('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Panduan Lampiran --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-lightbulb"></i> Panduan Lampiran</div>
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

    const existingTotal = {{ $balasan->lampiran_balasan->count() }};
    const input         = document.getElementById('lampiran-input');
    const dropZone      = document.getElementById('lampiran-drop-zone');
    const newPreview    = document.getElementById('new-preview-container');
    const quotaText     = document.getElementById('quota-text');
    const quotaFill     = document.getElementById('quota-fill');
    const markedSpan    = document.getElementById('marked-count');

    window.toggleDeleteLampiran = function (id) {
        const el      = document.getElementById(`existing-${id}`);
        const check   = document.getElementById(`del-${id}`);
        const isMarked = el.classList.toggle('marked-delete');
        check.checked  = isMarked;
        markedCount    = document.querySelectorAll('.delete-check:checked').length;
        if (markedSpan) markedSpan.textContent = markedCount;
        updateQuota();
    };

    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        addFiles([...e.dataTransfer.files]);
    });
    input.addEventListener('change', () => { addFiles([...input.files]); input.value = ''; });

    function addFiles(incoming) {
        let errs = [];
        incoming.forEach(file => {
            const currentTotal = (existingTotal - markedCount) + newFiles.length;
            if (currentTotal >= MAX_FILES) { errs.push(`Maksimal ${MAX_FILES} file total.`); return; }
            if (file.size > MAX_SIZE)       { errs.push(`"${file.name}" melebihi 10 MB.`); return; }
            const allowed = [...ACCEPT_IMG, ...ACCEPT_DOC];
            if (!allowed.includes(file.type)) { errs.push(`"${file.name}" format tidak didukung.`); return; }
            if (!newFiles.some(f => f.name === file.name && f.size === file.size)) newFiles.push(file);
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
                icon.innerHTML = `<i class="fas fa-file-alt"></i><span>${ext}</span><small style="font-size:.55rem;">${trim(file.name,14)}</small>`;
                item.appendChild(icon);
            }

            const btn = document.createElement('button');
            btn.type = 'button'; btn.className = 'remove-btn';
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
        quotaText.textContent      = `Total: ${total} / ${MAX_FILES} file`;
        quotaFill.style.width      = Math.min(pct, 100) + '%';
        quotaFill.style.background = pct >= 100 ? '#dc2626' : pct >= 70 ? '#d97706' : '#16a34a';
    }

    function trim(name, max) { return name.length > max ? name.substring(0, max) + '…' : name; }

    const balasanEl      = document.getElementById('balasan');
    const balasanCounter = document.getElementById('balasan-counter');
    const updateCounter  = () => {
        const len = balasanEl.value.length;
        balasanCounter.textContent = `${len} / 5000`;
        balasanCounter.className   = 'char-counter' + (len > 4500 ? ' danger' : len > 3750 ? ' warning' : '');
    };
    balasanEl.addEventListener('input', updateCounter);
    updateCounter();

    updateQuota();
})();
</script>
@endsection
