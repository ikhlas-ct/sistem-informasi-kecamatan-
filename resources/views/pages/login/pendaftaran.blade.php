<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Masyarakat</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="icon" href="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}">
  <style>
    body { background: #f8f9fa; }
    .card { border-radius: 20px; }
    .form-control, .form-select { border-radius: 10px; }
    .btn-lg { border-radius: 10px; }
    .form-floating > label { left: 1rem; }
    .is-invalid { border-color: #dc3545; }
    .invalid-feedback { color: #dc3545; font-size: 0.85rem; }
    .info-box {
      background: #e8f4fd;
      border-left: 4px solid #0d6efd;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 0.875rem;
      color: #0a4a8a;
    }
    .step-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 24px; height: 24px;
      border-radius: 50%;
      background: #0d6efd;
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      margin-right: 8px;
      flex-shrink: 0;
    }
    .password-toggle { cursor: pointer; }
  </style>
</head>
<body>
  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card shadow-lg">
            <div class="card-body p-5">

              {{-- Header --}}
              <div class="text-center mb-4">
                <img src="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}"
                     height="70" class="mb-2" alt="Logo">
                <h3 class="fw-bold">{{ $settings->nama_kecamatan ?? 'Nama Kecamatan' }}</h3>
                <p class="text-muted mb-0">Aktivasi Akun Masyarakat</p>
              </div>

              {{-- Info box --}}
              <div class="info-box mb-4">
                <div class="d-flex align-items-start mb-1">
                  <span class="step-badge">i</span>
                  <span>Data Anda harus sudah terdaftar oleh petugas nagari sebelum melakukan aktivasi akun.</span>
                </div>
                <div class="d-flex align-items-start mt-2 mb-1">
                  <span class="step-badge">1</span>
                  <span>Masukkan NIK dan Nomor KK sesuai identitas Anda untuk verifikasi.</span>
                </div>
                <div class="d-flex align-items-start mt-2">
                  <span class="step-badge">2</span>
                  <span>Buat password untuk masuk ke sistem.</span>
                </div>
              </div>

              {{-- Error global --}}
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              {{-- Success --}}
              @if (session('success'))
                <div class="alert alert-success">
                  <i class="bi bi-check-circle-fill me-1"></i>
                  {{ session('success') }}
                </div>
              @endif

              {{-- ─── FORM ─────────────────────────────────────── --}}
              <form action="{{ route('register.post') }}" method="POST" id="registerForm" novalidate>
                @csrf

                {{-- Separator --}}
                <p class="text-muted fw-semibold mb-3" style="font-size:0.85rem; letter-spacing:.05em;">
                  VERIFIKASI IDENTITAS
                </p>

                {{-- NIK --}}
                <div class="form-floating mb-1">
                  <input type="text"
                         name="nik"
                         id="nik"
                         class="form-control @error('nik') is-invalid @enderror"
                         value="{{ old('nik') }}"
                         placeholder="NIK"
                         required
                         pattern="\d{16}"
                         maxlength="16"
                         inputmode="numeric"
                         autocomplete="off">
                  <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                  @error('nik')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @else
                    <div class="invalid-feedback">Harap masukkan 16 digit NIK (angka saja).</div>
                  @enderror
                </div>
                <small class="text-muted d-block mb-3">Masukkan 16 digit NIK sesuai KTP.</small>

                {{-- KK --}}
                <div class="form-floating mb-1">
                  <input type="text"
                         name="kk"
                         id="kk"
                         class="form-control @error('kk') is-invalid @enderror"
                         value="{{ old('kk') }}"
                         placeholder="Nomor KK"
                         required
                         pattern="\d{16}"
                         maxlength="16"
                         inputmode="numeric"
                         autocomplete="off">
                  <label for="kk">Nomor Kartu Keluarga (KK)</label>
                  @error('kk')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @else
                    <div class="invalid-feedback">Masukkan 16 digit nomor KK (angka saja).</div>
                  @enderror
                </div>
                <small class="text-muted d-block mb-3">Masukkan 16 digit nomor KK.</small>

                <hr class="my-3">
                <p class="text-muted fw-semibold mb-3" style="font-size:0.85rem; letter-spacing:.05em;">
                  BUAT PASSWORD
                </p>

                {{-- Password --}}
                <div class="form-floating mb-1 position-relative">
                  <input type="password"
                         name="password"
                         id="password"
                         class="form-control @error('password') is-invalid @enderror"
                         placeholder="Password"
                         required
                         minlength="8"
                         autocomplete="new-password">
                  <label for="password">Password</label>
                  <span class="position-absolute top-50 end-0 translate-middle-y pe-3 password-toggle"
                        onclick="togglePassword('password', this)">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                         class="text-secondary" viewBox="0 0 16 16">
                      <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                      <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>
                  </span>
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @else
                    <div class="invalid-feedback">Password minimal 8 karakter.</div>
                  @enderror
                </div>
                <small class="text-muted d-block mb-3">Password minimal 8 karakter.</small>

                {{-- Konfirmasi Password --}}
                <div class="form-floating mb-1 position-relative">
                  <input type="password"
                         name="password_confirmation"
                         id="password_confirmation"
                         class="form-control"
                         placeholder="Konfirmasi Password"
                         required
                         autocomplete="new-password">
                  <label for="password_confirmation">Konfirmasi Password</label>
                  <span class="position-absolute top-50 end-0 translate-middle-y pe-3 password-toggle"
                        onclick="togglePassword('password_confirmation', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                         class="text-secondary" viewBox="0 0 16 16">
                      <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                      <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>
                  </span>
                  <div class="invalid-feedback">Konfirmasi password harus sama dengan password.</div>
                </div>
                <small class="text-muted d-block mb-3">Pastikan sama dengan password di atas.</small>

                {{-- Submit --}}
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary btn-lg">
                    Aktivasi Akun
                  </button>
                </div>

                <p class="text-center mt-3 mb-0">
                  Sudah punya akun?
                  <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                </p>
              </form>
              {{-- ─────────────────────────────────────────────── --}}

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<script>
  // ── Toggle show/hide password ──────────────────────────────────
  function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    // swap icon opacity sebagai feedback visual sederhana
    btn.style.opacity = isText ? '1' : '0.5';
  }

  // ── Hanya izinkan angka pada NIK & KK ─────────────────────────
  ['nik', 'kk'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  });

  // ── Validasi password match real-time ─────────────────────────
  const passwordField  = document.getElementById('password');
  const confirmField   = document.getElementById('password_confirmation');

  function checkPasswordMatch() {
    if (confirmField.value === '') return; // jangan validasi jika belum diisi
    if (passwordField.value !== confirmField.value) {
      confirmField.setCustomValidity('Konfirmasi password harus sama dengan password');
      confirmField.classList.add('is-invalid');
    } else {
      confirmField.setCustomValidity('');
      confirmField.classList.remove('is-invalid');
    }
  }

  passwordField.addEventListener('input', checkPasswordMatch);
  confirmField.addEventListener('input', checkPasswordMatch);

  // ── Validasi saat blur ─────────────────────────────────────────
  document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('blur', function () {
      if (!this.checkValidity()) {
        this.classList.add('is-invalid');
      } else {
        this.classList.remove('is-invalid');
      }
    });
  });

  // ── Validasi form sebelum submit ───────────────────────────────
  document.getElementById('registerForm').addEventListener('submit', function (e) {
    checkPasswordMatch(); // pastikan cek match sekali lagi saat submit

    if (!this.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    this.classList.add('was-validated');
  });
</script>
</body>
</html>
