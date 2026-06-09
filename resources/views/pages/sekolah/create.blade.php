@extends('layouts.user.user')

@section('title', 'Tambah Sekolah')

@section('styles')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent:#0d9488; --accent-light:#ccfbf1; --accent-shadow:rgba(13,148,136,.18); }

    .container { padding-left:28px; padding-right:24px; }

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

    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control:disabled, .form-select:disabled { background:#f1f5f9; color:#64748b; cursor:not-allowed; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Logo drop zone ── */
    .logo-drop-zone { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s,background .2s; background:#fafbfc; position:relative; }
    .logo-drop-zone:hover, .logo-drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #logo-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:contain; padding:12px; display:none; background:#fff; }
    .logo-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .logo-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .logo-placeholder span { font-size:.78rem; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    /* ── Nagari locked badge ── */
    .nagari-locked { background:var(--accent-light); border:1.5px solid var(--accent); border-radius:10px; padding:9px 14px; font-size:.84rem; color:#0f766e; font-weight:600; display:flex; align-items:center; gap:8px; }

    /* ── Info box ── */
    .info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:10px 14px; font-size:.8rem; color:#1e40af; display:flex; align-items:flex-start; gap:8px; margin-bottom:1rem; }

    /* ── Buttons ── */
    .btn-submit { background:linear-gradient(135deg,var(--accent),#0f766e); border:none; border-radius:10px; font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }
    .spinner-border-sm { width:1rem; height:1rem; }

    /* ── Select2 Custom Theme ── */
    .select2-container--default .select2-selection--single {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .85rem;
        padding: 5px 12px;
        height: 38px;
        color: #334155;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155;
        line-height: 26px;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 6px;
    }
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 3px var(--accent-shadow);
        outline: none;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent);
    }
    .select2-dropdown {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        font-size: .85rem;
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 7px;
        border: 1.5px solid #e2e8f0;
        font-size: .84rem;
        padding: 6px 10px;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--accent);
        outline: none;
    }
    /* Error state untuk Select2 */
    .is-invalid + .select2-container--default .select2-selection--single,
    .select2-container--default.is-invalid .select2-selection--single {
        border-color: #dc3545 !important;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h5 class="ph-title">Tambah Sekolah</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('sekolah.index') }}">Data Sekolah</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('sekolah.index') }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="alert" style="background:#fee2e2;color:#991b1b;border-radius:10px;" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Info: penjelasan role --}}
    @if($bisaPilihNagari)
    <div class="info-box">
        <i class="fas fa-info-circle mt-1"></i>
        <div>
            <strong>Mode Superadmin:</strong> Anda dapat memilih nagari sekolah secara bebas.
            Daftar administrator tersedia dari <strong>semua nagari</strong> — tidak dibatasi nagari tertentu.
        </div>
    </div>
    @else
    <div class="info-box">
        <i class="fas fa-info-circle mt-1"></i>
        <div>
            <strong>Nagari Terkunci:</strong> Sekolah akan otomatis terdaftar di nagari Anda
            ({{ $nagariTerpilih?->nama_nagari ?? '-' }}). Administrator dipilih dari masyarakat nagari Anda.
        </div>
    </div>
    @endif

    <form action="{{ route('sekolah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Informasi Sekolah --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-school"></i> Informasi Sekolah</div>

                        {{-- Nama Sekolah --}}
                        <div class="mb-3">
                            <label for="nama_sekolah">Nama Sekolah <span class="required-mark">*</span></label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah"
                                   class="form-control @error('nama_sekolah') is-invalid @enderror"
                                   value="{{ old('nama_sekolah') }}"
                                   placeholder="Contoh: SD Negeri 01 Nagari Sungai Puar">
                            @error('nama_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            {{-- NPSN --}}
                            <div class="col-md-6">
                                <label for="npsn">NPSN</label>
                                <input type="text" id="npsn" name="npsn"
                                       class="form-control @error('npsn') is-invalid @enderror"
                                       value="{{ old('npsn') }}"
                                       placeholder="Nomor Pokok Sekolah Nasional" maxlength="20">
                                @error('npsn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Jenjang --}}
                            <div class="col-md-6">
                                <label for="jenjang">Jenjang Pendidikan <span class="required-mark">*</span></label>
                                <select id="jenjang" name="jenjang"
                                        class="form-select @error('jenjang') is-invalid @enderror">
                                    <option value="">-- Pilih Jenjang --</option>
                                    @foreach(['TK','PAUD','SD','MI','SMP','MTs','SMA','MA','SMK'] as $j)
                                        <option value="{{ $j }}" {{ old('jenjang') === $j ? 'selected' : '' }}>{{ $j }}</option>
                                    @endforeach
                                </select>
                                @error('jenjang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3 mt-3">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                      class="form-control @error('alamat') is-invalid @enderror"
                                      placeholder="Alamat lengkap sekolah">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            {{-- No HP --}}
                            <div class="col-md-6">
                                <label for="no_hp">No. HP / Telepon</label>
                                <input type="text" id="no_hp" name="no_hp"
                                       class="form-control @error('no_hp') is-invalid @enderror"
                                       value="{{ old('no_hp') }}"
                                       placeholder="08xxxxxxxxxx" maxlength="20">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="email@sekolah.sch.id">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Lokasi & Administrator --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Lokasi & Administrator</div>

                        {{-- ── NAGARI ── --}}
                        <div class="mb-3">
                            <label>Nagari <span class="required-mark">*</span></label>

                            @if($bisaPilihNagari)
                                <select id="id_nagari" name="id_nagari"
                                        class="form-select @error('id_nagari') is-invalid @enderror">
                                    <option value="">-- Pilih Nagari --</option>
                                    @foreach($nagaris as $n)
                                        <option value="{{ $n->id }}" {{ old('id_nagari') == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama_nagari }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_nagari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih nagari tempat sekolah berada.</div>

                            @else
                                <div class="nagari-locked">
                                    <i class="fas fa-lock"></i>
                                    {{ $nagariTerpilih?->nama_nagari ?? '-' }}
                                    <span style="font-size:.75rem;font-weight:400;color:#64748b;margin-left:4px;">(otomatis sesuai nagari Anda)</span>
                                </div>
                                <input type="hidden" name="id_nagari" value="{{ $nagariTerpilih?->id }}">
                            @endif
                        </div>

                        {{-- ── USER / KEPALA SEKOLAH (Select2) ── --}}
                        <div class="mb-3">
                            <label for="id_user">Kepala Sekolah / Administrator <span class="required-mark">*</span></label>

                            {{--
                                Menggunakan Select2 agar bisa di-search ketika data masyarakat banyak.
                                Daftar dimuat server-side (tidak perlu AJAX).
                            --}}
                            <select id="id_user" name="id_user"
                                    class="form-select @error('id_user') is-invalid @enderror"
                                    style="width:100%">
                                <option value="">-- Cari / Pilih Administrator --</option>
                                @forelse($userMasyarakat as $u)
                                    <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}
                                        data-nama="{{ $u->masyarakat?->nama_masyarakat }}"
                                        data-nagari="{{ $bisaPilihNagari ? ($u->masyarakat?->nagari?->nama_nagari ?? '') : '' }}">
                                        {{ $u->nip_nik }}
                                        @if($u->masyarakat?->nama_masyarakat)
                                            – {{ $u->masyarakat->nama_masyarakat }}
                                        @endif
                                        @if($u->masyarakat?->nagari?->nama_nagari && $bisaPilihNagari)
                                            ({{ $u->masyarakat->nagari->nama_nagari }})
                                        @endif
                                    </option>
                                @empty
                                    <option disabled>Tidak ada masyarakat tersedia</option>
                                @endforelse
                            </select>

                            @error('id_user')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                @if($bisaPilihNagari)
                                    Ketik NIK atau nama untuk mencari. Nama nagari asal ditampilkan dalam tanda kurung.
                                @else
                                    Ketik NIK atau nama untuk mencari administrator dari nagari Anda.
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Logo --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-image"></i> Logo Sekolah</div>
                        <label for="logo-input" id="logo-drop-zone" class="logo-drop-zone">
                            <img id="logo-preview" src="" alt="Preview Logo">
                            <div class="logo-placeholder" id="logo-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret logo ke sini<br>
                                    <small>JPG, PNG, WEBP – maks 2 MB</small>
                                </span>
                            </div>
                        </label>
                        <input type="file" id="logo-input" name="logo"
                               class="d-none @error('logo') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @error('logo')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-cog"></i> Status</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['aktif' => ['Aktif','#16a34a','check-circle'], 'nonaktif' => ['Nonaktif','#dc2626','ban']] as $val => [$lbl, $col, $ico])
                            <div>
                                <input type="radio" id="status_{{ $val }}" name="status"
                                       value="{{ $val }}" class="status-radio"
                                       {{ old('status', 'aktif') === $val ? 'checked' : '' }}>
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
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="fas fa-save me-2"></i> Simpan Sekolah
                    </button>
                    <a href="{{ route('sekolah.index') }}" class="btn btn-cancel text-center">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection

@section('scripts')
{{-- jQuery (pastikan sudah ada di layout, jika belum uncomment baris ini) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script> --}}

{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// ── Select2: Dropdown Administrator ───────────────
$(document).ready(function () {
    $('#id_user').select2({
        theme: 'default',
        placeholder: '-- Cari / Pilih Administrator --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () { return 'Tidak ada data yang cocok'; },
            searching:  function () { return 'Mencari...'; },
        },
        // Fungsi matcher: cari by NIK atau nama masyarakat
        matcher: function (params, data) {
            if (!params.term || params.term.trim() === '') return data;
            var term   = params.term.toLowerCase();
            var text   = (data.text || '').toLowerCase();
            var nama   = $(data.element).data('nama')   || '';
            var nagari = $(data.element).data('nagari') || '';
            if (
                text.indexOf(term) > -1 ||
                nama.toLowerCase().indexOf(term) > -1 ||
                nagari.toLowerCase().indexOf(term) > -1
            ) {
                return data;
            }
            return null;
        },
    });

    // Tampilkan error validation di bawah Select2
    @error('id_user')
        $('#id_user').next('.select2-container').find('.select2-selection').css({
            'border-color': '#dc3545',
            'box-shadow': '0 0 0 3px rgba(220,53,69,.15)',
        });
    @enderror
});

// ── Logo preview ─────────────────────────────────
const logoInput   = document.getElementById('logo-input');
const logoPreview = document.getElementById('logo-preview');
const logoHolder  = document.getElementById('logo-placeholder');
const dropZone    = document.getElementById('logo-drop-zone');

function showLogoPreview(file) {
    if (!file || !file.type.match('image.*')) return;
    const reader = new FileReader();
    reader.onload = e => {
        logoPreview.src = e.target.result;
        logoPreview.style.display = 'block';
        logoHolder.style.display  = 'none';
    };
    reader.readAsDataURL(file);
}

logoInput.addEventListener('change', function () {
    if (this.files?.[0]) showLogoPreview(this.files[0]);
});

['dragover','dragleave','drop'].forEach(ev => {
    dropZone.addEventListener(ev, e => {
        e.preventDefault();
        if (ev === 'dragover') dropZone.classList.add('drag-over');
        if (ev === 'dragleave') dropZone.classList.remove('drag-over');
        if (ev === 'drop') {
            dropZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                logoInput.files = dt.files;
                showLogoPreview(file);
            }
        }
    });
});
</script>
@endsection
