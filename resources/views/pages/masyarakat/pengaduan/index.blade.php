@extends('layouts.user.user')

@section('title', 'Pengaduan')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center">
                <div class="card-title">
                    <i class="fas fa-file-alt me-2"></i> Daftar Pengaduan
                </div>
                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createPengaduanModal">
                    Buat Pengaduan
                </button>
            </div>
            <div class="card-body">
                @include('partials.alert.alert')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Masyarakat</th>
                                <th>Judul Pengaduan</th>
                                <th>Hal Pengaduan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Tanggal Pengaduan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengaduans as $index => $pengaduan)
                                <tr>
                                    <td class="text-center">{{ ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + $index + 1 }}</td>
                                    <td>{{ $pengaduan->masyarakat->nama_masyarakat }}</td>
                                    <td>{{ $pengaduan->judul_pengaduan }}</td>
                                    <td>{{ $pengaduan->hal_pengaduan ?? 'kosong' }}</td>
                                    <td>{{ Str::words($pengaduan->deskripsi, 10, '...') }}</td>
                                    <td class="text-center">
                                        @if ($pengaduan->status === 'selesai')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> {{ ucfirst($pengaduan->status) }}
                                            </span>
                                        @elseif ($pengaduan->status === 'ditolak')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> {{ ucfirst($pengaduan->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half"></i> {{ ucfirst($pengaduan->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        @if($pengaduan->status !== 'selesai')
                                            <a href="{{ route('masyarakat.pengaduan.edit', $pengaduan->id_pengaduan) }}" class="btn btn-sm btn-primary mb-2">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('masyarakat.pengaduan.destroy', $pengaduan->id_pengaduan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-info mb-2" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengaduan->id_pengaduan }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $pengaduan->id_pengaduan }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $pengaduan->id_pengaduan }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailModalLabel{{ $pengaduan->id_pengaduan }}">Detail Pengaduan</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <p><strong>Judul Pengaduan:</strong> {{ $pengaduan->judul_pengaduan }}</p>
                                                    <p><strong>Hal Pengaduan:</strong> {{ $pengaduan->hal_pengaduan }}</p>
                                                    <p><strong>Deskripsi:</strong> {{ $pengaduan->deskripsi }}</p>
                                                    <p><strong>Status:</strong> {{ ucfirst($pengaduan->status) }}</p>
                                                    <p><strong>Tanggal Pengaduan:</strong> {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y') }}</p>
                                                </div>
                                                <div class="bg-primary text-white p-2 rounded mb-3">
                                                    <h6 class="mb-0 text-uppercase">Lampiran File</h6>
                                                </div>
                                                <div class="row">
                                                    @if($pengaduan->lampiran_pengaduan->where('tipe', 'file')->isNotEmpty())
                                                        @foreach ($pengaduan->lampiran_pengaduan->where('tipe', 'file') as $lampiran)
                                                            <div class="col-md-6 mb-3">
                                                                <p class="mb-1"><strong>{{ ucfirst($lampiran->tipe) }}:</strong></p>
                                                                <a href="{{ Storage::url($lampiran->path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-eye"></i> Lihat File
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-center">Tidak ada lampiran file</p>
                                                    @endif
                                                </div>
                                                <div class="bg-primary text-white p-2 rounded mb-3">
                                                    <h6 class="mb-0 text-uppercase">Lampiran Foto</h6>
                                                </div>
                                                <div class="row">
                                                    @if($pengaduan->lampiran_pengaduan->where('tipe', 'gambar')->isNotEmpty())
                                                        @foreach ($pengaduan->lampiran_pengaduan->where('tipe', 'gambar') as $lampiran)
                                                            <div class="col-md-6 mb-3">
                                                                <p class="mb-1"><strong>{{ ucfirst($lampiran->tipe) }}:</strong></p>
                                                                <img src="{{ Storage::url($lampiran->path) }}" alt="Lampiran Gambar" class="img-fluid rounded w-100" style="max-height:200px;">
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-center">Tidak ada lampiran foto</p>
                                                    @endif
                                                </div>
                                                @if ($pengaduan->balasanpengaduan)
                                                    <div class="bg-success text-white p-2 rounded mb-3">
                                                        <h6 class="mb-0 text-uppercase">Balasan Pengaduan</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p><strong>Nama Pegawai:</strong> {{ $pengaduan->balasanpengaduan->pegawai->nama_pegawai }}</p>
                                                        <p><strong>Isi Balasan:</strong> {{ $pengaduan->balasanpengaduan->balasan }}</p>
                                                        <p><strong>Tanggal Balasan:</strong> {{ \Carbon\Carbon::parse($pengaduan->balasanpengaduan->tanggal_balasan)->format('d M Y') }}</p>
                                                    </div>
                                                    <div class="bg-success text-white p-2 rounded mb-3">
                                                        <h6 class="mb-0 text-uppercase">Lampiran File Balasan</h6>
                                                    </div>
                                                    <div class="row">
                                                        @if($pengaduan->balasanpengaduan->lampiran_balasan->where('tipe', 'file')->isNotEmpty())
                                                            @foreach ($pengaduan->balasanpengaduan->lampiran_balasan->where('tipe', 'file') as $lampiran)
                                                                <div class="col-md-6 mb-3">
                                                                    <p class="mb-1"><strong>File:</strong></p>
                                                                    <a href="{{ Storage::url($lampiran->path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                        <i class="fas fa-eye"></i> Lihat File
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p class="text-center">Tidak ada lampiran file balasan</p>
                                                        @endif
                                                    </div>
                                                    <div class="bg-success text-white p-2 rounded mb-3">
                                                        <h6 class="mb-0 text-uppercase">Lampiran Foto Balasan</h6>
                                                    </div>
                                                    <div class="row">
                                                        @if($pengaduan->balasanpengaduan->lampiran_balasan->where('tipe', 'gambar')->isNotEmpty())
                                                            @foreach ($pengaduan->balasanpengaduan->lampiran_balasan->where('tipe', 'gambar') as $lampiran)
                                                                <div class="col-md-6 mb-3">
                                                                    <p class="mb-1"><strong>Gambar:</strong></p>
                                                                    <img src="{{ Storage::url($lampiran->path) }}" alt="Lampiran Gambar" class="img-fluid rounded w-100" style="max-height:200px;">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p class="text-center">Tidak ada lampiran foto balasan</p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="bg-warning text-dark p-2 rounded mb-3">
                                                        <h6 class="mb-0 text-uppercase">Balasan Pengaduan</h6>
                                                    </div>
                                                    <p class="text-center">Belum ada balasan untuk pengaduan ini.</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <section id="blog-pagination" class="blog-pagination section">
                    <div class="container">
                        <ul class="pagination justify-content-end">
                            {{ $pengaduans->links('pagination::custom') }}
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createPengaduanModal" tabindex="-1" aria-labelledby="createPengaduanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Tambahkan modal-lg di sini -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPengaduanModalLabel">Buat Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createPengaduanForm" action="{{ route('masyarakat.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="judul_pengaduan" class="form-label">Judul Pengaduan</label>
                        <input type="text" class="form-control" id="judul_pengaduan" name="judul_pengaduan" required>
                    </div>
                    <div class="mb-3">
                        <label for="hal_pengaduan" class="form-label">Hal Pengaduan</label>
                        <input type="text" class="form-control" id="hal_pengaduan" name="hal_pengaduan" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="files" class="form-label">Pilih File</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="files" name="files[]" multiple>
                            <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">Hapus</button>
                        </div>
                    </div>
                    <div id="additionalFiles"></div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addFileInput()">Tambah File</button>
                    <div class="mb-3">
                        <label for="photos" class="form-label">Pilih Foto</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="photos" name="photos[]" multiple>
                            <button class="btn btn-danger" type="button" onclick="removePhotoInput(this)">Hapus</button>
                        </div>
                    </div>
                    <div id="additionalPhotos"></div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addPhotoInput()">Tambah Foto</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="createPengaduanForm">Kirim</button>
            </div>
        </div>
    </div>
</div>

<script>
    function addFileInput() {
        const additionalFiles = document.getElementById('additionalFiles');
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-3');
        div.innerHTML = `
            <input type="file" class="form-control" name="files[]">
            <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">Hapus</button>
        `;
        additionalFiles.appendChild(div);
    }

    function removeFileInput(button) {
        button.parentElement.remove();
    }

    function addPhotoInput() {
        const additionalPhotos = document.getElementById('additionalPhotos');
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-3');
        div.innerHTML = `
            <input type="file" class="form-control" name="photos[]">
            <button class="btn btn-danger" type="button" onclick="removePhotoInput(this)">Hapus</button>
        `;
        additionalPhotos.appendChild(div);
    }

    function removePhotoInput(button) {
        button.parentElement.remove();
    }
</script>
@endsection
