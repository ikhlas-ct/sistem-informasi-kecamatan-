@extends('layouts.user.user')

@section('title', 'Edit Sekolah – ' . $sekolah->nama_sekolah)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent:#0f766e; --accent-light:#ccfbf1; --accent-shadow:rgba(15,118,110,.18); }

    .container { padding-left:28px; padding-right:24px; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:var(--accent); text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:20px 22px; }
    .section-divider { border-left:4px solid var(--accent); background:#f8f9fa; padding:7px 13px; border-radius:0 6px 6px 0; font-weight:700; font-size:.82rem; color:var(--accent); display:flex; align-items:center; gap:8px; margin-bottom:1.1rem; }

    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control:disabled, .form-select:disabled { background:#f1f5f9; color:#64748b; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Logo ── */
    .logo-wrap { width:100%; height:180px; border:2px dashed #ced4da; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s,background .2s; background:#fafbfc; position:relative; }
    .logo-wrap:hover, .logo-wrap.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #logo-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:contain; padding:12px; background:#fff; }
    .logo-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .logo-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .logo-placeholder span { font-size:.78rem; }

    /* ── Status pills ── */
    .status-radio { display:none; }
    .status-label { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:20px; font-size:.8rem; font-weight:600; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; }
    .status-radio:checked + .status-label { border-color:var(--accent); background:var(--accent-light); color:var(--accent); }

    .nagari-locked { background:var(--accent-light); border:1.5px solid var(--accent); border-radius:10px; padding:9px 14px; font-size:.84rem; color:#0f766e; font-weight:600; display:flex; align-items:center; gap:8px; }

    .btn-submit { background:linear-gradient(135deg,var(--accent),#134e4a); border:none; border-radius:10px; font-weight:600; font-size:.88rem; padding:10px 28px; color:#fff; transition:all .2s; }
    .btn-submit:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-cancel { border-radius:10px; font-size:.85rem; border:1.5px solid #e2e8f0; color:#64748b; padding:9px 20px; }
    .btn-cancel:hover { background:#f8fafc; }

    .hapus-logo-wrap { margin-top:10px; }
    .hapus-logo-wrap label { font-size:.78rem; color:#dc2626; cursor:pointer; display:flex; align-items:center; gap:6px; }

    .spinner-border-sm { width:1rem; height:1rem; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-edit"></i></div>
            <div>
                <h5 class="ph-title">Edit Sekolah</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('sekolah.index') }}">Data Sekolah</a></li>
                    <li><a href="{{ route('sekolah.show', $sekolah->id_sekolah) }}">{{ Str::limit($sekolah->nama_sekolah, 30) }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('sekolah.show', $sekolah->id_sekolah) }}" class="btn btn-cancel btn-sm">
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

    <form action="{{ route('sekolah.update', $sekolah->id_sekolah) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Informasi Sekolah --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-school"></i> Informasi Sekolah</div>

                        <div class="mb-3">
                            <label for="nama_sekolah">Nama Sekolah <span class="required-mark">*</span></label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah"
                                   class="form-control @error('nama_sekolah') is-invalid @enderror"
                                   value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}">
                            @error('nama_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="npsn">NPSN</label>
                                <input type="text" id="npsn" name="npsn"
                                       class="form-control @error('npsn') is-invalid @enderror"
                                       value="{{ old('npsn', $sekolah->npsn) }}" maxlength="20">
                                @error('npsn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenjang">Jenjang <span class="required-mark">*</span></label>
                                <select id="jenjang" name="jenjang"
                                        class="form-select @error('jenjang') is-invalid @enderror">
                                    <option value="">-- Pilih Jenjang --</option>
                                    @foreach(['TK','PAUD','SD','MI','SMP','MTs','SMA','MA','SMK'] as $j)
                                        <option value="{{ $j }}" {{ old('jenjang', $sekolah->jenjang) === $j ? 'selected' : '' }}>{{ $j }}</option>
                                    @endforeach
                                </select>
                                @error('jenjang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                      class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $sekolah->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="no_hp">No. HP / Telepon</label>
                                <input type="text" id="no_hp" name="no_hp"
                                       class="form-control @error('no_hp') is-invalid @enderror"
                                       value="{{ old('no_hp', $sekolah->no_hp) }}" maxlength="20">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $sekolah->email) }}">
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

                        {{-- Nagari --}}
                        <div class="mb-3">
                            <label>Nagari <span class="required-mark">*</span></label>

                            @if($bisaPilihNagari)
                                {{--
                                    Camat / Staf Camat: bebas pilih nagari.
                                    PERBAIKAN: onchange dihapus — nagari dan administrator
                                    kini merupakan pilihan independen.
                                --}}
                                <select id="id_nagari" name="id_nagari"
                                        class="form-select @error('id_nagari') is-invalid @enderror">
                                    <option value="">-- Pilih Nagari --</option>
                                    @foreach($nagaris as $n)
                                        <option value="{{ $n->id }}"
                                            {{ old('id_nagari', $sekolah->id_nagari) == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama_nagari }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_nagari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih nagari tempat sekolah berada.</div>

                            @else
                                {{-- Kepala / Staf Nagari: nagari terkunci --}}
                                <div class="nagari-locked">
                                    <i class="fas fa-lock"></i>
                                    {{ $nagariTerpilih?->nama_nagari ?? $sekolah->nagari?->nama_nagari ?? '-' }}
                                    <span style="font-size:.75rem;font-weight:400;color:#64748b;margin-left:4px;">(otomatis)</span>
                                </div>
                                <input type="hidden" name="id_nagari" value="{{ $nagariTerpilih?->id ?? $sekolah->id_nagari }}">
                            @endif
                        </div>

                        {{-- User / Administrator --}}
                        {{--
                            PERBAIKAN: Daftar masyarakat kini dimuat langsung dari server untuk semua role.
                            - Superadmin  : $userMasyarakat berisi semua masyarakat (tanpa filter nagari)
                            - Pegawai nagari: $userMasyarakat berisi masyarakat di nagarinya saja
                            - Admin sekolah : $userMasyarakat berisi masyarakat dari nagarinya sendiri
                            AJAX tidak lagi diperlukan.
                        --}}
                        <div class="mb-3">
                            <label for="id_user">Kepala Sekolah / Administrator <span class="required-mark">*</span></label>
                            <select id="id_user" name="id_user"
                                    class="form-select @error('id_user') is-invalid @enderror">
                                <option value="">-- Pilih Administrator --</option>
                                @forelse($userMasyarakat as $u)
                                    <option value="{{ $u->id }}"
                                        {{ old('id_user', $sekolah->id_user) == $u->id ? 'selected' : '' }}>
                                        {{ $u->nip_nik }}
                                        @if($u->masyarakat?->nama_masyarakat)
                                            – {{ $u->masyarakat->nama_masyarakat }}
                                        @endif
                                        @if($u->id == $sekolah->id_user)
                                            (Admin Saat Ini)
                                        @endif
                                        @if($bisaPilihNagari && $u->masyarakat?->nagari?->nama_nagari && $u->id != $sekolah->id_user)
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
                            <div class="form-text">Admin yang ditandai "(Admin Saat Ini)" adalah administrator aktif sekolah ini.</div>
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

                        <label for="logo-input" id="logo-drop-zone" class="logo-wrap">
                            @if($sekolah->logo)
                                <img id="logo-preview" src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo">
                            @else
                                <img id="logo-preview" src="" alt="Preview" style="display:none;">
                            @endif
                            <div class="logo-placeholder" id="logo-placeholder" style="{{ $sekolah->logo ? 'display:none;' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret logo baru<br><small>JPG, PNG, WEBP – maks 2 MB</small></span>
                            </div>
                        </label>
                        <input type="file" id="logo-input" name="logo"
                               class="d-none @error('logo') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @error('logo')
                            <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>
                        @enderror

                        @if($sekolah->logo)
                        <div class="hapus-logo-wrap">
                            <input type="checkbox" id="hapus_logo" name="hapus_logo" value="1">
                            <label for="hapus_logo">
                                <i class="fas fa-trash-alt"></i>
                                Hapus logo yang ada
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Status: hanya camat, pegawai kecamatan & pegawai nagari --}}
                @if($roleLabel !== 'admin_sekolah')
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-cog"></i> Status</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['aktif' => ['Aktif','#16a34a','check-circle'], 'nonaktif' => ['Nonaktif','#dc2626','ban']] as $val => [$lbl, $col, $ico])
                            <div>
                                <input type="radio" id="status_{{ $val }}" name="status"
                                       value="{{ $val }}" class="status-radio"
                                       {{ old('status', $sekolah->status) === $val ? 'checked' : '' }}>
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
                @else
                {{-- Admin sekolah: tampilkan status sebagai info saja (tidak bisa diubah) --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-cog"></i> Status</div>
                        <div class="d-flex align-items-center gap-2"
                             style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;">
                            @if($sekolah->status === 'aktif')
                                <i class="fas fa-check-circle" style="color:#16a34a;"></i>
                                <span style="font-size:.84rem;font-weight:600;color:#16a34a;">Aktif</span>
                            @else
                                <i class="fas fa-ban" style="color:#dc2626;"></i>
                                <span style="font-size:.84rem;font-weight:600;color:#dc2626;">Nonaktif</span>
                            @endif
                            <span class="ms-auto text-muted" style="font-size:.75rem;">
                                <i class="fas fa-lock me-1"></i> Hanya dapat diubah oleh admin nagari / camat
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tombol --}}
                <div class="d-flex gap-2 flex-column">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('sekolah.show', $sekolah->id_sekolah) }}" class="btn btn-cancel text-center">
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
        if (logoHolder) logoHolder.style.display = 'none';
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
// Daftar masyarakat sudah tersedia langsung dari server untuk semua role.
// - Superadmin  : semua masyarakat tersedia, tidak dibatasi nagari
// - Pegawai nagari: masyarakat di nagarinya saja
// - Admin sekolah : masyarakat dari nagarinya sendiri
</script>
@endsection
