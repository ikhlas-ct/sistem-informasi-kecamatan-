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

    /* ── Doc upload zone ── */
    .img-upload-wrap {
        position: relative; border: 2px dashed #e2e8f0;
        border-radius: 12px; overflow: hidden; cursor: pointer;
        background: #fafbfc; transition: border-color .2s, background .2s;
        min-height: 100px;
    }
    .img-upload-wrap:hover { border-color: #6366f1; background: #f5f3ff; }
    .img-upload-wrap img.preview { width: 100%; height: 120px; object-fit: cover; display: block; }
    .img-upload-wrap .upload-overlay {
        position: absolute; inset: 0; display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 4px; padding: 10px;
    }
    .img-upload-wrap.has-img .upload-overlay {
        background: rgba(0,0,0,.45); opacity: 0; transition: opacity .2s;
    }
    .img-upload-wrap.has-img:hover .upload-overlay { opacity: 1; }
    .img-upload-wrap:not(.has-img) .upload-overlay { background: transparent; }
    .upload-overlay i { font-size: 1.6rem; color: #94a3b8; }
    .upload-overlay span { font-size: .72rem; color: #94a3b8; text-align: center; }
    .img-upload-wrap.has-img .upload-overlay i,
    .img-upload-wrap.has-img .upload-overlay span { color: #fff; }
    .doc-badge {
        position: absolute; top: 6px; right: 6px;
        background: #dcfce7; color: #15803d;
        font-size: .65rem; font-weight: 700; border-radius: 6px; padding: 2px 7px;
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
            <div class="ph-icon"><i class="fas fa-user-circle"></i></div>
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
        <div class="alert-success-custom alert-dismissible fade show mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('success_password'))
        <div class="alert-success-custom alert-dismissible fade show mb-3 d-flex align-items-center gap-2">
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
        <button type="button" class="setting-tab" data-tab="sosial">
            <i class="fas fa-share-alt"></i> Sosial & Deskripsi
        </button>
        <button type="button" class="setting-tab" data-tab="dokumen">
            <i class="fas fa-folder-open"></i> Dokumen
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
                    {{-- Avatar --}}
                    <div class="avatar-wrap" id="avatar-zone"
                         onclick="document.getElementById('foto-profil-input').click()">
                        <img id="avatar-preview"
                             src="{{ $profil->foto_profil ? asset('storage/' . $profil->foto_profil) : asset('defaultimage/no_image_available.jpg') }}"
                             alt="Foto Profil">
                        <div class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>
                    <div class="avatar-name">{{ $profil->nama_masyarakat }}</div>
                    <span class="avatar-role">Masyarakat</span>
                    <div class="mt-3" style="font-size:.78rem;color:#94a3b8;">
                        <i class="fas fa-id-card me-1"></i> NIK: {{ $profil->nik }}
                    </div>
                    @if($profil->nagari)
                        <div class="mt-1" style="font-size:.78rem;color:#94a3b8;">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $profil->nagari->nama_nagari }}
                        </div>
                    @endif
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
                            <td>{{ ucfirst($profil->jenis_kelamin ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">No. HP</td>
                            <td>{{ $profil->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:600;color:#475569;">Pekerjaan</td>
                            <td>{{ $profil->pekerjaan ?? '-' }}</td>
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
                <form action="{{ route('masyarakat.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    {{-- Hidden foto profil input --}}
                    <input type="file" id="foto-profil-input" name="foto_profil"
                           class="d-none" accept="image/jpeg,image/jpg,image/png,image/webp">

                    {{-- Identitas Utama --}}
                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-user"></i> Identitas Utama</div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="nama_masyarakat" class="form-control @error('nama_masyarakat') is-invalid @enderror"
                                           value="{{ old('nama_masyarakat', $profil->nama_masyarakat) }}"
                                           placeholder="Nama sesuai KTP" required>
                                    @error('nama_masyarakat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Nama Ibu Kandung</label>
                                    <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror"
                                           value="{{ old('nama_ibu', $profil->nama_ibu) }}"
                                           placeholder="Nama ibu kandung">
                                    @error('nama_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>NIK <span class="required-mark">*</span></label>
                                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                                           value="{{ old('nik', $profil->nik) }}"
                                           placeholder="16 digit angka" maxlength="16"
                                           oninput="this.value=this.value.replace(/\D/,'')">
                                    <div class="form-text">16 digit angka sesuai KTP</div>
                                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Nomor KK <span class="required-mark">*</span></label>
                                    <input type="text" name="kk" class="form-control @error('kk') is-invalid @enderror"
                                           value="{{ old('kk', $profil->kk) }}"
                                           placeholder="16 digit angka" maxlength="16"
                                           oninput="this.value=this.value.replace(/\D/,'')">
                                    <div class="form-text">16 digit angka sesuai Kartu Keluarga</div>
                                    @error('kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>Nomor HP <span class="required-mark">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                               value="{{ old('no_hp', $profil->no_hp) }}"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                    @error('no_hp') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Jenis Kelamin <span class="required-mark">*</span></label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="laki-laki"  {{ old('jenis_kelamin', $profil->jenis_kelamin) == 'laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan"  {{ old('jenis_kelamin', $profil->jenis_kelamin) == 'perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror"
                                           value="{{ old('pekerjaan', $profil->pekerjaan) }}"
                                           placeholder="Contoh: Petani, PNS, Wiraswasta…">
                                    @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Nagari</label>
                                    <select name="id_nagari" class="form-select @error('id_nagari') is-invalid @enderror">
                                        <option value="">-- Pilih Nagari --</option>
                                        @foreach($nagari as $n)
                                            <option value="{{ $n->id }}"
                                                {{ old('id_nagari', $profil->id_nagari) == $n->id ? 'selected' : '' }}>
                                                {{ $n->nama_nagari }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_nagari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label>Alamat Lengkap <span class="required-mark">*</span></label>
                                <textarea name="alamat" rows="3"
                                          class="form-control @error('alamat') is-invalid @enderror"
                                          placeholder="Alamat lengkap sesuai domisili…">{{ old('alamat', $profil->alamat) }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

            {{-- ═══ TAB: SOSIAL & DESKRIPSI ═══ --}}
            <div class="tab-pane" id="tab-sosial">
                <form action="{{ route('masyarakat.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    {{-- Kirim field wajib tersembunyi agar tidak error validasi --}}
                    <input type="hidden" name="nama_masyarakat" value="{{ $profil->nama_masyarakat }}">
                    <input type="hidden" name="nik" value="{{ $profil->nik }}">
                    <input type="hidden" name="kk" value="{{ $profil->kk }}">
                    <input type="hidden" name="no_hp" value="{{ $profil->no_hp }}">
                    <input type="hidden" name="jenis_kelamin" value="{{ $profil->jenis_kelamin }}">
                    <input type="hidden" name="alamat" value="{{ $profil->alamat }}">

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
                                               placeholder="username_instagram">
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
                                               placeholder="username_twitter">
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
                                               placeholder="nama.facebook">
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
                            <div class="form-text mt-1">Deskripsi singkat yang akan tampil di profil publik Anda.</div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Simpan Sosial & Deskripsi
                        </button>
                    </div>
                </form>
            </div>{{-- end tab sosial --}}

            {{-- ═══ TAB: DOKUMEN ═══ --}}
            <div class="tab-pane" id="tab-dokumen">
                <form action="{{ route('masyarakat.profil_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="nama_masyarakat" value="{{ $profil->nama_masyarakat }}">
                    <input type="hidden" name="nik" value="{{ $profil->nik }}">
                    <input type="hidden" name="kk" value="{{ $profil->kk }}">
                    <input type="hidden" name="no_hp" value="{{ $profil->no_hp }}">
                    <input type="hidden" name="jenis_kelamin" value="{{ $profil->jenis_kelamin }}">
                    <input type="hidden" name="alamat" value="{{ $profil->alamat }}">

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-id-card"></i> Dokumen KTP</div>
                            <div class="row g-3">
                                {{-- Scan KTP --}}
                                <div class="col-md-6">
                                    <label class="mb-2">Scan KTP</label>
                                    <div class="img-upload-wrap {{ $profil->scan_ktp ? 'has-img' : '' }}"
                                         id="zone-scan_ktp"
                                         onclick="document.getElementById('input-scan_ktp').click()">
                                        @if($profil->scan_ktp)
                                            <img class="preview" id="preview-scan_ktp"
                                                 src="{{ asset('storage/' . $profil->scan_ktp) }}" alt="Scan KTP">
                                            <span class="doc-badge"><i class="fas fa-check me-1"></i>Ada</span>
                                        @else
                                            <img class="preview" id="preview-scan_ktp" src="" style="display:none;" alt="">
                                        @endif
                                        <div class="upload-overlay">
                                            <i class="fas fa-{{ $profil->scan_ktp ? 'sync-alt' : 'cloud-upload-alt' }}"></i>
                                            <span>{{ $profil->scan_ktp ? 'Klik untuk ganti' : 'Klik untuk upload Scan KTP' }}</span>
                                        </div>
                                    </div>
                                    <input type="file" name="scan_ktp" id="input-scan_ktp"
                                           class="d-none" accept="image/jpeg,image/jpg,image/png">
                                    <div class="form-text mt-1">Format: JPEG/PNG · Maks. 2MB</div>
                                    @error('scan_ktp') <div class="text-danger" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>

                                {{-- Foto Diri + KTP --}}
                                <div class="col-md-6">
                                    <label class="mb-2">Foto Diri dengan KTP</label>
                                    <div class="img-upload-wrap {{ $profil->foto_diri_ktp ? 'has-img' : '' }}"
                                         id="zone-foto_diri_ktp"
                                         onclick="document.getElementById('input-foto_diri_ktp').click()">
                                        @if($profil->foto_diri_ktp)
                                            <img class="preview" id="preview-foto_diri_ktp"
                                                 src="{{ asset('storage/' . $profil->foto_diri_ktp) }}" alt="Foto Diri KTP">
                                            <span class="doc-badge"><i class="fas fa-check me-1"></i>Ada</span>
                                        @else
                                            <img class="preview" id="preview-foto_diri_ktp" src="" style="display:none;" alt="">
                                        @endif
                                        <div class="upload-overlay">
                                            <i class="fas fa-{{ $profil->foto_diri_ktp ? 'sync-alt' : 'cloud-upload-alt' }}"></i>
                                            <span>{{ $profil->foto_diri_ktp ? 'Klik untuk ganti' : 'Klik untuk upload Foto Diri + KTP' }}</span>
                                        </div>
                                    </div>
                                    <input type="file" name="foto_diri_ktp" id="input-foto_diri_ktp"
                                           class="d-none" accept="image/jpeg,image/jpg,image/png">
                                    <div class="form-text mt-1">Format: JPEG/PNG · Maks. 2MB</div>
                                    @error('foto_diri_ktp') <div class="text-danger" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-users"></i> Dokumen KK</div>
                            <div class="row g-3">
                                {{-- Scan KK --}}
                                <div class="col-md-6">
                                    <label class="mb-2">Scan Kartu Keluarga</label>
                                    <div class="img-upload-wrap {{ $profil->scan_kk ? 'has-img' : '' }}"
                                         id="zone-scan_kk"
                                         onclick="document.getElementById('input-scan_kk').click()">
                                        @if($profil->scan_kk)
                                            <img class="preview" id="preview-scan_kk"
                                                 src="{{ asset('storage/' . $profil->scan_kk) }}" alt="Scan KK">
                                            <span class="doc-badge"><i class="fas fa-check me-1"></i>Ada</span>
                                        @else
                                            <img class="preview" id="preview-scan_kk" src="" style="display:none;" alt="">
                                        @endif
                                        <div class="upload-overlay">
                                            <i class="fas fa-{{ $profil->scan_kk ? 'sync-alt' : 'cloud-upload-alt' }}"></i>
                                            <span>{{ $profil->scan_kk ? 'Klik untuk ganti' : 'Klik untuk upload Scan KK' }}</span>
                                        </div>
                                    </div>
                                    <input type="file" name="scan_kk" id="input-scan_kk"
                                           class="d-none" accept="image/jpeg,image/jpg,image/png">
                                    <div class="form-text mt-1">Format: JPEG/PNG · Maks. 2MB</div>
                                    @error('scan_kk') <div class="text-danger" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card section-card">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-file-alt"></i> Dokumen Akta</div>
                            <div class="row g-3">
                                {{-- Akta --}}
                                <div class="col-md-6">
                                    <label class="mb-2">Akta Kelahiran</label>
                                    <div class="img-upload-wrap {{ $profil->akta_kelahiran ? 'has-img' : '' }}"
                                         id="zone-akta_kelahiran"
                                         onclick="document.getElementById('input-akta_kelahiran').click()">
                                        @if($profil->akta_kelahiran)
                                            <img class="preview" id="preview-akta_kelahiran"
                                                 src="{{ asset('storage/' . $profil->akta_kelahiran) }}" alt="Akta">
                                            <span class="doc-badge"><i class="fas fa-check me-1"></i>Ada</span>
                                        @else
                                            <img class="preview" id="preview-akta_kelahiran" src="" style="display:none;" alt="">
                                        @endif
                                        <div class="upload-overlay">
                                            <i class="fas fa-{{ $profil->akta_kelahiran ? 'sync-alt' : 'cloud-upload-alt' }}"></i>
                                            <span>{{ $profil->akta_kelahiran ? 'Klik untuk ganti' : 'Klik untuk upload Akta Kelahiran' }}</span>
                                        </div>
                                    </div>
                                    <input type="file" name="akta_kelahiran" id="input-akta_kelahiran"
                                           class="d-none" accept="image/jpeg,image/jpg,image/png">
                                    <div class="form-text mt-1">Format: JPEG/PNG · Maks. 2MB</div>
                                    @error('akta_kelahiran') <div class="text-danger" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>

                                {{-- Foto Diri + Akta --}}
                                <div class="col-md-6">
                                    <label class="mb-2">Foto Diri dengan Akta</label>
                                    <div class="img-upload-wrap {{ $profil->foto_diri_akta ? 'has-img' : '' }}"
                                         id="zone-foto_diri_akta"
                                         onclick="document.getElementById('input-foto_diri_akta').click()">
                                        @if($profil->foto_diri_akta)
                                            <img class="preview" id="preview-foto_diri_akta"
                                                 src="{{ asset('storage/' . $profil->foto_diri_akta) }}" alt="Foto Diri Akta">
                                            <span class="doc-badge"><i class="fas fa-check me-1"></i>Ada</span>
                                        @else
                                            <img class="preview" id="preview-foto_diri_akta" src="" style="display:none;" alt="">
                                        @endif
                                        <div class="upload-overlay">
                                            <i class="fas fa-{{ $profil->foto_diri_akta ? 'sync-alt' : 'cloud-upload-alt' }}"></i>
                                            <span>{{ $profil->foto_diri_akta ? 'Klik untuk ganti' : 'Klik untuk upload Foto Diri + Akta' }}</span>
                                        </div>
                                    </div>
                                    <input type="file" name="foto_diri_akta" id="input-foto_diri_akta"
                                           class="d-none" accept="image/jpeg,image/jpg,image/png">
                                    <div class="form-text mt-1">Format: JPEG/PNG · Maks. 2MB</div>
                                    @error('foto_diri_akta') <div class="text-danger" style="font-size:.78rem;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Simpan Dokumen
                        </button>
                    </div>
                </form>
            </div>{{-- end tab dokumen --}}

            {{-- ═══ TAB: PASSWORD ═══ --}}
            <div class="tab-pane" id="tab-password">
                <form action="{{ route('masyarakat.password_update') }}" method="POST">
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
    const TAB_KEY = 'profil_masyarakat_tab';

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
        @elseif($errors->hasAny(['scan_ktp','foto_diri_ktp','scan_kk','akta_kelahiran','foto_diri_akta']))
            activateTab('dokumen');
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
    });

    // ── Dokumen preview ──
    var docFields = ['scan_ktp','foto_diri_ktp','scan_kk','akta_kelahiran','foto_diri_akta'];
    docFields.forEach(function (field) {
        var input   = document.getElementById('input-' + field);
        var preview = document.getElementById('preview-' + field);
        var zone    = document.getElementById('zone-' + field);
        if (!input) return;
        input.addEventListener('change', function () {
            var file = this.files[0];
            if (!file || !file.type.match('image.*')) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (zone) zone.classList.add('has-img');
            };
            reader.readAsDataURL(file);
        });
    });

    // ── Password strength ──
    var newPass    = document.getElementById('new_password');
    var confirmPass = document.getElementById('confirm_password');
    var fill       = document.getElementById('strength-fill');
    var label      = document.getElementById('strength-label');
    var matchInfo  = document.getElementById('match-info');

    if (newPass) {
        newPass.addEventListener('input', function () {
            var val = this.value;
            var score = 0;
            if (val.length >= 8)                   score++;
            if (/[A-Z]/.test(val))                 score++;
            if (/[0-9]/.test(val))                 score++;
            if (/[^A-Za-z0-9]/.test(val))          score++;

            var colors = ['#dc2626','#f97316','#eab308','#16a34a'];
            var labels = ['Sangat Lemah','Lemah','Cukup','Kuat'];
            fill.style.width    = (score * 25) + '%';
            fill.style.background = colors[score - 1] || '#e2e8f0';
            label.textContent   = score > 0 ? labels[score - 1] : '';
            label.style.color   = colors[score - 1] || '#94a3b8';
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
