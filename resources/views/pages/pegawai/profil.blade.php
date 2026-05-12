@extends('layouts.user.user')

@section('title', 'Profil Saya')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, label, .btn, input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }

    .container { padding-left: 28px; padding-right: 24px; }

    /* ── Page Header ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 22px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.5rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: #6366f1;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0; background: #ede9fe; color: #6366f1;
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px;
        list-style: none; padding: 0; margin: 4px 0 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ── Tab Nav ── */
    .setting-tabs {
        display: flex; gap: 4px; flex-wrap: wrap;
        background: #f8fafc; border: 1px solid #e9ecef;
        border-radius: 12px; padding: 5px; margin-bottom: 1.5rem;
    }
    .setting-tab {
        flex: 1; min-width: 110px; text-align: center;
        padding: 8px 12px; border-radius: 9px; font-size: .78rem;
        font-weight: 600; cursor: pointer; border: none;
        background: transparent; color: #64748b;
        transition: all .18s; display: flex; align-items: center;
        justify-content: center; gap: 6px;
    }
    .setting-tab:hover { background: #fff; color: #1e293b; }
    .setting-tab.active { background: #fff; color: #6366f1; box-shadow: 0 1px 6px rgba(0,0,0,.08); }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* ── Cards ── */
    .section-card { border: none; border-radius: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
    .section-card .card-body { padding: 22px 24px; }
    .section-divider {
        border-left: 4px solid #6366f1; background: #f8f9fa;
        padding: 7px 13px; border-radius: 0 6px 6px 0;
        font-weight: 700; font-size: .82rem; color: #6366f1;
        display: flex; align-items: center; gap: 8px; margin-bottom: 1.1rem;
    }

    /* ── Forms ── */
    label { font-size: .83rem; font-weight: 600; color: #475569; }
    .required-mark { color: #dc3545; }
    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 8px 12px; color: #334155;
        background: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6366f1; background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    }
    .form-control::placeholder { color: #b0bec5; }
    .form-text { font-size: .74rem; color: #94a3b8; }
    .input-group-text {
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-right: none; border-radius: 10px 0 0 10px;
        font-size: .85rem; color: #94a3b8;
    }
    .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }

    /* ── Avatar upload zone ── */
    .avatar-wrap {
        position: relative; width: 120px; height: 120px;
        border-radius: 50%; overflow: hidden; cursor: pointer;
        border: 3px solid #e2e8f0; margin: 0 auto;
        transition: border-color .2s;
    }
    .avatar-wrap:hover { border-color: #6366f1; }
    .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-overlay {
        position: absolute; inset: 0; border-radius: 50%;
        background: rgba(99,102,241,.7); display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 2px;
        opacity: 0; transition: opacity .2s;
    }
    .avatar-wrap:hover .avatar-overlay { opacity: 1; }
    .avatar-overlay i { font-size: 1.2rem; color: #fff; }
    .avatar-overlay span { font-size: .65rem; color: #fff; font-weight: 600; }
    .avatar-name { font-size: .82rem; font-weight: 600; color: #1e293b; margin-top: 10px; text-align: center; }
    .avatar-role {
        font-size: .72rem; color: #fff; background: #6366f1;
        border-radius: 20px; padding: 2px 10px; margin-top: 4px;
        display: inline-block;
    }

    /* ── Social rows ── */
    .social-row { display: flex; align-items: center; gap: 10px; margin-bottom: .75rem; }
    .social-icon {
        width: 38px; height: 38px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .social-fb { background: #e7f0fd; color: #1877f2; }
    .social-ig { background: #fce4ec; color: #e1306c; }
    .social-tw { background: #e1f5fe; color: #1da1f2; }

    /* ── Alert ── */
    .alert-success-custom {
        background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d;
        border-radius: 12px; font-size: .85rem; padding: 10px 16px;
    }
    .alert-danger-custom {
        background: #fee2e2; border: 1px solid #fecaca; color: #dc2626;
        border-radius: 12px; font-size: .85rem; padding: 10px 16px;
    }

    /* ── Btn ── */
    .btn-save {
        background: linear-gradient(135deg, #6366f1, #4f46e5); border: none;
        border-radius: 10px; font-weight: 600; font-size: .85rem;
        padding: 9px 24px; color: #fff;
        box-shadow: 0 2px 8px rgba(99,102,241,.35); transition: all .2s;
    }
    .btn-save:hover { filter: brightness(1.07); transform: translateY(-1px); color: #fff; }

    /* ── Password strength ── */
    .strength-bar { height: 4px; border-radius: 4px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
    .strength-fill { height: 100%; border-radius: 4px; transition: width .3s, background .3s; width: 0; }
    .strength-label { font-size: .7rem; margin-top: 3px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── Page Header ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-tie"></i></div>
            <div>
                <h5 class="ph-title">Profil Saya</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="#">Beranda</a></li>
                    <li><span class="bc-active">Profil</span></li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert-success-custom mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('success_password'))
        <div class="alert-success-custom mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-key"></i> {{ session('success_password') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert-danger-custom mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $err)
                    <li style="font-size:.83rem;">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Tab Nav ── --}}
    <div class="setting-tabs">
        <button type="button" class="setting-tab active" data-tab="biodata">
            <i class="fas fa-id-card"></i> Biodata
        </button>
        <button type="button" class="setting-tab" data-tab="kepegawaian">
            <i class="fas fa-briefcase"></i> Kepegawaian
        </button>
        <button type="button" class="setting-tab" data-tab="sosial">
            <i class="fas fa-share-alt"></i> Sosial & Deskripsi
        </button>
        <button type="button" class="setting-tab" data-tab="password">
            <i class="fas fa-lock"></i> Password
        </button>
    </div>

    <div class="row g-4">

        {{-- ── Sidebar Avatar ── --}}
        <div class="col-lg-3">
            <div class="card section-card">
                <div class="card-body text-center py-4">
                    <div class="avatar-wrap" id="avatar-zone"
                         onclick="document.getElementById('foto-profil-input').click()">
                        <img id="avatar-preview"
                             src="{{ $profil->foto_profil ? asset('storage/' . $profil->foto_profil) : asset('default-image/default-user.png') }}"
                             alt="Foto Profil">
                        <div class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>
                    <div class="avatar-name">{{ $profil->nama_pegawai }}</div>
                    <span class="avatar-role">{{ ucfirst($profil->role) }}</span>
                    <div class="mt-3" style="font-size:.78rem;color:#94a3b8;">
                        <i class="fas fa-id-badge me-1"></i> NIP: {{ $profil->nip }}
                    </div>
                    <div class="mt-1" style="font-size:.78rem;color:#94a3b8;">
                        <i class="fas fa-sitemap me-1"></i> {{ $profil->jabatan ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Info singkat --}}
            <div class="card section-card">
                <div class="card-body">
                    <div class="section-divider"><i class="fas fa-info-circle"></i> Info Akun</div>
                    <table style="font-size:.8rem;color:#64748b;width:100%;border-collapse:separate;border-spacing:0 6px;">
                        <tr>
                            <td style="font-weight:600;color:#475569;width:50%;">Status</td>
                            <td>
                                <span style="background:#dcfce7;color:#15803d;font-size:.72rem;padding:3px 8px;border-radius:20px;font-weight:600;">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Jenis Kelamin</td>
                            <td>{{ $profil->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">No. HP</td>
                            <td>{{ $profil->nohp_pegawai ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Golongan</td>
                            <td>{{ $profil->pangkat_golongan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Bergabung</td>
                            <td>{{ $profil->created_at?->translatedFormat('M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Konten Tab ── --}}
        <div class="col-lg-9">

            {{-- ═══ TAB: BIODATA ═══ --}}
            <div class="tab-pane active" id="tab-biodata">
                <form action="{{ route('pegawai.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    {{-- Hidden foto profil input (diklik dari sidebar) --}}
                    <input type="file" id="foto-profil-input" name="foto_profil"
                           class="d-none" accept="image/jpeg,image/jpg,image/png,image/webp">
                    {{-- Preserve kepegawaian fields --}}
                    <input type="hidden" name="nip"             value="{{ old('nip', $profil->nip) }}">
                    <input type="hidden" name="pangkat_golongan" value="{{ old('pangkat_golongan', $profil->pangkat_golongan) }}">
                    <input type="hidden" name="jabatan"         value="{{ old('jabatan', $profil->jabatan) }}">

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-user"></i> Identitas Pribadi</div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Nama Pegawai <span class="required-mark">*</span></label>
                                    <input type="text" name="nama_pegawai"
                                           class="form-control @error('nama_pegawai') is-invalid @enderror"
                                           value="{{ old('nama_pegawai', $profil->nama_pegawai) }}"
                                           placeholder="Nama lengkap sesuai KTP" required>
                                    @error('nama_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>NIK <span class="required-mark">*</span></label>
                                    <input type="text" name="nik"
                                           class="form-control @error('nik') is-invalid @enderror"
                                           value="{{ old('nik', $profil->nik) }}"
                                           placeholder="16 digit angka" maxlength="16"
                                           oninput="this.value=this.value.replace(/\D/g,'')">
                                    <div class="form-text">16 digit angka sesuai KTP</div>
                                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>Jenis Kelamin <span class="required-mark">*</span></label>
                                    <select name="jenis_kelamin"
                                            class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $profil->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $profil->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Nomor HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="nohp_pegawai"
                                               class="form-control @error('nohp_pegawai') is-invalid @enderror"
                                               value="{{ old('nohp_pegawai', $profil->nohp_pegawai) }}"
                                               placeholder="08xxxxxxxxxx" maxlength="20">
                                    </div>
                                    @error('nohp_pegawai') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email_pegawai"
                                               class="form-control @error('email_pegawai') is-invalid @enderror"
                                               value="{{ old('email_pegawai', $profil->email_pegawai) }}"
                                               placeholder="contoh@email.com">
                                    </div>
                                    <div class="form-text">Gunakan email aktif.</div>
                                    @error('email_pegawai') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label>Alamat Lengkap</label>
                                <textarea name="alamat_pegawai" rows="3"
                                          class="form-control @error('alamat_pegawai') is-invalid @enderror"
                                          placeholder="Alamat lengkap sesuai domisili…">{{ old('alamat_pegawai', $profil->alamat_pegawai) }}</textarea>
                                @error('alamat_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Simpan Biodata
                        </button>
                    </div>
                </form>
            </div>{{-- end tab biodata --}}

            {{-- ═══ TAB: KEPEGAWAIAN ═══ --}}
            <div class="tab-pane" id="tab-kepegawaian">
                <form action="{{ route('pegawai.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    {{-- Preserve biodata fields --}}
                    <input type="hidden" name="nama_pegawai"   value="{{ $profil->nama_pegawai }}">
                    <input type="hidden" name="nik"            value="{{ $profil->nik }}">
                    <input type="hidden" name="jenis_kelamin"  value="{{ $profil->jenis_kelamin }}">
                    <input type="hidden" name="nohp_pegawai"   value="{{ $profil->nohp_pegawai }}">
                    <input type="hidden" name="email_pegawai"  value="{{ $profil->email_pegawai }}">
                    <input type="hidden" name="alamat_pegawai" value="{{ $profil->alamat_pegawai }}">

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-briefcase"></i> Data Kepegawaian</div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>NIP <span class="required-mark">*</span></label>
                                    <input type="text" name="nip"
                                           class="form-control @error('nip') is-invalid @enderror"
                                           value="{{ old('nip', $profil->nip) }}"
                                           placeholder="Nomor Induk Pegawai" maxlength="20" required>
                                    <div class="form-text">NIP digunakan sebagai kredensial login.</div>
                                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Pangkat / Golongan <span class="required-mark">*</span></label>
                                    <input type="text" name="pangkat_golongan"
                                           class="form-control @error('pangkat_golongan') is-invalid @enderror"
                                           value="{{ old('pangkat_golongan', $profil->pangkat_golongan) }}"
                                           placeholder="Contoh: Penata Muda / III-a" required>
                                    @error('pangkat_golongan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>Jabatan <span class="required-mark">*</span></label>
                                    <input type="text" name="jabatan"
                                           class="form-control @error('jabatan') is-invalid @enderror"
                                           value="{{ old('jabatan', $profil->jabatan) }}"
                                           placeholder="Contoh: Staf Administrasi" required>
                                    @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Simpan Data Kepegawaian
                        </button>
                    </div>
                </form>
            </div>{{-- end tab kepegawaian --}}

            {{-- ═══ TAB: SOSIAL & DESKRIPSI ═══ --}}
            <div class="tab-pane" id="tab-sosial">
                <form action="{{ route('pegawai.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    {{-- Preserve required fields --}}
                    <input type="hidden" name="nama_pegawai"    value="{{ $profil->nama_pegawai }}">
                    <input type="hidden" name="nik"             value="{{ $profil->nik }}">
                    <input type="hidden" name="nip"             value="{{ $profil->nip }}">
                    <input type="hidden" name="pangkat_golongan" value="{{ $profil->pangkat_golongan }}">
                    <input type="hidden" name="jabatan"         value="{{ $profil->jabatan }}">
                    <input type="hidden" name="jenis_kelamin"   value="{{ $profil->jenis_kelamin }}">

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-share-alt"></i> Media Sosial</div>

                            <div class="social-row">
                                <div class="social-icon social-ig"><i class="fab fa-instagram"></i></div>
                                <div class="flex-grow-1">
                                    <label>Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" name="instagram" class="form-control"
                                               value="{{ old('instagram', $profil->instagram) }}"
                                               placeholder="username atau link instagram">
                                    </div>
                                </div>
                            </div>

                            <div class="social-row">
                                <div class="social-icon social-tw"><i class="fab fa-twitter"></i></div>
                                <div class="flex-grow-1">
                                    <label>Twitter / X</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" name="twitter" class="form-control"
                                               value="{{ old('twitter', $profil->twitter) }}"
                                               placeholder="username atau link twitter">
                                    </div>
                                </div>
                            </div>

                            <div class="social-row">
                                <div class="social-icon social-fb"><i class="fab fa-facebook-f"></i></div>
                                <div class="flex-grow-1">
                                    <label>Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook-f" style="font-size:.8rem;"></i></span>
                                        <input type="text" name="facebook" class="form-control"
                                               value="{{ old('facebook', $profil->facebook) }}"
                                               placeholder="nama.facebook atau link">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-align-left"></i> Deskripsi</div>
                            <label>Tentang Saya</label>
                            <textarea name="deskripsi" rows="5" class="form-control"
                                      placeholder="Ceritakan sedikit tentang Anda…">{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                            <div class="form-text mt-1">Deskripsi ini akan ditampilkan saat Anda membuat konten.</div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Simpan Sosial & Deskripsi
                        </button>
                    </div>
                </form>
            </div>{{-- end tab sosial --}}

            {{-- ═══ TAB: PASSWORD ═══ --}}
            <div class="tab-pane" id="tab-password">
                <form action="{{ route('pegawai.password_update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-lock"></i> Ubah Password</div>

                            <div class="mb-3">
                                <label>Password Lama <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           placeholder="Masukkan password lama">
                                </div>
                                @error('current_password')
                                    <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Password Baru <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="new_password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimal 8 karakter">
                                </div>
                                <div class="strength-bar mt-2">
                                    <div class="strength-fill" id="strength-fill"></div>
                                </div>
                                <div class="strength-label" id="strength-label" style="color:#94a3b8;"></div>
                                @error('password')
                                    <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Konfirmasi Password Baru <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                    <input type="password" name="password_confirmation" id="confirm_password"
                                           class="form-control"
                                           placeholder="Ulangi password baru">
                                </div>
                                <div class="form-text mt-1" id="match-info"></div>
                            </div>

                            <div style="background:#fef9c3;border-radius:10px;padding:10px 14px;font-size:.8rem;color:#854d0e;">
                                <i class="fas fa-shield-alt me-1"></i>
                                Gunakan minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol untuk password yang kuat.
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>{{-- end tab password --}}

        </div>{{-- end col-lg-9 --}}
    </div>{{-- end row --}}
</div>
@endsection

@section('scripts')
<script>
(function () {
    const TAB_KEY = 'profil_pegawai_tab';

    // ── Tab switching ──
    function activateTab(name) {
        document.querySelectorAll('.setting-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.tab === name);
        });
        document.querySelectorAll('.tab-pane').forEach(p => {
            p.classList.toggle('active', p.id === 'tab-' + name);
        });
        localStorage.setItem(TAB_KEY, name);
    }

    document.querySelectorAll('.setting-tab').forEach(btn => {
        btn.addEventListener('click', function () { activateTab(this.dataset.tab); });
    });

    // Pulihkan tab dari error atau localStorage
    @if($errors->any())
        @if($errors->has('current_password') || $errors->has('password'))
            activateTab('password');
        @elseif($errors->hasAny(['instagram','twitter','facebook','deskripsi']))
            activateTab('sosial');
        @elseif($errors->hasAny(['nip','pangkat_golongan','jabatan']))
            activateTab('kepegawaian');
        @else
            activateTab('biodata');
        @endif
    @elseif(session('success_password'))
        activateTab('password');
    @else
        var saved = localStorage.getItem(TAB_KEY);
        if (saved) activateTab(saved);
    @endif

    // ── Avatar preview ──
    document.getElementById('foto-profil-input')?.addEventListener('change', function () {
        var file = this.files[0];
        if (!file || !file.type.match('image.*')) return;
        var reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Auto submit foto saat dipilih
        this.closest('form') && this.closest('form').submit();
    });

    // ── Password strength ──
    var newPass     = document.getElementById('new_password');
    var confirmPass = document.getElementById('confirm_password');
    var fill        = document.getElementById('strength-fill');
    var label       = document.getElementById('strength-label');
    var matchInfo   = document.getElementById('match-info');

    if (newPass) {
        newPass.addEventListener('input', function () {
            var val = this.value;
            var score = 0;
            if (val.length >= 8)            score++;
            if (/[A-Z]/.test(val))          score++;
            if (/[0-9]/.test(val))          score++;
            if (/[^A-Za-z0-9]/.test(val))   score++;

            var colors = ['#dc2626','#f97316','#eab308','#16a34a'];
            var labels = ['Sangat Lemah','Lemah','Cukup','Kuat'];
            fill.style.width      = (score * 25) + '%';
            fill.style.background = colors[score - 1] || '#e2e8f0';
            label.textContent     = score > 0 ? labels[score - 1] : '';
            label.style.color     = colors[score - 1] || '#94a3b8';
            checkMatch();
        });
    }

    if (confirmPass) {
        confirmPass.addEventListener('input', checkMatch);
    }

    function checkMatch() {
        if (!newPass || !confirmPass || !matchInfo) return;
        if (confirmPass.value === '') { matchInfo.textContent = ''; return; }
        if (newPass.value === confirmPass.value) {
            matchInfo.textContent = '✓ Password cocok';
            matchInfo.style.color = '#16a34a';
        } else {
            matchInfo.textContent = '✗ Password tidak cocok';
            matchInfo.style.color = '#dc2626';
        }
    }
})();
</script>
@endsection
