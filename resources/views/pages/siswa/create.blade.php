@extends('layouts.user.user')

@section('title', 'Tambah Siswa')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body,.card,label,.btn{font-family:'Plus Jakarta Sans',sans-serif;}
    :root{--accent:#0d9488;--accent-light:#ccfbf1;}
    .container{padding-left:28px;padding-right:24px;}

    .ph-card{background:#fff;border:1px solid #e9ecef;border-radius:14px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:1.25rem;position:relative;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.05);}
    .ph-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:14px 0 0 14px;background:var(--accent);}
    .ph-left{display:flex;align-items:center;gap:12px;}
    .ph-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;background:var(--accent-light);color:var(--accent);}
    .ph-title{font-size:1.05rem;font-weight:700;color:#1e293b;margin:0;}
    .ph-breadcrumb{display:flex;align-items:center;gap:4px;list-style:none;padding:0;margin:4px 0 0;}
    .ph-breadcrumb li{display:flex;align-items:center;}
    .ph-breadcrumb li+li::before{content:'›';color:#cbd5e1;font-size:.7rem;margin:0 4px;}
    .ph-breadcrumb a{font-size:.75rem;color:var(--accent);text-decoration:none;}
    .ph-breadcrumb .bc-active{font-size:.75rem;color:#94a3b8;}

    .section-card{border:none;border-radius:14px;box-shadow:0 1px 8px rgba(0,0,0,.06);margin-bottom:1.25rem;}
    .section-card .card-body{padding:20px 22px;}
    .section-divider{border-left:4px solid var(--accent);background:#f8f9fa;padding:7px 13px;border-radius:0 6px 6px 0;font-weight:700;font-size:.82rem;color:var(--accent);display:flex;align-items:center;gap:8px;margin-bottom:1.1rem;}

    .form-label{font-size:.8rem;font-weight:600;color:#475569;margin-bottom:5px;}
    .form-label .req{color:#ef4444;}
    .form-control,.form-select{border-radius:10px;border:1.5px solid #e2e8f0;font-size:.84rem;padding:9px 12px;color:#334155;background:#f8fafc;transition:border-color .2s,box-shadow .2s;}
    .form-control:focus,.form-select:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 15%,transparent);}
    .form-control.is-invalid,.form-select.is-invalid{border-color:#ef4444;}
    .invalid-feedback{font-size:.75rem;}

    .locked-field{background:#f1f5f9;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 12px;font-size:.84rem;color:#64748b;display:flex;align-items:center;gap:8px;}
    .locked-field i{color:#94a3b8;font-size:.8rem;}

    .input-pw{position:relative;}
    .input-pw .form-control{padding-right:40px;}
    .pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.85rem;padding:0;}
    .pw-toggle:hover{color:var(--accent);}

    /* Mode tab */
    .mode-tabs{display:flex;gap:8px;margin-bottom:16px;}
    .mode-tab{flex:1;padding:10px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.82rem;font-weight:600;cursor:pointer;text-align:center;background:#f8fafc;color:#64748b;transition:all .2s;}
    .mode-tab.active{border-color:var(--accent);background:var(--accent-light);color:var(--accent);}
    .mode-tab:hover:not(.active){border-color:#cbd5e1;background:#f1f5f9;}

    /* Foto upload */
    .foto-upload-wrap{border:2px dashed #e2e8f0;border-radius:14px;padding:20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#fafbfc;}
    .foto-upload-wrap:hover{border-color:var(--accent);background:#f0fdfa;}
    .foto-preview-wrap{display:none;position:relative;width:90px;height:90px;margin:0 auto 8px;}
    .foto-preview-wrap img{width:100%;height:100%;border-radius:50%;object-fit:cover;border:3px solid var(--accent-light);}
    .foto-remove-btn{position:absolute;top:-4px;right:-4px;width:22px;height:22px;border-radius:50%;background:#ef4444;border:none;color:#fff;font-size:.65rem;display:flex;align-items:center;justify-content:center;cursor:pointer;}

    .info-box{background:#f0fdfa;border:1.5px solid var(--accent-light);border-radius:10px;padding:10px 14px;font-size:.8rem;color:#0f766e;display:flex;align-items:flex-start;gap:8px;}

    .btn-simpan{background:linear-gradient(135deg,var(--accent),#0f766e);border:none;border-radius:10px;font-weight:700;font-size:.85rem;padding:10px 28px;color:#fff;}
    .btn-simpan:hover{filter:brightness(1.07);color:#fff;}
    .btn-batal{border-radius:10px;font-size:.85rem;border:1.5px solid #e2e8f0;color:#64748b;padding:9px 20px;}
    .btn-batal:hover{background:#f8fafc;}
    .btn-simpan .spinner-border{width:.85rem;height:.85rem;border-width:2px;}
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
                <h5 class="ph-title">Tambah Siswa</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('siswa.index') }}">Data Siswa</a></li>
                    <li><span class="bc-active">Tambah Baru</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('siswa.index') }}" class="btn btn-batal btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius:10px;font-size:.82rem;">
        <i class="fas fa-exclamation-circle me-2"></i><strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data" id="form-create">
        @csrf
        <input type="hidden" name="mode_akun" id="input-mode-akun" value="{{ old('mode_akun','pilih') }}">

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- ── CARD: Akun Siswa (Tab Pilih / Baru) ── --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user-circle"></i> Akun Siswa</div>

                        <div class="mode-tabs">
                            <div class="mode-tab {{ old('mode_akun','pilih') === 'pilih' ? 'active' : '' }}"
                                 id="tab-pilih" onclick="setMode('pilih')">
                                <i class="fas fa-search me-1"></i> Pilih dari Masyarakat
                            </div>
                            <div class="mode-tab {{ old('mode_akun') === 'baru' ? 'active' : '' }}"
                                 id="tab-baru" onclick="setMode('baru')">
                                <i class="fas fa-user-plus me-1"></i> Daftarkan Baru
                            </div>
                        </div>

                        {{-- ───────────── PANEL: PILIH ───────────── --}}
                        <div id="panel-pilih" style="{{ old('mode_akun','pilih') === 'pilih' ? '' : 'display:none' }}">
                            <div class="info-box mb-3">
                                <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                                <span>Pilih masyarakat yang sudah terdaftar. Akunnya akan otomatis ditandai sebagai siswa.</span>
                            </div>
                            <div>
                                <label class="form-label">Masyarakat <span class="req">*</span></label>
                                <select name="id_user_masyarakat" id="select-masyarakat"
                                        class="form-select @error('id_user_masyarakat') is-invalid @enderror">
                                    <option value="">— Pilih Masyarakat —</option>
                                    @foreach($masyarakatList as $m)
                                        <option value="{{ $m['id'] }}"
                                            {{ old('id_user_masyarakat') == $m['id'] ? 'selected' : '' }}>
                                            {{ $m['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_user_masyarakat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if($masyarakatList->isEmpty())
                                <div class="mt-2" style="font-size:.78rem;color:#f59e0b;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Tidak ada masyarakat tersedia. Gunakan tab "Daftarkan Baru".
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- ───────────── PANEL: BUAT BARU ───────────── --}}
                        <div id="panel-baru" style="{{ old('mode_akun') === 'baru' ? '' : 'display:none' }}">
                            <div class="info-box mb-3">
                                <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                                <span>
                                    Isi data masyarakat dan akun login siswa secara bersamaan.
                                    <strong>NIK</strong> digunakan sebagai username login.
                                </span>
                            </div>

                            {{-- ── Sub-section: Data Login ── --}}
                            <div class="mb-3 pb-3" style="border-bottom:1px dashed #e2e8f0;">
                                <div style="font-size:.78rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">
                                    <i class="fas fa-key me-1"></i> Data Login
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">NIK <span class="req">*</span></label>
                                        <input type="text" name="nik"
                                               class="form-control @error('nik') is-invalid @enderror"
                                               value="{{ old('nik') }}"
                                               placeholder="16 digit NIK" maxlength="16">
                                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Password <span class="req">*</span></label>
                                        <div class="input-pw">
                                            <input type="password" name="password" id="pw1"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="Min. 6 karakter">
                                            <button type="button" class="pw-toggle" onclick="togglePw('pw1',this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Konfirmasi Password <span class="req">*</span></label>
                                        <div class="input-pw">
                                            <input type="password" name="password_confirmation" id="pw2"
                                                   class="form-control" placeholder="Ulangi password">
                                            <button type="button" class="pw-toggle" onclick="togglePw('pw2',this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Sub-section: Data Masyarakat ── --}}
                            <div class="mb-1">
                                <div style="font-size:.78rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">
                                    <i class="fas fa-id-card me-1"></i> Data Masyarakat
                                    <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#cbd5e1;">— field wajib saja</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                                        <input type="text" name="nama_masyarakat"
                                               class="form-control @error('nama_masyarakat') is-invalid @enderror"
                                               value="{{ old('nama_masyarakat') }}"
                                               placeholder="Nama lengkap siswa">
                                        @error('nama_masyarakat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. Kartu Keluarga (KK) <span class="req">*</span></label>
                                        <input type="text" name="kk"
                                               class="form-control @error('kk') is-invalid @enderror"
                                               value="{{ old('kk') }}"
                                               placeholder="16 digit nomor KK" maxlength="16">
                                        @error('kk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="jenis_kelamin"
                                                class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">— Pilih —</option>
                                            <option value="laki-laki"  {{ old('jenis_kelamin') === 'laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="perempuan"  {{ old('jenis_kelamin') === 'perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" name="no_hp"
                                               class="form-control @error('no_hp') is-invalid @enderror"
                                               value="{{ old('no_hp') }}"
                                               placeholder="08xxxxxxxxxx">
                                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nagari</label>
                                        <select name="id_nagari_masy"
                                                class="form-select @error('id_nagari_masy') is-invalid @enderror">
                                            <option value="">— Pilih Nagari —</option>
                                            @foreach($nagariAllList as $n)
                                                <option value="{{ $n->id }}"
                                                    {{ old('id_nagari_masy') == $n->id ? 'selected' : '' }}>
                                                    {{ $n->nama_nagari }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_nagari_masy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>{{-- end panel-baru --}}

                    </div>
                </div>

                {{-- ── CARD: Data Siswa ── --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-graduation-cap"></i> Data Siswa</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis"
                                       class="form-control @error('nis') is-invalid @enderror"
                                       value="{{ old('nis') }}" placeholder="Nomor Induk Siswa">
                                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas"
                                       class="form-control @error('kelas') is-invalid @enderror"
                                       value="{{ old('kelas') }}" placeholder="Contoh: X IPA 1">
                                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── CARD: Penempatan Sekolah ── --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-school"></i> Penempatan Sekolah</div>

                        @if($isAdminSekolah)
                            {{-- Sekolah terkunci --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nagari</label>
                                    <div class="locked-field"><i class="fas fa-lock"></i>{{ $nagariLocked?->nama_nagari ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sekolah</label>
                                    <div class="locked-field"><i class="fas fa-lock"></i>{{ $sekolahLocked?->nama_sekolah ?? '-' }}</div>
                                    <input type="hidden" name="id_sekolah" value="{{ $sekolahLocked?->id_sekolah }}">
                                </div>
                            </div>

                        @elseif($isNagari)
                            {{-- Nagari terkunci, pilih sekolah di nagari itu --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nagari</label>
                                    <div class="locked-field"><i class="fas fa-lock"></i>{{ $nagariLocked?->nama_nagari ?? '-' }}</div>
                                    <input type="hidden" id="selected-nagari" value="{{ $nagariLocked?->id }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sekolah <span class="req">*</span></label>
                                    <select name="id_sekolah" id="select-sekolah"
                                            class="form-select @error('id_sekolah') is-invalid @enderror">
                                        <option value="">Memuat sekolah...</option>
                                    </select>
                                    @error('id_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                        @else
                            {{-- SuperAdmin: pilih nagari → sekolah --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nagari</label>
                                    <select id="select-nagari" class="form-select">
                                        <option value="">— Pilih Nagari —</option>
                                        @foreach($nagariList as $n)
                                            <option value="{{ $n->id }}">{{ $n->nama_nagari }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size:.72rem;">Pilih nagari untuk filter sekolah</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sekolah <span class="req">*</span></label>
                                    <select name="id_sekolah" id="select-sekolah"
                                            class="form-select @error('id_sekolah') is-invalid @enderror">
                                        <option value="">— Pilih Nagari dulu —</option>
                                    </select>
                                    @error('id_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Foto Profil (hanya untuk mode baru) --}}
                <div class="card section-card" id="card-foto">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-camera"></i> Foto Profil
                            <span style="font-size:.7rem;color:#94a3b8;font-weight:400;">(opsional)</span>
                        </div>
                        <div class="info-box mb-3" style="font-size:.75rem;" id="info-foto-mode">
                            <i class="fas fa-info-circle flex-shrink-0"></i>
                            <span id="info-foto-text">Foto disimpan ke data masyarakat baru.</span>
                        </div>
                        <input type="file" name="foto_profil" id="foto-input"
                               accept="image/jpg,image/jpeg,image/png,image/webp" class="d-none">
                        <div class="foto-upload-wrap" id="foto-wrap"
                             onclick="document.getElementById('foto-input').click()">
                            <div class="foto-preview-wrap" id="preview-wrap">
                                <img id="foto-preview" src="" alt="Preview">
                                <button type="button" class="foto-remove-btn" id="btn-remove-foto">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="foto-placeholder">
                                <div style="font-size:1.6rem;color:#cbd5e1;margin-bottom:4px;"><i class="fas fa-camera"></i></div>
                                <div style="font-size:.78rem;color:#64748b;font-weight:600;">Klik untuk upload</div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">JPG, PNG, WEBP · Maks 2 MB</div>
                            </div>
                        </div>
                        @error('foto_profil')
                        <div class="text-danger mt-1" style="font-size:.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Panduan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-lightbulb"></i> Panduan</div>
                        <ul style="font-size:.79rem;color:#64748b;padding-left:18px;margin:0;line-height:2;">
                            <li><strong>Pilih dari Masyarakat</strong> → gunakan akun yang sudah terdaftar.</li>
                            <li><strong>Daftarkan Baru</strong> → buat akun sekaligus data masyarakat baru. NIK = username login.</li>
                            @if($isSuperAdmin)
                            <li>Bisa pilih sekolah dari <strong>nagari manapun</strong>.</li>
                            @elseif($isNagari)
                            <li>Sekolah terbatas pada <strong>nagari Anda</strong>.</li>
                            @elseif($isAdminSekolah)
                            <li>Siswa otomatis masuk ke <strong>sekolah Anda</strong>.</li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}

        </div>{{-- end row --}}

        <div class="d-flex justify-content-end gap-2 mt-1 mb-4">
            <a href="{{ route('siswa.index') }}" class="btn btn-batal">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-simpan" id="btn-submit">
                <span class="btn-text"><i class="fas fa-save me-1"></i> Simpan</span>
                <span class="spinner-border d-none" role="status"></span>
            </button>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
// ── Mode toggle ────────────────────────────────────────────
function setMode(mode) {
    document.getElementById('input-mode-akun').value = mode;
    document.getElementById('panel-pilih').style.display = mode === 'pilih' ? '' : 'none';
    document.getElementById('panel-baru').style.display  = mode === 'baru'  ? '' : 'none';
    document.getElementById('tab-pilih').classList.toggle('active', mode === 'pilih');
    document.getElementById('tab-baru').classList.toggle('active',  mode === 'baru');

    // Foto hanya relevan untuk mode baru
    const cardFoto  = document.getElementById('card-foto');
    const infoText  = document.getElementById('info-foto-text');
    if (mode === 'baru') {
        cardFoto.style.opacity = '1';
        cardFoto.style.pointerEvents = '';
        infoText.textContent = 'Foto disimpan ke data masyarakat baru.';
    } else {
        cardFoto.style.opacity = '.45';
        cardFoto.style.pointerEvents = 'none';
        infoText.textContent = 'Foto diambil dari data masyarakat yang dipilih.';
    }
}

// Inisialisasi tampilan sesuai mode awal
setMode(document.getElementById('input-mode-akun').value);

// ── Password toggle ────────────────────────────────────────
function togglePw(id, btn) {
    const el = document.getElementById(id);
    const ic = btn.querySelector('i');
    if (el.type === 'password') { el.type = 'text'; ic.classList.replace('fa-eye','fa-eye-slash'); }
    else { el.type = 'password'; ic.classList.replace('fa-eye-slash','fa-eye'); }
}

// ── Foto preview ───────────────────────────────────────────
const fotoInput   = document.getElementById('foto-input');
const fotoPreview = document.getElementById('foto-preview');
const previewWrap = document.getElementById('preview-wrap');
const placeholder = document.getElementById('foto-placeholder');
const removeBtn   = document.getElementById('btn-remove-foto');

fotoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        fotoPreview.src = e.target.result;
        previewWrap.style.display = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
removeBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    fotoInput.value = '';
    fotoPreview.src = '';
    previewWrap.style.display = 'none';
    placeholder.style.display = 'block';
});

// ── Sekolah AJAX ───────────────────────────────────────────
@if($isSuperAdmin)
const selectNagari  = document.getElementById('select-nagari');
const selectSekolah = document.getElementById('select-sekolah');
const oldSekolah    = "{{ old('id_sekolah') }}";

selectNagari.addEventListener('change', function () {
    const idNagari = this.value;
    selectSekolah.innerHTML = '<option value="">Memuat...</option>';
    selectSekolah.disabled  = true;
    if (!idNagari) {
        selectSekolah.innerHTML = '<option value="">— Pilih Nagari dulu —</option>';
        selectSekolah.disabled  = false;
        return;
    }
    fetch(`{{ route('siswa.ajax.sekolah-by-nagari') }}?id_nagari=${idNagari}`)
        .then(r => r.json())
        .then(data => {
            selectSekolah.disabled = false;
            selectSekolah.innerHTML = data.length
                ? '<option value="">— Pilih Sekolah —</option>'
                : '<option value="">Tidak ada sekolah aktif</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id_sekolah;
                opt.textContent = `${s.nama_sekolah} (${s.jenjang})`;
                if (String(s.id_sekolah) === String(oldSekolah)) opt.selected = true;
                selectSekolah.appendChild(opt);
            });
        })
        .catch(() => { selectSekolah.innerHTML = '<option value="">Gagal memuat</option>'; selectSekolah.disabled = false; });
});
@endif

@if($isNagari)
(function () {
    const idNagari   = document.getElementById('selected-nagari').value;
    const selSekolah = document.getElementById('select-sekolah');
    const oldSekolah = "{{ old('id_sekolah') }}";
    fetch(`{{ route('siswa.ajax.sekolah-by-nagari') }}?id_nagari=${idNagari}`)
        .then(r => r.json())
        .then(data => {
            selSekolah.innerHTML = '<option value="">— Pilih Sekolah —</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id_sekolah;
                opt.textContent = `${s.nama_sekolah} (${s.jenjang})`;
                if (String(s.id_sekolah) === String(oldSekolah)) opt.selected = true;
                selSekolah.appendChild(opt);
            });
        });
})();
@endif

// ── Spinner submit ─────────────────────────────────────────
document.getElementById('form-create').addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.spinner-border').classList.remove('d-none');
    btn.disabled = true;
});
</script>
@endsection
