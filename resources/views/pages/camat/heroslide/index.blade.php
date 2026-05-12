{{-- filepath: resources/views/pages/camat/heroslide/index.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Manage Hero Beranda')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body, .card, .table, .btn, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border: none; border-radius: 16px; padding: 20px;
        position: relative; overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after {
        content: ''; position: absolute; right: -18px; top: -18px;
        width: 80px; height: 80px; border-radius: 50%; opacity: .12;
    }
    .stat-card.blue   { background: linear-gradient(135deg,#e8f0fe,#dbeafe); }
    .stat-card.blue::after   { background: #1a73e8; }
    .stat-card.purple { background: linear-gradient(135deg,#f3e8ff,#ede9fe); }
    .stat-card.purple::after { background: #7c3aed; }

    .stat-icon {
        width:48px; height:48px; border-radius:12px;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:1.25rem; flex-shrink:0;
    }
    .stat-icon.blue   { background:#1a73e8; color:#fff; }
    .stat-icon.purple { background:#7c3aed; color:#fff; }

    .stat-value { font-size:1.85rem; font-weight:800; line-height:1; color:#1e293b; }
    .stat-label { font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:3px; }

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
    .ph-card.index-page::before { background:#1a73e8; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon {
        width:42px; height:42px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        font-size:1rem; flex-shrink:0;
    }
    .ph-icon.index { background:#e8f0fe; color:#1a73e8; }
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

    /* ===== FILTER CARD ===== */
    .filter-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .filter-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:18px 24px; }
    .filter-card .card-header h5 { font-size:.95rem; font-weight:700; color:#1e293b; }

    /* ===== TABLE ===== */
    .table thead th {
        background:#f8fafc; color:#64748b; font-size:.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.6px; padding:12px 16px;
        border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap;
    }
    .table tbody td {
        padding:13px 16px; vertical-align:middle; font-size:.85rem;
        color:#334155; border-bottom:1px solid #f1f5f9;
    }
    .table tbody tr:last-child td { border-bottom:none; }
    .table-hover tbody tr:hover td { background:#f8fafc; }

    /* ===== SLIDE THUMBNAIL ===== */
    .thumb-slide {
        width: 110px; height: 62px; object-fit: cover;
        border-radius: 8px; border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .thumb-placeholder {
        width:110px; height:62px; border-radius:8px;
        background:#f1f5f9; display:inline-flex;
        align-items:center; justify-content:center;
        color:#94a3b8; font-size:.75rem; border:1px dashed #cbd5e1;
    }

    /* ===== BADGES ===== */
    .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:6px; letter-spacing:.2px; }
    .badge-slide { background:#e8f0fe; color:#1558b0; }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width:30px; height:30px; display:inline-flex; align-items:center;
        justify-content:center; border-radius:8px; font-size:.75rem;
        padding:0; border:none; transition:all .15s ease;
    }
    .btn-edit-a { background:#fef9c3; color:#a16207; }
    .btn-edit-a:hover { background:#ca8a04; color:#fff; }
    .btn-hapus { background:#fee2e2; color:#dc2626; }
    .btn-hapus:hover { background:#dc2626; color:#fff; }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background:linear-gradient(135deg,#1a73e8,#1558b0); border:none;
        border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px;
        box-shadow:0 2px 8px rgba(26,115,232,.35); transition:all .2s ease;
    }
    .btn-primary:hover {
        background:linear-gradient(135deg,#1558b0,#0f3e82);
        box-shadow:0 4px 14px rgba(26,115,232,.45); transform:translateY(-1px);
    }
    .btn-outline-secondary {
        border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; padding:7px 12px;
    }
    .btn-outline-secondary:hover { background:#f1f5f9; border-color:#cbd5e1; color:#334155; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding:60px 20px; }
    .empty-state-icon {
        width:72px; height:72px; background:#f1f5f9; border-radius:50%;
        display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
    }
    .empty-state-icon i { font-size:1.8rem; color:#94a3b8; }

    .card-footer { background:#f8fafc; border-top:1px solid #f1f5f9; padding:12px 24px; }

    .alert-success {
        background:#dcfce7; border:1px solid #bbf7d0;
        color:#15803d; border-radius:12px; font-size:.85rem;
    }

    /* ===== MODAL ===== */
    .modal-content { border:none; border-radius:16px; overflow:hidden; }
    .modal-header  { padding:18px 24px; border-bottom:1px solid #f1f5f9; }
    .modal-body    { padding:24px 28px; }
    .modal-footer  { padding:14px 24px; border-top:1px solid #f1f5f9; background:#fafbfc; }
    .modal-header.bg-primary { background:linear-gradient(135deg,#1a73e8,#1558b0) !important; }
    .modal-title { font-size:.95rem; font-weight:700; }
    .form-label { font-size:.8rem; font-weight:600; color:#475569; margin-bottom:5px; }

    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.83rem;
        padding:7px 12px; color:#334155; background-color:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:#1a73e8; background:#fff; box-shadow:0 0 0 3px rgba(26,115,232,.12);
    }

    /* Preview image di modal */
    .img-preview-wrap {
        background:#f1f5f9; border-radius:10px; border:1.5px dashed #cbd5e1;
        display:flex; align-items:center; justify-content:center;
        min-height:140px; overflow:hidden; margin-bottom:10px;
    }
    .img-preview-wrap img { max-height:180px; border-radius:8px; object-fit:cover; width:100%; }
    .img-preview-placeholder { color:#94a3b8; font-size:.83rem; text-align:center; padding:20px; }
    .img-preview-placeholder i { font-size:2rem; display:block; margin-bottom:6px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card index-page">
        <div class="ph-left">
            <div class="ph-icon index"><i class="fas fa-images"></i></div>
            <div>
                <h5 class="ph-title">Manage Hero Beranda</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><span class="bc-active">Hero Slide</span></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="page-inner">

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card stat-card blue">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon blue"><i class="fas fa-images"></i></div>
                        <div>
                            <div class="stat-value">{{ $slides->count() }}</div>
                            <div class="stat-label">Total Slide</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card stat-card purple">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon purple"><i class="fas fa-mouse-pointer"></i></div>
                        <div>
                            <div class="stat-value">{{ $slides->whereNotNull('button_text')->count() }}</div>
                            <div class="stat-label">Slide dengan Tombol</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card filter-card shadow-sm">

            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary me-2 opacity-75"></i>
                    Daftar Hero Slide
                </h5>

                @php
                    $canManage = auth()->user()->role === 'camat'
                        || (auth()->user()->role === 'pegawai' && is_null(auth()->user()->pegawai?->id_nagari));
                @endphp

                @if ($canManage)
                    <button type="button" class="btn btn-primary btn-sm"
                        data-bs-toggle="modal" data-bs-target="#tambahHeroslideModal">
                        <i class="fas fa-plus me-1"></i> Tambah Hero Slide
                    </button>
                @endif
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th width="130">Gambar</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Tombol</th>
                                @if ($canManage)
                                    <th width="90">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($slides as $index => $slide)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>

                                    {{-- Gambar --}}
                                    <td>
                                        @if ($slide->image)
                                            <img src="{{ asset('storage/' . $slide->image) }}"
                                                alt="{{ $slide->title }}" class="thumb-slide">
                                        @else
                                            <div class="thumb-placeholder"><i class="fas fa-image"></i></div>
                                        @endif
                                    </td>

                                    {{-- Judul --}}
                                    <td>
                                        <div class="fw-semibold" style="font-size:.87rem;color:#1e293b;">
                                            {{ $slide->title ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Deskripsi --}}
                                    <td>
                                        <div style="font-size:.82rem;color:#64748b;max-width:280px;">
                                            {{ $slide->description ? Str::limit($slide->description, 80) : '-' }}
                                        </div>
                                    </td>

                                    {{-- Tombol --}}
                                    <td>
                                        @if ($slide->button_text)
                                            <span class="badge badge-slide">
                                                <i class="fas fa-link me-1"></i>{{ $slide->button_text }}
                                            </span>
                                        @else
                                            <span class="text-muted" style="font-size:.8rem;">-</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    @if ($canManage)
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('camat.settings.heroslide.edit', $slide->id) }}"
                                                    class="btn btn-action btn-edit-a" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button class="btn btn-action btn-hapus"
                                                    data-id="{{ $slide->id }}"
                                                    data-judul="{{ $slide->title ?? 'slide ini' }}"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-hapus-{{ $slide->id }}"
                                                    action="{{ route('camat.settings.heroslide.destroy', $slide->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canManage ? 6 : 5 }}" class="p-0 text-center">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-images"></i>
                                            </div>
                                            <div class="fw-semibold text-secondary mb-1">Belum ada hero slide</div>
                                            <div class="text-muted" style="font-size:.8rem;">
                                                Tambahkan slide untuk ditampilkan di halaman beranda
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end .card --}}
    </div>{{-- end .page-inner --}}
</div>


{{-- ==================== MODAL TAMBAH HERO SLIDE ==================== --}}
@if ($canManage)
<div class="modal fade" id="tambahHeroslideModal" tabindex="-1"
    aria-labelledby="tambahHeroslideLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('camat.settings.heroslide.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tambahHeroslideLabel">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Hero Slide
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">

                        {{-- Kolom Kiri: Gambar --}}
                        <div class="col-md-5">
                            <label class="form-label">Gambar <span class="text-danger">*</span></label>

                            {{-- Preview --}}
                            <div class="img-preview-wrap" id="previewWrap-tambah">
                                <div class="img-preview-placeholder" id="previewPlaceholder-tambah">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Preview gambar akan muncul di sini
                                </div>
                                <img id="previewImg-tambah" src="" alt="Preview" class="d-none" style="max-height:200px;width:100%;object-fit:cover;border-radius:8px;">
                            </div>

                            <input type="file" name="image" id="image-tambah"
                                class="form-control" accept="image/*" required
                                onchange="previewImage(this,'tambah')">
                            <div class="mt-2" style="font-size:.75rem;color:#94a3b8;">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: JPG, JPEG, PNG &bull; Maks. 2MB &bull; Disarankan: 1920×1080px
                            </div>
                        </div>

                        {{-- Kolom Kanan: Form --}}
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Judul Slide</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title') }}"
                                    placeholder="Masukkan judul slide (opsional)">
                                <div style="font-size:.74rem;color:#94a3b8;margin-top:4px;">
                                    Kosongkan jika tidak ingin menampilkan judul.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4"
                                    placeholder="Masukkan deskripsi slide (opsional)">{{ old('description') }}</textarea>
                                <div style="font-size:.74rem;color:#94a3b8;margin-top:4px;">
                                    Kosongkan jika tidak ingin menampilkan deskripsi.
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Teks Tombol <span style="font-size:.73rem;color:#94a3b8;">(Opsional)</span></label>
                                    <input type="text" name="button_text" class="form-control"
                                        value="{{ old('button_text') }}"
                                        placeholder="Contoh: Pelajari Lebih Lanjut">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Link Tombol <span style="font-size:.73rem;color:#94a3b8;">(Opsional)</span></label>
                                    <input type="url" name="button_link" class="form-control"
                                        value="{{ old('button_link') }}"
                                        placeholder="https://...">
                                </div>
                            </div>
                        </div>

                    </div>{{-- end .row --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Hero Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif


@endsection

@section('scripts')
<script>
    // Preview gambar sebelum upload
    function previewImage(input, key) {
        const img       = document.getElementById('previewImg-' + key);
        const placeholder = document.getElementById('previewPlaceholder-' + key);
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

    // Buka kembali modal tambah jika ada error validasi
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('tambahHeroslideModal')).show();
        });
    @endif

    // Konfirmasi hapus dengan sweetalert (jika tersedia) atau confirm biasa
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const judul = this.dataset.judul;

            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Hapus Hero Slide?',
                    text : `Slide "${judul}" akan dihapus permanen.`,
                    icon : 'warning',
                    buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
                    dangerMode: true,
                }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
            } else {
                if (confirm(`Yakin hapus slide "${judul}"?`)) {
                    document.getElementById('form-hapus-' + id).submit();
                }
            }
        });
    });
</script>
@endsection
