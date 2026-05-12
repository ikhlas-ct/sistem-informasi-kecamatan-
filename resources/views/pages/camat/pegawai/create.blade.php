@extends('layouts.user.user')

@section('title', 'Tambah Pegawai')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent: #1a73e8; --accent-light: #e8f0fe; --accent-shadow: rgba(26,115,232,.18); }

    .container { padding-left:28px; padding-right:24px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 22px;
               display:flex; align-items:center; justify-content:space-between; gap:16px;
               flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden;
               box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
                       border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center;
               justify-content:center; font-size:1.05rem; flex-shrink:0;
               background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px;
                       border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent);
                       display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    /* ── Form ── */
    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
                                   padding:8px 12px; color:#334155; background:#f8fafc;
                                   transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff;
                                               box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#b0bec5; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }
    .invalid-feedback { font-size:.78rem; }

    /* ── Tipe pills ── */
    .tipe-radio { display:none; }
    .tipe-label { display:flex; align-items:center; gap:8px; padding:10px 14px; border-radius:10px;
                  font-size:.82rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0;
                  background:#f8fafc; color:#64748b; transition:all .15s; width:100%; }
    .tipe-radio:checked + .tipe-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }
    .tipe-radio:disabled + .tipe-label { opacity:.45; cursor:not-allowed; }
    .tipe-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center;
                 justify-content:center; font-size:.85rem; flex-shrink:0; }

    /* ── Foto drop zone ── */
    .foto-drop { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px;
                 display:flex; flex-direction:column; align-items:center; justify-content:center;
                 cursor:pointer; overflow:hidden; transition:border-color .2s,background .2s;
                 background:#fafbfc; position:relative; }
    .foto-drop:hover, .foto-drop.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #foto-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:none; }
    .foto-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .foto-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .foto-placeholder span { font-size:.78rem; }

    /* ── Social media ── */
    .social-prefix { background:#f1f5f9; border:1.5px solid #e2e8f0; border-right:none;
                     border-radius:10px 0 0 10px; padding:8px 12px; font-size:.82rem; color:#64748b; }
    .social-input { border-radius:0 10px 10px 0 !important; }

    /* ── Buttons ── */
    .btn-submit { background:linear-gradient(135deg,var(--accent),#1558b0); border:none; border-radius:10px;
                  font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }

    /* ── Nagari alert ── */
    #nagari-section { transition:all .25s; }
    .info-notice { background:#e0f2fe; border-radius:10px; padding:10px 14px; font-size:.82rem;
                   color:#0369a1; display:flex; align-items:center; gap:8px; margin-bottom:1rem; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h5 class="ph-title">Tambah Pegawai</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pegawai.index') }}" class="btn btn-cancel btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert mb-3" style="background:#fee2e2;color:#991b1b;border-radius:10px;border:none;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">

            {{-- ── KOLOM KIRI ── --}}
            <div class="col-lg-8">

                {{-- Data Diri --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user"></i> Data Diri Pegawai</div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Nama Lengkap <span class="required-mark">*</span></label>
                                <input type="text" name="nama_pegawai"
                                       class="form-control @error('nama_pegawai') is-invalid @enderror"
                                       value="{{ old('nama_pegawai') }}" placeholder="Masukkan nama lengkap…">
                                @error('nama_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>NIP <span class="required-mark">*</span></label>
                                <input type="text" name="nip"
                                       class="form-control @error('nip') is-invalid @enderror"
                                       value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" maxlength="20">
                                <div class="form-text">Digunakan sebagai username login.</div>
                                @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>NIK <span class="required-mark">*</span></label>
                                <input type="text" name="nik"
                                       class="form-control @error('nik') is-invalid @enderror"
                                       value="{{ old('nik') }}" placeholder="16 digit NIK" maxlength="16">
                                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Jenis Kelamin <span class="required-mark">*</span></label>
                                <select name="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki"  {{ old('jenis_kelamin')=='Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan"  {{ old('jenis_kelamin')=='Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>No. HP</label>
                                <input type="text" name="nohp_pegawai"
                                       class="form-control @error('nohp_pegawai') is-invalid @enderror"
                                       value="{{ old('nohp_pegawai') }}" placeholder="08xxxxxxxxxx" maxlength="20">
                                @error('nohp_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Email</label>
                                <input type="email" name="email_pegawai"
                                       class="form-control @error('email_pegawai') is-invalid @enderror"
                                       value="{{ old('email_pegawai') }}" placeholder="email@contoh.com">
                                @error('email_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Alamat</label>
                                <input type="text" name="alamat_pegawai"
                                       class="form-control @error('alamat_pegawai') is-invalid @enderror"
                                       value="{{ old('alamat_pegawai') }}" placeholder="Alamat lengkap…">
                                @error('alamat_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Deskripsi / Bio</label>
                                <textarea name="deskripsi" rows="3"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          placeholder="Deskripsi singkat…">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jabatan & Kepangkatan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-briefcase"></i> Jabatan & Kepangkatan</div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Pangkat / Golongan <span class="required-mark">*</span></label>
                                <input type="text" name="pangkat_golongan"
                                       class="form-control @error('pangkat_golongan') is-invalid @enderror"
                                       value="{{ old('pangkat_golongan') }}" placeholder="Contoh: Penata Muda / III-a">
                                @error('pangkat_golongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Jabatan Struktural <span class="required-mark">*</span></label>
                                <input type="text" name="jabatan"
                                       class="form-control @error('jabatan') is-invalid @enderror"
                                       value="{{ old('jabatan') }}" placeholder="Contoh: Kepala Seksi…">
                                @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sosial Media --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-share-alt"></i> Media Sosial</div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Instagram</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-instagram"></i></span>
                                    <input type="url" name="instagram" class="form-control social-input @error('instagram') is-invalid @enderror"
                                           value="{{ old('instagram') }}" placeholder="https://instagram.com/username">
                                    @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Twitter / X</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-twitter"></i></span>
                                    <input type="url" name="twitter" class="form-control social-input @error('twitter') is-invalid @enderror"
                                           value="{{ old('twitter') }}" placeholder="https://twitter.com/username">
                                    @error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Facebook</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-facebook"></i></span>
                                    <input type="url" name="facebook" class="form-control social-input @error('facebook') is-invalid @enderror"
                                           value="{{ old('facebook') }}" placeholder="https://facebook.com/username">
                                    @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── KOLOM KANAN ── --}}
            <div class="col-lg-4">

                {{-- Foto Profil --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-camera"></i> Foto Profil</div>

                        <label for="foto-input" id="foto-drop-zone" class="foto-drop">
                            <img id="foto-preview" src="" alt="Preview">
                            <div class="foto-placeholder" id="foto-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret foto ke sini<br>
                                    <small>JPG, PNG, WEBP – maks 3 MB</small>
                                </span>
                            </div>
                        </label>
                        <input type="file" id="foto-input" name="foto_profil"
                               class="d-none @error('foto_profil') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @error('foto_profil')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tipe Pegawai --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-sitemap"></i> Tipe Pegawai</div>

                        @error('tipe')
                            <div class="text-danger mb-2" style="font-size:.78rem;"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</div>
                        @enderror

                        <div class="d-flex flex-column gap-2">

                            @if(in_array('camat', $allowedTipes))
                            <div>
                                <input type="radio" id="tipe_camat" name="tipe" value="camat" class="tipe-radio"
                                       {{ old('tipe')=='camat' ? 'checked' : '' }}
                                       {{ $camatExists ? 'disabled' : '' }}>
                                <label for="tipe_camat" class="tipe-label">
                                    <div class="tipe-icon" style="background:#dbeafe;color:#1e40af;"><i class="fas fa-user-tie"></i></div>
                                    <div>
                                        <div>Camat</div>
                                        <div style="font-size:.72rem;font-weight:400;color:#94a3b8;">
                                            {{ $camatExists ? 'Sudah ada camat' : 'Admin tertinggi sistem' }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            @if(in_array('staf_camat', $allowedTipes))
                            <div>
                                <input type="radio" id="tipe_staf_camat" name="tipe" value="staf_camat" class="tipe-radio"
                                       {{ old('tipe')=='staf_camat' ? 'checked' : '' }}>
                                <label for="tipe_staf_camat" class="tipe-label">
                                    <div class="tipe-icon" style="background:#ede9fe;color:#5b21b6;"><i class="fas fa-id-badge"></i></div>
                                    <div>
                                        <div>Staf Camat</div>
                                        <div style="font-size:.72rem;font-weight:400;color:#94a3b8;">Kelola pegawai nagari</div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            @if(in_array('kepala_nagari', $allowedTipes))
                            <div>
                                <input type="radio" id="tipe_kepala" name="tipe" value="kepala_nagari" class="tipe-radio tipe-nagari"
                                       {{ old('tipe')=='kepala_nagari' ? 'checked' : '' }}>
                                <label for="tipe_kepala" class="tipe-label">
                                    <div class="tipe-icon" style="background:#ccfbf1;color:#0f766e;"><i class="fas fa-user-shield"></i></div>
                                    <div>
                                        <div>Kepala Nagari</div>
                                        <div style="font-size:.72rem;font-weight:400;color:#94a3b8;">1 per nagari</div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            @if(in_array('staf_nagari', $allowedTipes))
                            <div>
                                <input type="radio" id="tipe_staf" name="tipe" value="staf_nagari" class="tipe-radio tipe-nagari"
                                       {{ old('tipe')=='staf_nagari' ? 'checked' : '' }}>
                                <label for="tipe_staf" class="tipe-label">
                                    <div class="tipe-icon" style="background:#dcfce7;color:#15803d;"><i class="fas fa-users"></i></div>
                                    <div>
                                        <div>Staf Nagari</div>
                                        <div style="font-size:.72rem;font-weight:400;color:#94a3b8;">Pegawai tingkat nagari</div>
                                    </div>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pilih Nagari (muncul jika tipe nagari) --}}
                <div class="card section-card" id="nagari-section" style="{{ in_array(old('tipe'), ['kepala_nagari','staf_nagari']) ? '' : 'display:none;' }}">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Nagari</div>

                        @if($lockedNagari)
                            {{-- Kepala nagari: nagari dikunci --}}
                            <div class="info-notice">
                                <i class="fas fa-lock"></i>
                                Nagari dikunci sesuai nagari Anda.
                            </div>
                            <input type="hidden" name="id_nagari" value="{{ $lockedNagari }}">
                            <div class="form-control" style="background:#f1f5f9;cursor:default;">
                                {{ $nagaris->firstWhere('id', $lockedNagari)?->nama_nagari ?? '-' }}
                            </div>
                        @else
                            <label>Pilih Nagari <span class="required-mark">*</span></label>
                            <select name="id_nagari" id="nagari-select"
                                    class="form-select @error('id_nagari') is-invalid @enderror">
                                <option value="">-- Pilih Nagari --</option>
                                @foreach($nagaris as $nagari)
                                    <option value="{{ $nagari->id }}"
                                            {{ old('id_nagari')==$nagari->id ? 'selected' : '' }}
                                            data-has-kepala="{{ in_array($nagari->id, $nagariWithKepala) ? '1' : '0' }}">
                                        {{ $nagari->nama_nagari }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_nagari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="kepala-warning" class="text-danger mt-1" style="font-size:.78rem;display:none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>Nagari ini sudah memiliki Kepala Nagari.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Akun Login --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-key"></i> Akun Login</div>

                        <div class="mb-3">
                            <label>Password <span class="required-mark">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min. 8 karakter">
                                <button type="button" class="btn btn-outline-secondary toggle-pw"
                                        data-target="password" style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label>Konfirmasi Password <span class="required-mark">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" placeholder="Ulangi password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw"
                                        data-target="password_confirmation" style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="fas fa-save me-2"></i> Simpan Pegawai
                    </button>
                    <a href="{{ route('pegawai.index') }}" class="btn btn-cancel text-center">
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
(function () {
    /* ── Foto preview ── */
    const fotoInput = document.getElementById('foto-input');
    const fotoDrop  = document.getElementById('foto-drop-zone');
    const fotoPreview = document.getElementById('foto-preview');
    const fotoPlaceholder = document.getElementById('foto-placeholder');

    fotoInput?.addEventListener('change', function () {
        if (this.files[0]) previewFoto(this.files[0]);
    });
    ['dragover','dragleave','drop'].forEach(evt => {
        fotoDrop?.addEventListener(evt, function (e) {
            e.preventDefault();
            if (evt === 'dragover') this.classList.add('drag-over');
            else this.classList.remove('drag-over');
            if (evt === 'drop' && e.dataTransfer.files[0]) {
                fotoInput.files = e.dataTransfer.files;
                previewFoto(e.dataTransfer.files[0]);
            }
        });
    });
    function previewFoto(file) {
        const reader = new FileReader();
        reader.onload = e => {
            fotoPreview.src = e.target.result;
            fotoPreview.style.display = 'block';
            fotoPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    /* ── Tampilkan / sembunyikan section nagari ── */
    const nagariSection = document.getElementById('nagari-section');
    const nagariSelect  = document.getElementById('nagari-select');
    const kepalaWarning = document.getElementById('kepala-warning');

    function toggleNagari() {
        const selected = document.querySelector('.tipe-radio:checked');
        const isNagari = selected && ['kepala_nagari','staf_nagari'].includes(selected.value);
        nagariSection && (nagariSection.style.display = isNagari ? '' : 'none');
        checkKepalaWarning();
    }

    function checkKepalaWarning() {
        if (!nagariSelect || !kepalaWarning) return;
        const selected = document.querySelector('.tipe-radio:checked');
        const opt      = nagariSelect.options[nagariSelect.selectedIndex];
        const hasKepala = opt && opt.dataset.hasKepala === '1';
        const isKepala  = selected && selected.value === 'kepala_nagari';
        kepalaWarning.style.display = (hasKepala && isKepala) ? '' : 'none';
    }

    document.querySelectorAll('.tipe-radio').forEach(r => {
        r.addEventListener('change', toggleNagari);
    });
    nagariSelect?.addEventListener('change', checkKepalaWarning);

    toggleNagari(); // init

    /* ── Toggle password ── */
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', function () {
            const inp = document.getElementById(this.dataset.target);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
})();
</script>
@endsection
