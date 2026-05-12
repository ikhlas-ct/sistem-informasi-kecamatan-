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
    .invalid-feedback { color: #dc3545; }
    .file-input-info { font-size: 0.875rem; }
  </style>
</head>
<body>
  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card shadow-lg">
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <img src="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}" height="70" class="mb-2">
                <h3 class="fw-bold">{{ $settings->nama_kecamatan ?? 'Nama Kecamatan' }}</h3>
                <p class="text-muted">Formulir Pendaftaran Masyarakat</p>
              </div>

              {{-- Tampilkan error --}}
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
                <div class="alert alert-success">{{ session('success') }}</div>
              @endif

              {{-- FORM --}}
              <form action="{{ route('register.post') }}" method="POST"  id="registerForm" novalidate>
                @csrf

                {{-- NIK --}}
                <div class="form-floating mb-1">
                  <input type="text" name="nik" id="nik" class="form-control"
                         value="{{ old('nik') }}"
                         placeholder="NIK"
                         required
                         pattern="\d{16}"
                         maxlength="16"
                         oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                  <div class="invalid-feedback">Harap masukkan 16 digit NIK (angka saja).</div>
                </div>
                <small class="text-muted d-block mb-3">Masukkan 16 digit NIK sesuai KTP.</small>
                                {{-- Nomor KK --}}
                <div class="form-floating mb-1">
                  <input type="text" name="kk" id="kk" class="form-control"
                         value="{{ old('kk') }}"
                         placeholder="Nomor KK"
                         required
                         minlength="16"
                         maxlength="16"
                         pattern="\d{16}"
                         oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  <label for="kk">Nomor Kartu Keluarga (KK)</label>
                  <div class="invalid-feedback">Masukkan 16 digit nomor KK. (angka saja).</div>
                </div>
                <small class="text-muted d-block mb-3">Masukkan 16 digit nomor KK.</small>

                       {{-- Nama Lengkap --}}
                <div class="form-floating mb-1">
                  <input type="text" name="nama_masyarakat" id="nama_masyarakat"
                         class="form-control"
                         value="{{ old('nama_masyarakat') }}"
                         placeholder="Nama Lengkap"
                         required
                         maxlength="255">
                  <label for="nama_masyarakat">Nama Lengkap</label>
                  <div class="invalid-feedback">Nama lengkap wajib diisi (maksimal 100 karakter).</div>
                </div>
                <small class="text-muted d-block mb-3">Masukkan nama lengkap sesuai identitas.</small>




                <div class="form-floating mb-1">
                  <input type="text" name="nama_ibu" id="nama_ibu" class="form-control"
                         value="{{ old('nama_ibu') }}"
                         placeholder="Nama Ibu Kandung"
                         maxlength="255">
                  <label for="nama_ibu">Nama Ibu Kandung </label>
                  <div class="invalid-feedback">Maksimal 35 karakter.</div>
                </div>
                <small class="text-muted d-block mb-3"></small>
                 {{-- Nagari --}}
         
                <select name="id_nagari" id="id_nagari" class="form-select" required>
                <option value="">Pilih Nagari</option>
                @foreach($nagari as $n)
                    <option value="{{ $n->id }}" {{ old('id_nagari') == $n->id ? 'selected' : '' }}>
                    {{ $n->nama_nagari }}
                    </option>
                @endforeach
                </select>
                <small class="text-muted d-block mb-3">Pilih nagari tempat tinggal Anda.</small>
                {{-- Password --}}
                <div class="form-floating mb-1">
                  <input type="password" name="password" id="password" class="form-control"
                         placeholder="Password"
                         required
                         minlength="8">
                  <label for="password">Password</label>
                  <div class="invalid-feedback">Password minimal 8 karakter.</div>
                </div>
                <small class="text-muted d-block mb-3">Password minimal 8 karakter.</small>

                {{-- Konfirmasi Password --}}
                <div class="form-floating mb-1">
                  <input type="password" name="password_confirmation" id="password_confirmation"
                         class="form-control"
                         placeholder="Konfirmasi Password"
                         required
                         data-match="#password">
                  <label for="password_confirmation">Konfirmasi Password</label>
                  <div class="invalid-feedback">Konfirmasi password harus sama dengan password.</div>
                </div>
                <small class="text-muted d-block mb-3">Pastikan konfirmasi password sama dengan password di atas.</small>



                <div class="d-grid">
                  <button type="submit" class="btn btn-primary btn-lg">Daftar</button>
                </div>

                <p class="text-center mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<script>
    // Validasi form sebelum submit
    document.getElementById('registerForm').addEventListener('submit', function(event) {
      if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      this.classList.add('was-validated');
    });

    // Validasi password match
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    passwordConfirm.addEventListener('input', function() {
      if (password.value !== this.value) {
        this.setCustomValidity('Konfirmasi password harus sama dengan password');
        this.classList.add('is-invalid');
      } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
      }
    });

    // Validasi real-time
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
      input.addEventListener('input', function() {
        this.classList.remove('is-invalid');

        // Validasi khusus untuk NIK (hanya angka)
        if (this.id === 'nik') {
          this.value = this.value.replace(/[^0-9]/g, '');
        }

        // Validasi password match
        if (this.id === 'password_confirmation') {
          if (password.value !== this.value) {
            this.setCustomValidity('Konfirmasi password harus sama dengan password');
            this.classList.add('is-invalid');
          } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
          }
        }
      });

      // Validasi saat kehilangan fokus
      input.addEventListener('blur', function() {
        if (!this.checkValidity()) {
          this.classList.add('is-invalid');
        }
      });
    });
  </script>
</body>
</html>
