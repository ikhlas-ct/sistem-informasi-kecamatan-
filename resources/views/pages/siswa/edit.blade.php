@extends('layouts.user.user')

@section('title', 'Edit Siswa – ' . $siswa->nama_siswa)

@section('styles')
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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

    .info-box{background:#f0fdfa;border:1.5px solid var(--accent-light);border-radius:10px;padding:10px 14px;font-size:.8rem;color:#0f766e;display:flex;align-items:flex-start;gap:8px;}
    .warn-box{background:#fef9c3;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;font-size:.8rem;color:#92400e;display:flex;align-items:flex-start;gap:8px;}

    .btn-simpan{background:linear-gradient(135deg,var(--accent),#0f766e);border:none;border-radius:10px;font-weight:700;font-size:.85rem;padding:10px 28px;color:#fff;}
    .btn-simpan:hover{filter:brightness(1.07);color:#fff;}
    .btn-batal{border-radius:10px;font-size:.85rem;border:1.5px solid #e2e8f0;color:#64748b;padding:9px 20px;}
    .btn-batal:hover{background:#f8fafc;}
    .btn-simpan .spinner-border{width:.85rem;height:.85rem;border-width:2px;}

    /* ── Select2 custom theme ── */
    .select2-container--default .select2-selection--single{
        border-radius:10px;border:1.5px solid #e2e8f0;height:42px;background:#f8fafc;
        display:flex;align-items:center;padding:0 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        color:#334155;font-size:.84rem;font-family:'Plus Jakarta Sans',sans-serif;padding:0;line-height:normal;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height:40px;right:8px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single{
        border-color:var(--accent);background:#fff;
        box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 15%,transparent);
    }
    .select2-container--default .select2-selection--single.is-invalid-s2{
        border-color:#ef4444;
    }
    .select2-dropdown{border:1.5px solid #e2e8f0;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.1);font-family:'Plus Jakarta Sans',sans-serif;font-size:.84rem;}
    .select2-container--default .select2-results__option--highlighted[aria-selected]{
        background:var(--accent);
    }
    .select2-container--default .select2-results__option[aria-selected=true]{
        background:var(--accent-light);color:var(--accent);
    }
    .select2-search--dropdown .select2-search__field{
        border-radius:8px;border:1.5px solid #e2e8f0;font-size:.83rem;padding:7px 10px;
    }
    .select2-container--default .select2-results__option.select2-results__message{
        color:#94a3b8;font-size:.8rem;
    }
    .select2-container{width:100%!important;}

    /* Badge current masyarakat */
    .masy-badge{background:#f0fdfa;border:1.5px solid var(--accent-light);border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:10px;margin-bottom:14px;}
    .masy-badge-avatar{width:36px;height:36px;border-radius:50%;background:var(--accent-light);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;}
    .masy-badge-name{font-size:.83rem;font-weight:700;color:#0f766e;}
    .masy-badge-nik{font-size:.76rem;color:#64748b;}
</style>
@endsection

@section('content')
@php
    $masyarakat = $siswa->user?->masyarakat;
    $u          = $siswa->user;
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

    <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST" id="form-edit">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- ── CARD: Akun / Masyarakat ── --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user-circle"></i> Akun Masyarakat</div>

                        {{-- Tampilkan masyarakat yang sedang terhubung --}}
                        <div class="masy-badge">
                            <div class="masy-badge-avatar">
                                @if($masyarakat?->foto_profil)
                                    <img src="{{ Storage::url($masyarakat->foto_profil) }}"
                                         style="width:36px;height:36px;border-radius:50%;object-fit:cover;" alt="">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div>
                                <div class="masy-badge-name">{{ $masyarakat?->nama_masyarakat ?? '-' }}</div>
                                <div class="masy-badge-nik">NIK: {{ $u?->nip_nik ?? '-' }}</div>
                            </div>
                            <span style="margin-left:auto;background:var(--accent);color:#fff;font-size:.71rem;padding:3px 10px;border-radius:20px;font-weight:700;">
                                Terhubung
                            </span>
                        </div>

                        <div class="info-box mb-3">
                            <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                            <span>
                                Anda dapat <strong>mengganti</strong> masyarakat yang terhubung ke siswa ini.
                                Hanya masyarakat yang <strong>belum menjadi siswa atau admin sekolah</strong> yang dapat dipilih.
                                Masyarakat lama otomatis dilepas.
                            </span>
                        </div>

                        <div>
                            <label class="form-label">Pilih / Ganti Masyarakat <span class="req">*</span></label>

                            {{--
                                Select2 AJAX – pre-populate dengan user saat ini.
                                exclude_user_id → agar user saat ini tetap muncul di hasil pencarian
                                meskipun sekolah-nya = 'siswa'.
                            --}}
                            <select name="id_user_masyarakat" id="select-masyarakat"
                                    class="@error('id_user_masyarakat') is-invalid-s2 @enderror"
                                    style="width:100%">
                                {{-- Pre-populate nilai saat ini --}}
                                <option value="{{ old('id_user_masyarakat', $siswa->id_user) }}" selected>
                                    {{ old('_masyarakat_text', $currentMasyarakatText) }}
                                </option>
                            </select>

                            @error('id_user_masyarakat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <div class="mt-2" style="font-size:.76rem;color:#94a3b8;">
                                <i class="fas fa-search me-1"></i>
                                Ketik nama atau NIK untuk mencari masyarakat lain.
                            </div>
                        </div>

                        {{-- Hidden: teks Select2 untuk re-populate setelah validasi gagal --}}
                        <input type="hidden" name="_masyarakat_text" id="hidden-masyarakat-text"
                               value="{{ old('_masyarakat_text', $currentMasyarakatText) }}">

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

                {{-- ── CARD: Penempatan Sekolah ── --}}
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

            </div>{{-- end col-lg-8 --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Info Akun --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-user-circle"></i> Info Akun</div>
                        <table style="width:100%;font-size:.82rem;border-collapse:collapse;">
                            <tr>
                                <td style="color:#94a3b8;padding:5px 0;width:40%;font-weight:600;">NIK</td>
                                <td style="color:#1e293b;font-weight:700;">{{ $u?->nip_nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#94a3b8;padding:5px 0;font-weight:600;">Sub-role</td>
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
                            <tr>
                                <td style="color:#94a3b8;padding:5px 0;font-weight:600;">Status</td>
                                <td>
                                    <span class="badge bg-{{ $u?->status === 'aktif' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $u?->status === 'aktif' ? 'success' : 'secondary' }}" style="font-size:.72rem;">
                                        {{ ucfirst($u?->status ?? '-') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#94a3b8;padding:5px 0;font-weight:600;">KK</td>
                                <td style="color:#334155;">{{ $masyarakat?->kk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#94a3b8;padding:5px 0;font-weight:600;">No. HP</td>
                                <td style="color:#334155;">{{ $masyarakat?->no_hp ?? '-' }}</td>
                            </tr>
                        </table>

                        <div class="info-box mt-3" style="font-size:.76rem;">
                            <i class="fas fa-lock flex-shrink-0"></i>
                            <span>Data profil masyarakat (nama, foto, dll.) dikelola melalui menu <strong>Masyarakat</strong>.</span>
                        </div>
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

                {{-- Badge info --}}
                <div class="card section-card" style="background:var(--accent-light);border:1.5px solid var(--accent);">
                    <div class="card-body py-3">
                        <div style="font-size:.8rem;color:#0f766e;font-weight:600;">
                            <i class="fas fa-shield-check me-1"></i> Pengecekan Otomatis
                        </div>
                        <div style="font-size:.77rem;color:#0f766e;margin-top:6px;line-height:1.7;">
                            Sistem tidak memperbolehkan memilih masyarakat yang sudah menjadi:
                            <ul style="padding-left:16px;margin:4px 0 0;">
                                <li>Siswa di sekolah lain</li>
                                <li>Admin sekolah manapun</li>
                            </ul>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {

    // ── Select2 AJAX: pilih / ganti masyarakat ─────────────────
    // exclude_user_id → agar user yang sedang terhubung tetap
    // muncul di hasil pencarian (sekolah-nya = 'siswa' tapi harus bisa dipilih ulang)
    const excludeUserId = {{ $siswa->id_user }};

    $('#select-masyarakat').select2({
        placeholder: '— Ketik nama atau NIK untuk mencari —',
        allowClear : false,   // tidak boleh kosong di edit
        minimumInputLength: 0,
        language: {
            inputTooShort: () => 'Ketik untuk mencari masyarakat...',
            noResults    : () => 'Masyarakat tidak ditemukan.',
            searching    : () => 'Mencari...',
            loadingMore  : () => 'Memuat lebih banyak...',
            errorLoading : () => 'Gagal memuat data.',
        },
        ajax: {
            url      : '{{ route('siswa.ajax.masyarakat') }}',
            dataType : 'json',
            delay    : 300,
            data     : params => ({ q: params.term, exclude_user_id: excludeUserId }),
            processResults: data => ({ results: data }),
            cache    : true,
        },
    });

    // Simpan teks pilihan ke hidden field
    $('#select-masyarakat').on('select2:select', function (e) {
        $('#hidden-masyarakat-text').val(e.params.data.text);
    });

    // ── Sekolah AJAX ────────────────────────────────────────────
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
            }).catch(() => {
                selectSekolah.innerHTML = '<option value="">Gagal memuat</option>';
                selectSekolah.disabled  = false;
            });
    }
    if (selectNagari.value) loadSekolah(selectNagari.value);
    selectNagari.addEventListener('change', function () { loadSekolah(this.value); });
    @endif

    @if($isNagari)
    (function () {
        const idNagari = document.getElementById('selected-nagari').value;
        const sel      = document.getElementById('select-sekolah');
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

    // ── Spinner submit ──────────────────────────────────────────
    document.getElementById('form-edit').addEventListener('submit', function () {
        const btn = document.getElementById('btn-submit');
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.disabled = true;
    });

});
</script>
@endsection
