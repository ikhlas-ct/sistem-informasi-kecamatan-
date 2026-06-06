@extends('layouts.user.user')

@section('title', 'Edit Siswa – ' . $siswa->nama_siswa)

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

    .foto-current-wrap{position:relative;width:90px;height:90px;margin:0 auto 10px;}
    .foto-current{width:100%;height:100%;border-radius:50%;object-fit:cover;border:3px solid var(--accent-light);}
    .foto-upload-wrap{border:2px dashed #e2e8f0;border-radius:14px;padding:18px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#fafbfc;}
    .foto-upload-wrap:hover{border-color:var(--accent);background:#f0fdfa;}
    .foto-preview-wrap{display:none;position:relative;width:90px;height:90px;margin:0 auto 8px;}
    .foto-preview-wrap img{width:100%;height:100%;border-radius:50%;object-fit:cover;border:3px solid var(--accent-light);}
    .foto-remove-btn{position:absolute;top:-4px;right:-4px;width:22px;height:22px;border-radius:50%;background:#ef4444;border:none;color:#fff;font-size:.65rem;display:flex;align-items:center;justify-content:center;cursor:pointer;}

    .info-box{background:#f0fdfa;border:1.5px solid var(--accent-light);border-radius:10px;padding:10px 14px;font-size:.8rem;color:#0f766e;display:flex;align-items:flex-start;gap:8px;}
    .warn-box{background:#fef9c3;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;font-size:.8rem;color:#92400e;display:flex;align-items:flex-start;gap:8px;}

    .btn-simpan{background:linear-gradient(135deg,var(--accent),#0f766e);border:none;border-radius:10px;font-weight:700;font-size:.85rem;padding:10px 28px;color:#fff;}
    .btn-simpan:hover{filter:brightness(1.07);color:#fff;}
    .btn-batal{border-radius:10px;font-size:.85rem;border:1.5px solid #e2e8f0;color:#64748b;padding:9px 20px;}
    .btn-batal:hover{background:#f8fafc;}
    .btn-simpan .spinner-border{width:.85rem;height:.85rem;border-width:2px;}
</style>
@endsection

@section('content')
@php
    $masyarakat = $siswa->user?->masyarakat;
@endphp
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <h5 class="ph-title">Edit Siswa</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('siswa.index') }}">Data Siswa</a></li>
                    <li><a href="{{ route('siswa.show', $siswa->id_siswa) }}">{{ Str::limit($siswa->nama_siswa, 28) }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('siswa.show', $siswa->id_siswa) }}" class="btn btn-batal btn-sm">
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

    <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST"
          enctype="multipart/form-data" id="form-edit">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Data Masyarakat --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-id-card"></i> Data Masyarakat</div>
                        <div class="info-box mb-3">
                            <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                            <span>Data ini tersimpan di profil masyarakat yang terhubung ke akun siswa.</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                                <input type="text" name="nama_masyarakat"
                                       class="form-control @error('nama_masyarakat') is-invalid @enderror"
                                       value="{{ old('nama_masyarakat', $masyarakat?->nama_masyarakat) }}"
                                       placeholder="Nama lengkap">
                                @error('nama_masyarakat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIK (Username Login)</label>
                                <div class="locked-field">
                                    <i class="fas fa-lock"></i>
                                    {{ $siswa->user?->nip_nik ?? '-' }}
                                </div>
                                <small class="text-muted" style="font-size:.72rem;">NIK tidak dapat diubah</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">— Pilih —</option>
                                    <option value="laki-laki"  {{ old('jenis_kelamin', $masyarakat?->jenis_kelamin) === 'laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan"  {{ old('jenis_kelamin', $masyarakat?->jenis_kelamin) === 'perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp"
                                       class="form-control @error('no_hp') is-invalid @enderror"
                                       value="{{ old('no_hp', $masyarakat?->no_hp) }}"
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
                                            {{ old('id_nagari_masy', $masyarakat?->id_nagari) == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama_nagari }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_nagari_masy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Siswa --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-graduation-cap"></i> Data Siswa</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis"
                                       class="form-control @error('nis') is-invalid @enderror"
                                       value="{{ old('nis', $siswa->nis) }}"
                                       placeholder="Nomor Induk Siswa">
                                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas"
                                       class="form-control @error('kelas') is-invalid @enderror"
                                       value="{{ old('kelas', $siswa->kelas) }}"
                                       placeholder="Contoh: XI IPA 2">
                                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Penempatan Sekolah --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-school"></i> Penempatan Sekolah</div>

                        @if($isAdminSekolah)
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
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nagari</label>
                                    <select id="select-nagari" class="form-select">
                                        <option value="">— Pilih Nagari —</option>
                                        @foreach($nagariList as $n)
                                            <option value="{{ $n->id }}"
                                                {{ $siswa->sekolah?->id_nagari == $n->id ? 'selected' : '' }}>
                                                {{ $n->nama_nagari }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            <div class="warn-box mt-3">
                                <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                                <span>Sekolah saat ini: <strong>{{ $siswa->sekolah?->nama_sekolah ?? '-' }}</strong>
                                    ({{ $siswa->sekolah?->nagari?->nama_nagari ?? '-' }}).
                                    Pilih nagari untuk memuat ulang daftar sekolah.</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Ganti Password --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-lock"></i> Ganti Password
                            <span style="font-size:.7rem;color:#94a3b8;font-weight:400;">(opsional)</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <div class="input-pw">
                                    <input type="password" name="password" id="pw1"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Kosongkan jika tidak ganti">
                                    <button type="button" class="pw-toggle" onclick="togglePw('pw1',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-pw">
                                    <input type="password" name="password_confirmation" id="pw2"
                                           class="form-control" placeholder="Ulangi password baru">
                                    <button type="button" class="pw-toggle" onclick="togglePw('pw2',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Foto Profil (dari masyarakat) --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-camera"></i> Foto Profil</div>
                        <div class="info-box mb-3" style="font-size:.75rem;">
                            <i class="fas fa-info-circle flex-shrink-0"></i>
                            <span>Foto disimpan ke data masyarakat yang terhubung.</span>
                        </div>

                        @php $fotoSaatIni = $masyarakat?->foto_profil; @endphp
                        @if($fotoSaatIni)
                        <div class="text-center mb-3">
                            <div class="foto-current-wrap">
                                <img src="{{ Storage::url($fotoSaatIni) }}" class="foto-current"
                                     alt="{{ $siswa->nama_siswa }}">
                            </div>
                            <div style="font-size:.74rem;color:#94a3b8;" class="mb-2">Foto saat ini</div>
                            <div class="form-check d-inline-flex align-items-center gap-2">
                                <input type="checkbox" name="hapus_foto" value="1"
                                       id="chk-hapus" class="form-check-input" style="cursor:pointer;">
                                <label for="chk-hapus" class="form-check-label"
                                       style="font-size:.78rem;color:#ef4444;cursor:pointer;font-weight:600;">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus foto ini
                                </label>
                            </div>
                        </div>
                        @endif

                        <input type="file" name="foto_profil" id="foto-input"
                               accept="image/jpg,image/jpeg,image/png,image/webp" class="d-none">
                        <div class="foto-upload-wrap" onclick="document.getElementById('foto-input').click()">
                            <div class="foto-preview-wrap" id="preview-wrap">
                                <img id="foto-preview" src="" alt="Preview">
                                <button type="button" class="foto-remove-btn" id="btn-remove-foto">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="foto-placeholder">
                                <div style="font-size:1.5rem;color:#cbd5e1;margin-bottom:4px;"><i class="fas fa-camera"></i></div>
                                <div style="font-size:.78rem;color:#64748b;font-weight:600;">
                                    {{ $fotoSaatIni ? 'Ganti foto' : 'Upload foto' }}
                                </div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">JPG, PNG, WEBP · Maks 2 MB</div>
                            </div>
                        </div>
                        @error('foto_profil')
                        <div class="text-danger mt-1" style="font-size:.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Info Akun --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user-circle"></i> Info Akun</div>
                        @php $u = $siswa->user; @endphp
                        <table style="width:100%;font-size:.82rem;border-collapse:collapse;">
                            <tr><td style="color:#94a3b8;padding:5px 0;width:40%;font-weight:600;">NIK</td>
                                <td style="color:#1e293b;font-weight:700;">{{ $u?->nip_nik ?? '-' }}</td></tr>
                            <tr><td style="color:#94a3b8;padding:5px 0;font-weight:600;">Sub-role</td>
                                <td>
                                    @if($u?->role === 'masyarakat' && $u?->sekolah === 'siswa')
                                        <span style="background:var(--accent-light);color:var(--accent);font-size:.72rem;padding:2px 8px;border-radius:6px;font-weight:700;">
                                            Masyarakat – Siswa
                                        </span>
                                    @else
                                        <span style="font-size:.8rem;color:#64748b;">{{ ucfirst($u?->role ?? '-') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><td style="color:#94a3b8;padding:5px 0;font-weight:600;">Status</td>
                                <td>
                                    <span class="badge bg-{{ $u?->status === 'aktif' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $u?->status === 'aktif' ? 'success' : 'secondary' }}" style="font-size:.72rem;">
                                        {{ ucfirst($u?->status ?? '-') }}
                                    </span>
                                </td>
                            </tr>
                            <tr><td style="color:#94a3b8;padding:5px 0;font-weight:600;">KK</td>
                                <td style="color:#334155;">{{ $masyarakat?->kk ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-clock"></i> Informasi Data</div>
                        <div style="font-size:.8rem;color:#64748b;line-height:2.2;">
                            <div><i class="fas fa-calendar-plus me-2 text-muted"></i>
                                Dibuat: <strong>{{ $siswa->created_at?->translatedFormat('d M Y, H:i') }}</strong>
                            </div>
                            <div><i class="fas fa-calendar-check me-2 text-muted"></i>
                                Diperbarui: <strong>{{ $siswa->updated_at?->translatedFormat('d M Y, H:i') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}

        </div>{{-- end row --}}

        <div class="d-flex justify-content-end gap-2 mt-1 mb-4">
            <a href="{{ route('siswa.show', $siswa->id_siswa) }}" class="btn btn-batal">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-simpan" id="btn-submit">
                <span class="btn-text"><i class="fas fa-save me-1"></i> Simpan Perubahan</span>
                <span class="spinner-border d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function togglePw(id, btn) {
    const el = document.getElementById(id), ic = btn.querySelector('i');
    if (el.type === 'password') { el.type = 'text'; ic.classList.replace('fa-eye','fa-eye-slash'); }
    else { el.type = 'password'; ic.classList.replace('fa-eye-slash','fa-eye'); }
}

// Foto preview
const fotoInput = document.getElementById('foto-input');
fotoInput.addEventListener('change', function () {
    const file = this.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('foto-preview').src = e.target.result;
        document.getElementById('preview-wrap').style.display = 'block';
        document.getElementById('foto-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});
document.getElementById('btn-remove-foto')?.addEventListener('click', function (e) {
    e.stopPropagation();
    fotoInput.value = '';
    document.getElementById('foto-preview').src = '';
    document.getElementById('preview-wrap').style.display = 'none';
    document.getElementById('foto-placeholder').style.display = 'block';
});

// Sekolah AJAX
const oldSekolah = "{{ old('id_sekolah', $siswa->id_sekolah) }}";

@if($isSuperAdmin)
const selectNagari  = document.getElementById('select-nagari');
const selectSekolah = document.getElementById('select-sekolah');

function loadSekolah(idNagari) {
    selectSekolah.innerHTML = '<option value="">Memuat...</option>';
    selectSekolah.disabled  = true;
    if (!idNagari) {
        selectSekolah.innerHTML = '<option value="">— Pilih Nagari dulu —</option>';
        selectSekolah.disabled  = false; return;
    }
    fetch(`{{ route('siswa.ajax.sekolah-by-nagari') }}?id_nagari=${idNagari}`)
        .then(r => r.json())
        .then(data => {
            selectSekolah.disabled = false;
            selectSekolah.innerHTML = data.length ? '<option value="">— Pilih Sekolah —</option>' : '<option value="">Tidak ada sekolah aktif</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id_sekolah;
                opt.textContent = `${s.nama_sekolah} (${s.jenjang})`;
                if (String(s.id_sekolah) === String(oldSekolah)) opt.selected = true;
                selectSekolah.appendChild(opt);
            });
        }).catch(() => { selectSekolah.innerHTML = '<option value="">Gagal memuat</option>'; selectSekolah.disabled = false; });
}
if (selectNagari.value) loadSekolah(selectNagari.value);
selectNagari.addEventListener('change', function () { loadSekolah(this.value); });
@endif

@if($isNagari)
(function () {
    const idNagari = document.getElementById('selected-nagari').value;
    const sel = document.getElementById('select-sekolah');
    fetch(`{{ route('siswa.ajax.sekolah-by-nagari') }}?id_nagari=${idNagari}`)
        .then(r => r.json())
        .then(data => {
            sel.innerHTML = '<option value="">— Pilih Sekolah —</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id_sekolah;
                opt.textContent = `${s.nama_sekolah} (${s.jenjang})`;
                if (String(s.id_sekolah) === String(oldSekolah)) opt.selected = true;
                sel.appendChild(opt);
            });
        });
})();
@endif

document.getElementById('form-edit').addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.spinner-border').classList.remove('d-none');
    btn.disabled = true;
});
</script>
@endsection
