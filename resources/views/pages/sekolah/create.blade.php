@extends('layouts.user.user')

@section('title', 'Tambah Sekolah')

@section('styles')
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
            {{-- PERBAIKAN: superadmin dapat memilih masyarakat dari nagari mana pun --}}
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
                                {{-- Camat / Staf Camat: bebas pilih nagari (tidak lagi mengontrol daftar user) --}}
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
                                {{-- Kepala Nagari / Staf Nagari: nagari terkunci otomatis --}}
                                <div class="nagari-locked">
                                    <i class="fas fa-lock"></i>
                                    {{ $nagariTerpilih?->nama_nagari ?? '-' }}
                                    <span style="font-size:.75rem;font-weight:400;color:#64748b;margin-left:4px;">(otomatis sesuai nagari Anda)</span>
                                </div>
                                {{-- Hidden field agar id_nagari ikut tersubmit --}}
                                <input type="hidden" name="id_nagari" value="{{ $nagariTerpilih?->id }}">
                            @endif
                        </div>

                        {{-- ── USER / KEPALA SEKOLAH ── --}}
                        {{--
                            PERBAIKAN: Daftar masyarakat kini dimuat langsung dari server untuk semua role.
                            - Superadmin  : $userMasyarakat berisi semua masyarakat (tanpa filter nagari)
                            - Pegawai nagari: $userMasyarakat berisi masyarakat di nagarinya saja
                            AJAX tidak lagi diperlukan.
                        --}}
                        <div class="mb-3">
                            <label for="id_user">Kepala Sekolah / Administrator <span class="required-mark">*</span></label>
                            <select id="id_user" name="id_user"
                                    class="form-select @error('id_user') is-invalid @enderror">
                                <option value="">-- Pilih Administrator --</option>
                                @forelse($userMasyarakat as $u)
                                    <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nip_nik }}
                                        @if($u->masyarakat?->nama_masyarakat)
                                            – {{ $u->masyarakat->nama_masyarakat }}
                                        @endif
                                        @if($u->masyarakat?->nagari?->nama_nagari && $bisaPilihNagari)
                                            {{-- Superadmin: tampilkan asal nagari agar mudah dibedakan --}}
                                            ({{ $u->masyarakat->nagari->nama_nagari }})
                                        @endif
                                    </option>
                                @empty
                                    <option disabled>Tidak ada masyarakat tersedia</option>
                                @endforelse
                            </select>
                            @error('id_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                @if($bisaPilihNagari)
                                    Pilih masyarakat yang akan menjadi administrator. Nama nagari asal ditampilkan dalam tanda kurung.
                                @else
                                    Pilih masyarakat yang akan menjadi kepala sekolah / administrator akun sekolah.
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
<script>
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

// PERBAIKAN: AJAX user-by-nagari dihapus.
// Daftar masyarakat kini langsung tersedia dari server untuk semua role.
// Superadmin dapat memilih dari semua masyarakat tanpa harus pilih nagari dulu.
</script>
@endsection
