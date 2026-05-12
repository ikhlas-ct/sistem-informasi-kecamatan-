@extends('layouts.user.user')

@section('title', 'Edit ' . ucfirst(str_replace('_',' ', $jenis)) . ' – ' . Str::limit($konten->judul, 40))

@php
    $jenisLabel = match($jenis) {
        'berita'           => ['label' => 'Berita',           'color' => '#e96c1a', 'light' => '#fff4ed'],
        'artikel'          => ['label' => 'Artikel',          'color' => '#7c3aed', 'light' => '#f5f3ff'],
        'seni_tari'        => ['label' => 'Seni Tari',        'color' => '#be185d', 'light' => '#fdf2f8'],
        'makanan_daerah'   => ['label' => 'Makanan Daerah',   'color' => '#c2410c', 'light' => '#fff7ed'],
        'kerajinan_daerah' => ['label' => 'Kerajinan Daerah', 'color' => '#b45309', 'light' => '#fffbeb'],
        'seni_musik'       => ['label' => 'Seni Musik',       'color' => '#7e22ce', 'light' => '#f5f3ff'],
        'seni_budaya'      => ['label' => 'Seni Budaya',      'color' => '#0f766e', 'light' => '#f0fdfa'],
        'pariwisata'       => ['label' => 'Pariwisata',       'color' => '#15803d', 'light' => '#f0fdf4'],
        'pertanian'        => ['label' => 'Pertanian',        'color' => '#4d7c0f', 'light' => '#f7fee7'],
        default            => ['label' => ucfirst($jenis),    'color' => '#64748b', 'light' => '#f1f5f9'],
    };

    $jenisOptions = [
        'berita'           => 'Berita',
        'artikel'          => 'Artikel',
        'seni_tari'        => 'Seni Tari',
        'makanan_daerah'   => 'Makanan Daerah',
        'kerajinan_daerah' => 'Kerajinan Daerah',
        'seni_musik'       => 'Seni Musik',
        'seni_budaya'      => 'Seni Budaya',
        'pariwisata'       => 'Pariwisata',
        'pertanian'        => 'Pertanian',
    ];
@endphp

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --accent:        {{ $jenisLabel['color'] }};
        --accent-light:  {{ $jenisLabel['light'] }};
        --accent-shadow: color-mix(in srgb, {{ $jenisLabel['color'] }} 18%, transparent);
    }

    .container { padding-left:28px; padding-right:24px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Section cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:20px 22px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    /* ── Form controls ── */
    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Summernote ── */
    .note-editor.note-frame { border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }
    .note-editor.note-frame .note-toolbar { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .note-editor.note-frame.focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-shadow); }
    .note-editor .note-editable { font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; min-height:300px; }

    /* ── Gambar wrap ── */
    .gambar-wrap { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px; display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s; background:#fafbfc; position:relative; }
    .gambar-wrap:hover { border-color:var(--accent); }
    #gambar-preview { width:100%; height:100%; object-fit:cover; }
    .gambar-placeholder { text-align:center; color:#94a3b8; position:absolute; }
    .gambar-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .gambar-placeholder div { font-size:.78rem; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; white-space:nowrap; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    /* ── Char counter ── */
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; font-weight:600; }

    /* ── Buttons ── */
    .btn-update { background:linear-gradient(135deg, var(--accent), color-mix(in srgb, var(--accent) 80%, black)); border:none; border-radius:10px; font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-update:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }

    /* ── Pending notice ── */
    .pending-notice { background:#fef9c3; border-radius:10px; padding:10px 14px; font-size:.83rem; color:#854d0e; display:flex; align-items:flex-start; gap:8px; }

    /* ── Info badge ── */
    .info-badge { display:inline-flex; align-items:center; gap:5px; background:#f1f5f9; border-radius:8px; padding:4px 10px; font-size:.75rem; color:#64748b; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit {{ $jenisLabel['label'] }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('konten.index', $jenis) }}">{{ $jenisLabel['label'] }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="info-badge">
                <i class="fas fa-calendar-alt"></i>
                Dibuat: {{ $konten->created_at?->translatedFormat('d M Y') }}
            </span>
            <a href="{{ route('konten.index', $jenis) }}" class="btn btn-cancel btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Notif jika non-superadmin edit konten aktif --}}
    @if(!$isSuperAdmin && $konten->status === 'aktif')
    <div class="pending-notice mb-3">
        <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
        <span>Setelah disimpan, konten ini akan kembali berstatus <strong>Menunggu Persetujuan</strong> hingga ditinjau ulang oleh admin.</span>
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

    <form action="{{ route('konten.update', ['jenis' => $jenis, 'id_konten' => $konten->id_konten]) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-edit"></i> Informasi Konten
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="judul">Judul <span class="required-mark">*</span></label>
                            <input type="text" id="judul" name="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $konten->judul) }}"
                                   placeholder="Masukkan judul konten…" maxlength="255">
                            <div id="judul-counter" class="char-counter">0 / 255</div>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Isi konten --}}
                        <div class="mb-3">
                            <label for="isi">Isi Konten <span class="required-mark">*</span></label>
                            <textarea id="isi" name="isi"
                                      class="form-control summernote @error('isi') is-invalid @enderror">{!! old('isi', $konten->isi) !!}</textarea>
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

                        <label for="gambar-input" class="gambar-wrap">
                            @if($konten->gambar)
                                <img id="gambar-preview" src="{{ asset('storage/' . $konten->gambar) }}" alt="Gambar saat ini">
                            @else
                                <img id="gambar-preview" src="" alt="" style="display:none;">
                            @endif
                            <div class="gambar-placeholder" id="gambar-placeholder" style="{{ $konten->gambar ? 'display:none;' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>Klik untuk ganti gambar<br><small>JPG, PNG, WEBP – maks 3 MB</small></div>
                            </div>
                        </label>
                        <input type="file" id="gambar-input" name="gambar"
                               class="d-none @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @if($konten->gambar)
                            <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
                        @endif
                        @error('gambar')
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

                        {{-- Jenis Konten --}}
                        <div class="mb-3">
                            <label>Jenis Konten</label>
                            <div class="form-control" style="background:#f1f5f9;cursor:default;">
                                {{ $jenisOptions[$jenis] ?? ucfirst($jenis) }}
                            </div>
                            <div class="form-text">Jenis konten tidak dapat diubah setelah dibuat.</div>
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label for="id_kategori">Kategori</label>
                            <select name="id_kategori" id="id_kategori"
                                    class="form-select @error('id_kategori') is-invalid @enderror">
                                <option value="">-- Tanpa Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}"
                                        {{old('id_kategori', $konten->kategori->first()?->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status (hanya superadmin) --}}
                        @if($isSuperAdmin)
                        <div class="mb-3">
                            <label>Status Publikasi <span class="required-mark">*</span></label>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach(['aktif' => ['Aktif','#16a34a','check-circle'], 'pending' => ['Menunggu','#d97706','clock'], 'nonaktif' => ['Nonaktif','#dc2626','ban']] as $val => [$lbl, $col, $ico])
                                <div>
                                    <input type="radio" id="status_{{ $val }}" name="status"
                                           value="{{ $val }}" class="status-radio"
                                           {{ old('status', $konten->status) === $val ? 'checked' : '' }}>
                                    <label for="status_{{ $val }}" class="status-label">
                                        <i class="fas fa-{{ $ico }}" style="color:{{ $col }}"></i>
                                        {{ $lbl }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('status')
                                <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                        {{-- Non-superadmin: tampilkan status saja (read-only) --}}
                        <div class="mb-3">
                            <label>Status Saat Ini</label>
                            <div class="mt-1">
                                @if($konten->status === 'aktif')
                                    <span class="badge" style="background:#dcfce7;color:#15803d;font-size:.8rem;padding:5px 12px;">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @elseif($konten->status === 'pending')
                                    <span class="badge" style="background:#fef9c3;color:#854d0e;font-size:.8rem;padding:5px 12px;">
                                        <i class="fas fa-clock me-1"></i>Menunggu Persetujuan
                                    </span>
                                @else
                                    <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:.8rem;padding:5px 12px;">
                                        <i class="fas fa-ban me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-update w-100">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('konten.index', $jenis) }}" class="btn btn-cancel text-center">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection

@section('scripts')
@include('pages.konten.konten_summernote')
@endsection
