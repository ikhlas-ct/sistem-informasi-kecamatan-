@extends('layouts.user.user')

@section('title', 'Edit Masyarakat – ' . Str::limit($masyarakat->nama_masyarakat, 30))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent: #e96c1a; --accent-light: #fff4ed; --accent-shadow: rgba(233,108,26,.18); }

    .container { padding-left:28px; padding-right:24px; }

    /* ── Page Header ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px;
               display:flex; align-items:center; justify-content:space-between; gap:16px;
               flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden;
               box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
                       border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center;
               justify-content:center; font-size:1rem; flex-shrink:0;
               background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }
    .info-badge { display:inline-flex; align-items:center; gap:5px; background:#f1f5f9;
                  border-radius:8px; padding:4px 10px; font-size:.75rem; color:#64748b; }

    /* ── Section cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:20px 22px; }
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
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }
    .invalid-feedback { font-size:.78rem; }

    /* ── Foto ── */
    .foto-wrap { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px;
                 display:flex; align-items:center; justify-content:center; cursor:pointer;
                 overflow:hidden; transition:border-color .2s; background:#fafbfc; position:relative; }
    .foto-wrap:hover { border-color:var(--accent); }
    #foto-preview { width:100%; height:100%; object-fit:cover; }
    .foto-placeholder { text-align:center; color:#94a3b8; position:absolute; }
    .foto-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .foto-placeholder div { font-size:.78rem; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px;
                    font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0;
                    background:#f8fafc; color:#64748b; transition:all .15s; white-space:nowrap; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    /* ── Social ── */
    .social-prefix { background:#f1f5f9; border:1.5px solid #e2e8f0; border-right:none;
                     border-radius:10px 0 0 10px; padding:8px 12px; font-size:.82rem; color:#64748b; }
    .social-input  { border-radius:0 10px 10px 0 !important; }

    /* ── Buttons ── */
    .btn-update { background:linear-gradient(135deg,var(--accent),#c45c10); border:none; border-radius:10px;
                  font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-update:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }

    .info-notice { background:#e0f2fe; border-radius:10px; padding:10px 14px; font-size:.82rem;
                   color:#0369a1; display:flex; align-items:center; gap:8px; margin-bottom:1rem; }
    .info-locked { background:#f0fdf4; border-radius:10px; padding:10px 14px; font-size:.82rem;
                   color:#15803d; display:flex; align-items:center; gap:8px; margin-bottom:.75rem; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <h5 class="ph-title">Edit Masyarakat</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('camat.masyarakat.index') }}">Masyarakat</a></li>
                    <li><span class="bc-active">{{ Str::limit($masyarakat->nama_masyarakat, 30) }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="info-badge">
                <i class="fas fa-calendar-alt"></i>
                Ditambahkan: {{ $masyarakat->created_at?->translatedFormat('d M Y') }}
            </span>
            <a href="{{ route('camat.masyarakat.index') }}" class="btn btn-cancel btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
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

    <form action="{{ route('camat.masyarakat.update', $masyarakat->id_masyarakat) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-3">

            {{-- ── KOLOM KIRI ── --}}
            <div class="col-lg-8">

                {{-- Data Diri --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user"></i> Data Diri Masyarakat</div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Nama Lengkap <span class="required-mark">*</span></label>
                                <input type="text" name="nama_masyarakat"
                                       class="form-control @error('nama_masyarakat') is-invalid @enderror"
                                       value="{{ old('nama_masyarakat', $masyarakat->nama_masyarakat) }}"
                                       placeholder="Masukkan nama lengkap…">
                                @error('nama_masyarakat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>NIK <span class="required-mark">*</span></label>
                                <input type="text" name="nik"
                                       class="form-control @error('nik') is-invalid @enderror"
                                       value="{{ old('nik', $masyarakat->nik) }}" maxlength="16">
                                <div class="form-text">Digunakan sebagai username login.</div>
                                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>No. KK</label>
                                <input type="text" name="kk"
                                       class="form-control @error('kk') is-invalid @enderror"
                                       value="{{ old('kk', $masyarakat->kk) }}" maxlength="16">
                                @error('kk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Jenis Kelamin <span class="required-mark">*</span></label>
                                <select name="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="Laki-laki"  {{ old('jenis_kelamin', $masyarakat->jenis_kelamin)=='Laki-laki'  ? 'selected':'' }}>Laki-laki</option>
                                    <option value="Perempuan"  {{ old('jenis_kelamin', $masyarakat->jenis_kelamin)=='Perempuan'  ? 'selected':'' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>No. HP</label>
                                <input type="text" name="no_hp"
                                       class="form-control @error('no_hp') is-invalid @enderror"
                                       value="{{ old('no_hp', $masyarakat->no_hp) }}" maxlength="20">
                                @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan"
                                       class="form-control @error('pekerjaan') is-invalid @enderror"
                                       value="{{ old('pekerjaan', $masyarakat->pekerjaan) }}">
                                @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Alamat</label>
                                <input type="text" name="alamat"
                                       class="form-control @error('alamat') is-invalid @enderror"
                                       value="{{ old('alamat', $masyarakat->alamat) }}">
                                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label>Deskripsi / Bio</label>
                                <textarea name="deskripsi" rows="3"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          placeholder="Deskripsi singkat…">{{ old('deskripsi', $masyarakat->deskripsi) }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Media Sosial --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-share-alt"></i> Media Sosial</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Instagram</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-instagram"></i></span>
                                    <input type="url" name="instagram" class="form-control social-input @error('instagram') is-invalid @enderror"
                                           value="{{ old('instagram', $masyarakat->instagram) }}">
                                    @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Twitter / X</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-twitter"></i></span>
                                    <input type="url" name="twitter" class="form-control social-input @error('twitter') is-invalid @enderror"
                                           value="{{ old('twitter', $masyarakat->twitter) }}">
                                    @error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Facebook</label>
                                <div class="input-group">
                                    <span class="social-prefix"><i class="fab fa-facebook"></i></span>
                                    <input type="url" name="facebook" class="form-control social-input @error('facebook') is-invalid @enderror"
                                           value="{{ old('facebook', $masyarakat->facebook) }}">
                                    @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Akun & Status --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-cog"></i> Akun & Status</div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label>Status Akun <span class="required-mark">*</span></label>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                @foreach(['aktif'=>['Aktif','#15803d','check-circle'],'nonaktif'=>['Nonaktif','#dc2626','ban']] as $val=>[$lbl,$col,$ico])
                                <div>
                                    <input type="radio" id="status_{{ $val }}" name="status"
                                           value="{{ $val }}" class="status-radio"
                                           {{ old('status', $masyarakat->user?->status) === $val ? 'checked' : '' }}>
                                    <label for="status_{{ $val }}" class="status-label">
                                        <i class="fas fa-{{ $ico }}" style="color:{{ $col }};"></i> {{ $lbl }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('status')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                        </div>

                        {{-- Ganti Password --}}
                        <div class="mb-3">
                            <label>Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Kosongkan jika tidak diganti">
                                <button type="button" class="btn btn-outline-secondary toggle-pw"
                                        data-target="password" style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div>
                            <label>Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" placeholder="Ulangi password baru">
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
                    <button type="submit" class="btn btn-update w-100">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('camat.masyarakat.index') }}" class="btn btn-cancel text-center">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

            </div>

            {{-- ── KOLOM KANAN ── --}}
            <div class="col-lg-4">

                {{-- Foto Profil --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-camera"></i> Foto Profil</div>

                        <label for="foto-input" class="foto-wrap">
                            @if($masyarakat->foto_profil)
                                <img id="foto-preview" src="{{ asset('storage/'.$masyarakat->foto_profil) }}" alt="Foto saat ini">
                            @else
                                <img id="foto-preview" src="" alt="" style="display:none;">
                            @endif
                            <div class="foto-placeholder" id="foto-placeholder"
                                 style="{{ $masyarakat->foto_profil ? 'display:none;' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>Klik untuk ganti foto<br><small>JPG, PNG, WEBP – maks 3 MB</small></div>
                            </div>
                        </label>
                        <input type="file" id="foto-input" name="foto_profil"
                               class="d-none @error('foto_profil') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @if($masyarakat->foto_profil)
                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
                        @endif
                        @error('foto_profil')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Nagari --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-map-marker-alt"></i> Nagari</div>

                        @if($lockedNagari)
                            {{-- Kepala Nagari / Pegawai Nagari: nagari dikunci --}}
                            <div class="info-locked">
                                <i class="fas fa-lock"></i>
                                Nagari dikunci sesuai nagari Anda.
                            </div>
                            <input type="hidden" name="id_nagari" value="{{ $lockedNagari }}">
                            <div class="form-control" style="background:#f1f5f9;cursor:default;">
                                {{ $nagaris->firstWhere('id', $lockedNagari)?->nama_nagari ?? '-' }}
                            </div>
                        @else
                            {{-- Camat / Staf Camat: bebas pilih nagari --}}
                            <div class="info-notice">
                                <i class="fas fa-info-circle"></i>
                                Anda dapat mengubah nagari masyarakat ini.
                            </div>
                            <label>Pilih Nagari</label>
                            <select name="id_nagari"
                                    class="form-select @error('id_nagari') is-invalid @enderror">
                                <option value="">-- Tanpa Nagari --</option>
                                @foreach($nagaris as $nagari)
                                    <option value="{{ $nagari->id }}"
                                            {{ old('id_nagari', $masyarakat->id_nagari)==$nagari->id ? 'selected' : '' }}>
                                        {{ $nagari->nama_nagari }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_nagari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @endif
                    </div>
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
    const fotoInput       = document.getElementById('foto-input');
    const fotoPreview     = document.getElementById('foto-preview');
    const fotoPlaceholder = document.getElementById('foto-placeholder');

    fotoInput?.addEventListener('change', function () {
        if (!this.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            fotoPreview.src = e.target.result;
            fotoPreview.style.display = 'block';
            if (fotoPlaceholder) fotoPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
    });

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
