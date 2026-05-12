{{-- filepath: resources/views/pages/camat/heroslide/edit.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Edit Hero Slide')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content:''; position:absolute; left:0; top:0; bottom:0;
        width:4px; border-radius:14px 0 0 14px;
    }
    .ph-card.edit-page::before { background:#f59e0b; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon {
        width:42px; height:42px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        font-size:1rem; flex-shrink:0;
    }
    .ph-icon.edit  { background:#fef9c3; color:#a16207; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb {
        display:flex; align-items:center; gap:4px; flex-wrap:wrap;
        margin-top:4px; list-style:none; padding:0; margin-bottom:0;
    }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ===== CARD ===== */
    .edit-card {
        border:none; border-radius:16px;
        box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden;
    }
    .edit-card .card-header {
        background:#fff; border-bottom:1px solid #f1f5f9;
        padding:18px 24px;
    }
    .edit-card .card-header h5 { font-size:.95rem; font-weight:700; color:#1e293b; margin:0; }
    .edit-card .card-body { padding:28px; }

    /* ===== FORM ===== */
    .form-label { font-size:.8rem; font-weight:600; color:#475569; margin-bottom:5px; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.83rem;
        padding:7px 12px; color:#334155; background-color:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:#f59e0b; background:#fff;
        box-shadow:0 0 0 3px rgba(245,158,11,.12);
    }
    .form-text { font-size:.74rem; color:#94a3b8; margin-top:4px; }

    /* ===== IMAGE PREVIEW ===== */
    .img-preview-wrap {
        background:#f1f5f9; border-radius:12px; border:1.5px dashed #cbd5e1;
        display:flex; align-items:center; justify-content:center;
        min-height:160px; overflow:hidden; margin-bottom:12px; position:relative;
    }
    .img-preview-wrap img { width:100%; max-height:220px; object-fit:cover; border-radius:10px; }
    .img-preview-placeholder { color:#94a3b8; font-size:.83rem; text-align:center; padding:20px; }
    .img-preview-placeholder i { font-size:2.2rem; display:block; margin-bottom:6px; }

    /* ===== SECTION DIVIDER ===== */
    .section-label {
        font-size:.72rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.6px; color:#94a3b8; margin-bottom:14px;
        padding-bottom:8px; border-bottom:1px solid #f1f5f9;
    }

    /* ===== BUTTONS ===== */
    .btn-save {
        background:linear-gradient(135deg,#f59e0b,#d97706); border:none;
        border-radius:10px; font-weight:600; font-size:.83rem; padding:9px 22px;
        color:#fff; box-shadow:0 2px 8px rgba(245,158,11,.35);
        transition:all .2s ease;
    }
    .btn-save:hover {
        background:linear-gradient(135deg,#d97706,#b45309);
        box-shadow:0 4px 14px rgba(245,158,11,.45); transform:translateY(-1px);
        color:#fff;
    }
    .btn-cancel {
        border-radius:10px; font-size:.83rem; border-color:#e2e8f0;
        color:#64748b; padding:9px 22px; background:#fff;
        transition:all .15s ease;
    }
    .btn-cancel:hover { background:#f1f5f9; border-color:#cbd5e1; color:#334155; }

    .alert-danger {
        background:#fee2e2; border:1px solid #fca5a5;
        color:#991b1b; border-radius:12px; font-size:.85rem;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card edit-page">
        <div class="ph-left">
            <div class="ph-icon edit"><i class="fas fa-edit"></i></div>
            <div>
                <h5 class="ph-title">Edit Hero Slide</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('camat.settings.heroslide') }}">Hero Slide</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="page-inner">

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card edit-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-images text-warning opacity-75"></i>
                <h5>Edit Slide: <span class="text-warning">{{ $slide->title ?? 'Tanpa Judul' }}</span></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('camat.settings.heroslide.update', $slide->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Kolom Kiri: Gambar --}}
                        <div class="col-md-5">
                            <div class="section-label"><i class="fas fa-image me-1"></i> Gambar Slide</div>

                            {{-- Preview gambar saat ini --}}
                            <div class="img-preview-wrap" id="previewWrap">
                                @if ($slide->image)
                                    <img src="{{ asset('storage/' . $slide->image) }}"
                                        alt="{{ $slide->title }}" id="previewImg">
                                @else
                                    <div class="img-preview-placeholder" id="previewPlaceholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        Belum ada gambar
                                    </div>
                                    <img id="previewImg" src="" class="d-none" alt="Preview">
                                @endif
                            </div>

                            <input type="file" name="image" id="imageInput"
                                class="form-control" accept="image/*"
                                onchange="previewImage(this)">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: JPG, JPEG, PNG &bull; Maks. 2MB &bull; Disarankan: 1920×1080px<br>
                                Kosongkan jika tidak ingin mengganti gambar.
                            </div>
                        </div>

                        {{-- Kolom Kanan: Info Slide --}}
                        <div class="col-md-7">
                            <div class="section-label"><i class="fas fa-align-left me-1"></i> Informasi Slide</div>

                            <div class="mb-3">
                                <label class="form-label">Judul Slide</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $slide->title) }}"
                                    placeholder="Masukkan judul slide (opsional)">
                                <div class="form-text">Kosongkan jika tidak ingin menampilkan judul.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="5"
                                    placeholder="Masukkan deskripsi slide (opsional)">{{ old('description', $slide->description) }}</textarea>
                                <div class="form-text">Kosongkan jika tidak ingin menampilkan deskripsi.</div>
                            </div>

                            <div class="section-label mt-4"><i class="fas fa-mouse-pointer me-1"></i> Tombol CTA (Opsional)</div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Teks Tombol</label>
                                    <input type="text" name="button_text" class="form-control"
                                        value="{{ old('button_text', $slide->button_text) }}"
                                        placeholder="Contoh: Pelajari Lebih Lanjut">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Link Tombol</label>
                                    <input type="url" name="button_link" class="form-control"
                                        value="{{ old('button_link', $slide->button_link) }}"
                                        placeholder="https://...">
                                </div>
                            </div>
                        </div>

                    </div>{{-- end .row --}}

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3"
                        style="border-top:1px solid #f1f5f9;">
                        <a href="{{ route('camat.settings.heroslide') }}" class="btn btn-cancel">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-1"></i> Perbarui Slide
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end .page-inner --}}
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const img         = document.getElementById('previewImg');
        const placeholder = document.getElementById('previewPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
