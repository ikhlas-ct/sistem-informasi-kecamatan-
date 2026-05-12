@extends('layouts.user.user')

@section('title', 'Surat Keterangan Kurang Mampu')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center">
                <div class="card-title">
                    <i class="fas fa-file-alt me-2"></i> Daftar Surat Keterangan Kurang Mampu
                </div>
            </div>
            <div class="card-body">
                <!-- Tombol Tambah Surat Keterangan Miskin -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahSuratModal">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Surat Keterangan Kurang Mampu
                </button>

                @include('partials.alert.alert')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Alasan Pembuatan</th>
                                <th>Surat Pengantar RT/RW</th>
                                <th>Surat Pernyataan Pribadi</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratketeranganmiskin as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->alasan_pembuatan }}</td>
                                     <td class="text-center">
                                @if($item->surat_pengantar_rt_rw)
                                    <a href="{{ Storage::url($item->surat_pengantar_rt_rw) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                                <br>
                                @if(isset($item->validasi_pengantar))
                                    @if(in_array($item->validasi_pengantar, ['valid_pegawai', 'valid_nagari']))
                                        <span class="badge bg-success">Valid</span>
                                    @elseif($item->validasi_pengantar === 'ditolak')
                                        <span class="badge bg-danger">Tidak Valid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Divalidasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->surat_pernyataan_pribadi)
                                    <a href="{{ Storage::url($item->surat_pernyataan_pribadi) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                                <br>
                                @if(isset($item->validasi_pernyataan))
                                    @if(in_array($item->validasi_pernyataan, ['valid_pegawai', 'valid_nagari']))
                                        <span class="badge bg-success">Valid</span>
                                    @elseif($item->validasi_pernyataan === 'ditolak')
                                        <span class="badge bg-danger">Tidak Valid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Divalidasi</span>
                                @endif
                            </td>
                                    <td class="text-center">
                                        @if ($item->status === 'selesai')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> {{ ucfirst($item->status) }}
                                            </span>
                                        @elseif ($item->status === 'ditolak')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> {{ ucfirst($item->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half"></i> {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            @if ($item->status === 'ditolak' || $item->status === 'pending')
                                                <button
                                                    type="button"
                                                    class="btn btn-warning btn-sm px-3 py-1"
                                                    onclick="openEditModal({!! htmlspecialchars(json_encode([
                                                        'update_url' => route('masyarakat.surat_keterangan_miskin_update', $item->id_pelayanan),
                                                        'alasan_pembuatan' => $item->alasan_pembuatan,
                                                        'nama_lengkap' => $item->nama_lengkap,
                                                        'alamat' => $item->alamat,
                                                        'tempat_lahir' => $item->tempat_lahir,
                                                        'pekerjaan' => $item->pekerjaan, // <-- pastikan ini sesuai dengan field di database
                                                        'tanggal_lahir' => $item->tanggal_lahir,
                                                        'pendapatan' => $item->pendapatan,
                                                        'surat_pengantar_rt_rw_url' => $item->surat_pengantar_rt_rw ? Storage::url($item->surat_pengantar_rt_rw) : null,
                                                        'surat_pernyataan_pribadi_url' => $item->surat_pernyataan_pribadi ? Storage::url($item->surat_pernyataan_pribadi) : null,
                                                        'anggota' => $item->anggota->map(function($a) {
                                                            return [
                                                                'nama' => $a->nama,
                                                                'jk' => $a->jk,
                                                                'umur' => $a->umur,
                                                                'hubungan' => $a->hubungan,
                                                                'pekerjaan' => $a->pekerjaan,
                                                            ];
                                                        })->values(),
                                                    ]), ENT_QUOTES, 'UTF-8') !!})"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endif


                                            @if ($item->status == 'pending')

                                                <form action="{{ route('masyarakat.surat_keterangan_miskin_destroy', $item->id_pelayanan) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm px-3 py-1"
                                                            onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Surat Keterangan Miskin -->
<div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"> <!-- Header dengan warna -->
                <h5 class="modal-title" id="tambahSuratLabel">
                    <i class="fas fa-plus-circle"></i> Tambah Surat Keterangan Kurang Mampu
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto"> <!-- Overflow agar modal tidak terlalu panjang -->
                <form action="{{ route('masyarakat.surat_keterangan_miskin_store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alasan_pembuatan" class="form-label fw-bold">Alasan Pembuatan</label>
                            <textarea class="form-control" id="alasan_pembuatan" name="alasan_pembuatan" rows="3" required>{{ old('alasan_pembuatan') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="surat_pengantar_rt_rw" class="form-label fw-bold">Surat Pengantar RT/RW</label>
                            <input type="file" class="form-control" id="surat_pengantar_rt_rw" name="surat_pengantar_rt_rw" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="surat_pernyataan_pribadi" class="form-label fw-bold">Surat Pernyataan Pribadi</label>
                            <input type="file" class="form-control" id="surat_pernyataan_pribadi" name="surat_pernyataan_pribadi" required>
                        </div>
                        <div class="row">
                            {{-- Data Diri --}}
                               <div class="col-12 mb-2 mt-2">
                                <hr>
                                <h6 class="fw-bold text-primary">Yang Berkepeluan</h6>
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label fw-bold">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label fw-bold">Pekerjaan</label>
                                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan_pengaju" value="{{ old('pekerjaan_pengaju') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            </div>

                            {{-- Pemisah --}}
                            <div class="col-12 mb-2 mt-2">
                                <hr>
                                <h6 class="fw-bold text-primary">Anggota Keluarga</h6>
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="pendapatan" class="form-label fw-bold">Pendapatan Keluarga per Bulan (Rp)</label>
                                <input type="number" class="form-control form-control-lg" id="pendapatan" name="pendapatan" min="0" value="{{ old('pendapatan') }}" required style="width:100%;">
                            </div>


                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Anggota Keluarga (berdasarkan KK) dan Sesuaikan dengan urutan</label>
                            <table class="table table-bordered" id="anggota-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Umur</th>
                                        <th>Hubungan Keluarga</th>
                                        <th>Pekerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(old('anggota.nama'))
                                        @foreach(old('anggota.nama') as $i => $nama)
                                            <tr>
                                                <td>
                                                    <input type="text" name="anggota[nama][]" class="form-control" value="{{ $nama }}" required>
                                                </td>
                                                <td>
                                                    <select name="anggota[jk][]" class="form-control" required>
                                                        <option value="">Pilih</option>
                                                        <option value="L" {{ old('anggota.jk')[$i] == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                        <option value="P" {{ old('anggota.jk')[$i] == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="anggota[umur][]" class="form-control" value="{{ old('anggota.umur')[$i] }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="anggota[hubungan][]" class="form-control" value="{{ old('anggota.hubungan')[$i] }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="anggota[pekerjaan][]" class="form-control" value="{{ old('anggota.pekerjaan')[$i] }}" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm hapus-anggota">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-primary" id="tambah-anggota">Tambah Anggota</button>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Surat Keterangan Miskin -->
<div class="modal fade" id="editSuratModal" tabindex="-1" aria-labelledby="editSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editSuratLabel">
                    <i class="fas fa-edit"></i> Edit Surat Keterangan Kurang Mampu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto">
                <form id="form-edit-surat" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_alasan_pembuatan" class="form-label fw-bold">Alasan Pembuatan</label>
                            <textarea class="form-control" id="edit_alasan_pembuatan" name="alasan_pembuatan" rows="3" required></textarea>
                        </div>
                <div class="col-md-6 mb-3">
    <label for="edit_surat_pengantar_rt_rw" class="form-label fw-bold">Surat Pengantar RT/RW</label>
    <input type="file" class="form-control" id="edit_surat_pengantar_rt_rw" name="surat_pengantar_rt_rw">
    <div id="edit_surat_pengantar_rt_rw_link" class="mt-1"></div>
    <div id="edit_surat_pengantar_rt_rw_status"></div>
</div>

<div class="col-md-6 mb-3">
    <label for="edit_surat_pernyataan_pribadi" class="form-label fw-bold">Surat Pernyataan Pribadi</label>
    <input type="file" class="form-control" id="edit_surat_pernyataan_pribadi" name="surat_pernyataan_pribadi">
    <div id="edit_surat_pernyataan_pribadi_link" class="mt-1"></div>
    <div id="edit_surat_pernyataan_pribadi_status"></div>
</div>
                        <div class="row">
                            <div class="col-12 mb-2 mt-2">
                                <hr>
                                <h6 class="fw-bold text-primary">Yang Berkepeluan</h6>
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="edit_nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="edit_alamat" class="form-label fw-bold">Alamat</label>
                                <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tempat_lahir" class="form-label fw-bold">Tempat Lahir</label>
                                <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_pekerjaan" class="form-label fw-bold">Pekerjaan</label>
                                <input type="text" class="form-control" id="edit_pekerjaan" name="pekerjaan_pengaju" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="col-12 mb-2 mt-2">
                                <hr>
                                <h6 class="fw-bold text-primary">Anggota Keluarga</h6>
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="edit_pendapatan" class="form-label fw-bold">Pendapatan Keluarga per Bulan (Rp)</label>
                                <input type="number" class="form-control form-control-lg" id="edit_pendapatan" name="pendapatan" min="0" required style="width:100%;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Anggota Keluarga (berdasarkan KK) dan Sesuaikan dengan urutan</label>
                            <table class="table table-bordered" id="edit-anggota-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Umur</th>
                                        <th>Hubungan Keluarga</th>
                                        <th>Pekerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Akan diisi via JS -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-primary" id="edit-tambah-anggota">Tambah Anggota</button>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let anggotaTable = document.querySelector('#anggota-table tbody');
    let tambahBtn = document.getElementById('tambah-anggota');

    tambahBtn.addEventListener('click', function() {
        let row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="anggota[nama][]" class="form-control" required></td>
            <td>
                <select name="anggota[jk][]" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="L">laki-laki</option>
                    <option value="P">perempuan</option>
                </select>
            </td>
            <td><input type="number" name="anggota[umur][]" class="form-control" required></td>
            <td><input type="text" name="anggota[hubungan][]" class="form-control" required></td>
            <td><input type="text" name="anggota[pekerjaan][]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-anggota">Hapus</button></td>
        `;
        anggotaTable.appendChild(row);
    });

    anggotaTable.addEventListener('click', function(e) {
        if(e.target.classList.contains('hapus-anggota')) {
            e.target.closest('tr').remove();
        }
    });
});

// Script untuk mengisi data surat ke dalam form edit
function fillEditForm(data) {
    document.getElementById('edit_alasan_pembuatan').value = data.alasan_pembuatan;
    document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
    document.getElementById('edit_alamat').value = data.alamat;
    document.getElementById('edit_tempat_lahir').value = data.tempat_lahir;
    document.getElementById('edit_pekerjaan').value = data.pekerjaan;
    document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir;
    document.getElementById('edit_pendapatan').value = data.pendapatan;

    // Mengisi anggota keluarga
    let anggotaTable = document.querySelector('#edit-anggota-table tbody');
    anggotaTable.innerHTML = ''; // Kosongkan tabel sebelumnya
    data.anggota.forEach(function(anggota, index) {
        let row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="anggota[nama][]" class="form-control" value="${anggota.nama}" required></td>
            <td>
                <select name="anggota[jk][]" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="L" ${anggota.jk == 'L' ? 'selected' : ''}>laki-laki</option>
                    <option value="P" ${anggota.jk == 'P' ? 'selected' : ''}>perempuan</option>
                </select>
            </td>
            <td><input type="number" name="anggota[umur][]" class="form-control" value="${anggota.umur}" required></td>
            <td><input type="text" name="anggota[hubungan][]" class="form-control" value="${anggota.hubungan}" required></td>
            <td><input type="text" name="anggota[pekerjaan][]" class="form-control" value="${anggota.pekerjaan}" required></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-anggota">Hapus</button></td>
        `;
        anggotaTable.appendChild(row);
    });
}

// Event listener untuk tombol edit di tabel
document.querySelectorAll('a[data-bs-target="#editSuratModal"]').forEach(function(editBtn) {
    editBtn.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        // Ambil data surat berdasarkan ID
        fetch(`/api/surat-keterangan-miskin/${id}`)
            .then(response => response.json())
            .then(data => {
                // Isi form edit dengan data surat
                fillEditForm(data);
                // Tampilkan modal edit
                var myModal = new bootstrap.Modal(document.getElementById('editSuratModal'));
                myModal.show();
            });
    });
});
</script>

<script>
function openEditModal(data) {
    // Set action form
    document.getElementById('form-edit-surat').action = data.update_url;

    // Isi field utama
    document.getElementById('edit_alasan_pembuatan').value = data.alasan_pembuatan ?? '';
    document.getElementById('edit_nama_lengkap').value = data.nama_lengkap ?? '';
    document.getElementById('edit_alamat').value = data.alamat ?? '';
    document.getElementById('edit_tempat_lahir').value = data.tempat_lahir ?? '';
    document.getElementById('edit_pekerjaan').value = data.pekerjaan ?? '';
    document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir ?? '';
    document.getElementById('edit_pendapatan').value = data.pendapatan ?? '';

    // File preview
    document.getElementById('edit_surat_pengantar_rt_rw_link').innerHTML = data.surat_pengantar_rt_rw_url
        ? `<a href="${data.surat_pengantar_rt_rw_url}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Lihat File</a>`
        : '';
    document.getElementById('edit_surat_pernyataan_pribadi_link').innerHTML = data.surat_pernyataan_pribadi_url
        ? `<a href="${data.surat_pernyataan_pribadi_url}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Lihat File</a>`
        : '';

    // Badge validasi pengantar
    let pengantarStatus = '';
    if (data.validasi_pengantar) {
        if (data.validasi_pengantar === 'valid_nagari') {
            pengantarStatus = '<span class="badge bg-success">Valid</span>';
        } else if (data.validasi_pengantar === 'valid_pegawai') {
            pengantarStatus = '<span class="badge bg-warning text-dark">Proses</span><small class="text-warning">Menunggu validasi Nagari.</small>';
        } else if (data.validasi_pengantar === 'ditolak') {
            pengantarStatus = '<span class="badge bg-danger">Tidak Valid</span><small class="text-danger">Dokumen ini tidak valid. Silakan edit atau unggah ulang dokumen yang sesuai.</small>';
        } else if (data.validasi_pengantar) {
            pengantarStatus = '<span class="badge bg-warning text-dark">Pending</span><small class="text-warning">Dokumen ini sedang dalam proses validasi. Anda dapat mengunggah ulang jika diperlukan.</small>';
        } else {
            pengantarStatus = '<span class="badge bg-secondary">Belum Divalidasi</span><small class="text-muted">Dokumen belum divalidasi. Silakan unggah dokumen untuk memulai proses validasi.</small>';
        }
    } else {
        pengantarStatus = '<span class="badge bg-secondary">Belum Divalidasi</span><small class="text-muted">Dokumen belum divalidasi. Silakan unggah dokumen untuk memulai proses validasi.</small>';
    }
    document.getElementById('edit_surat_pengantar_rt_rw_status').innerHTML = pengantarStatus;

    // Badge validasi pernyataan
    let pernyataanStatus = '';
    if (data.validasi_pernyataan) {
        if (['valid_pegawai', 'valid_nagari'].includes(data.validasi_pernyataan)) {
            pernyataanStatus = '<span class="badge bg-success">Valid</span>';
        } else if (data.validasi_pernyataan === 'ditolak') {
            pernyataanStatus = '<span class="badge bg-danger">Tidak Valid</span><small class="text-danger">Dokumen ini tidak valid. Silakan edit atau unggah ulang dokumen yang sesuai.</small>';
        } else {
            pernyataanStatus = '<span class="badge bg-warning text-dark">Pending</span><small class="text-warning">Dokumen ini sedang dalam proses validasi. Anda dapat mengunggah ulang jika diperlukan.</small>';
        }
    } else {
        pernyataanStatus = '<span class="badge bg-secondary">Belum Divalidasi</span><small class="text-muted">Dokumen belum divalidasi. Silakan unggah dokumen untuk memulai proses validasi.</small>';
    }
    document.getElementById('edit_surat_pernyataan_pribadi_status').innerHTML = pernyataanStatus;

    // Isi anggota keluarga
    let tbody = document.querySelector('#edit-anggota-table tbody');
    tbody.innerHTML = '';
    if (data.anggota && data.anggota.length > 0) {
        data.anggota.forEach(function(anggota, i) {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="anggota[nama][]" class="form-control" value="${anggota.nama}" required></td>
                <td>
                    <select name="anggota[jk][]" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="L" ${anggota.jk === 'L' ? 'selected' : ''}>Laki-laki</option>
                        <option value="P" ${anggota.jk === 'P' ? 'selected' : ''}>Perempuan</option>
                    </select>
                </td>
                <td><input type="number" name="anggota[umur][]" class="form-control" value="${anggota.umur}" required></td>
                <td><input type="text" name="anggota[hubungan][]" class="form-control" value="${anggota.hubungan}" required></td>
                <td><input type="text" name="anggota[pekerjaan][]" class="form-control" value="${anggota.pekerjaan}" required></td>
                <td><button type="button" class="btn btn-danger btn-sm hapus-anggota">Hapus</button></td>
            `;
            tbody.appendChild(row);
        });
    }

    // Tambah anggota dinamis
    document.getElementById('edit-tambah-anggota').onclick = function() {
        let row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="anggota[nama][]" class="form-control" required></td>
            <td>
                <select name="anggota[jk][]" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </td>
            <td><input type="number" name="anggota[umur][]" class="form-control" required></td>
            <td><input type="text" name="anggota[hubungan][]" class="form-control" required></td>
            <td><input type="text" name="anggota[pekerjaan][]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-anggota">Hapus</button></td>
        `;
        tbody.appendChild(row);
    };

    tbody.onclick = function(e) {
        if(e.target.classList.contains('hapus-anggota')) {
            e.target.closest('tr').remove();
        }
    };

    // Tampilkan modal
    let modal = new bootstrap.Modal(document.getElementById('editSuratModal'));
    modal.show();
}
</script>
@endsection

@section('styles')
<style>
    #anggota-table th, #anggota-table td {
        font-size: 1.1rem;
        padding: 10px 8px !important;
        vertical-align: middle;
    }
    #anggota-table input,
    #anggota-table select {
        font-size: 1.1rem;
        padding: 8px 10px;
        height: 42px;
    }
    #anggota-table {
        width: 100%;
        min-width: 900px;
    }
    .modal-lg {
        max-width: 90% !important;
    }
</style>
@endsection

