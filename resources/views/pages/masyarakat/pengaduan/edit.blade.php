@extends('layouts.user.user')

@section('title', 'Edit Pengaduan')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center">
                <div class="card-title">
                    <i class="fas fa-file-alt me-2"></i> Edit Pengaduan
                </div>
            </div>
            <div class="card-body">
                @include('partials.alert.alert')
                <form id="editPengaduanForm" action="{{ route('masyarakat.pengaduan.update', $pengaduan->id_pengaduan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="judul_pengaduan" class="form-label">Judul Pengaduan</label>
                        <input type="text" class="form-control" id="judul_pengaduan" name="judul_pengaduan" value="{{ old('judul_pengaduan', $pengaduan->judul_pengaduan) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="hal_pengaduan" class="form-label">Hal Pengaduan</label>
                        <input type="text" class="form-control" id="hal_pengaduan" name="hal_pengaduan" value="{{ old('hal_pengaduan', $pengaduan->hal_pengaduan) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pengaduan" class="form-label">Tanggal Pengaduan</label>
                        <input type="date" class="form-control" id="tanggal_pengaduan" name="tanggal_pengaduan" value="{{ old('tanggal_pengaduan', $pengaduan->tanggal_pengaduan) }}" required>
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
                    <div class="mb-3">
                        <label for="existingFiles" class="form-label">File yang Sudah Ada</label>
                        <div class="row">
                            @foreach ($pengaduan->lampiran_pengaduan->where('tipe', 'file') as $lampiran)
                                <div class="col-md-6 mb-3">
                                    <a href="{{ Storage::url($lampiran->path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                    <button class="btn btn-danger btn-sm" type="button" onclick="deleteExistingFile('{{ $lampiran->id }}', this)">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="existingPhotos" class="form-label">Foto yang Sudah Ada</label>
                        <div class="row">
                            @foreach ($pengaduan->lampiran_pengaduan->where('tipe', 'gambar') as $lampiran)
                                <div class="col-md-6 mb-3">
                                    <img src="{{ Storage::url($lampiran->path) }}" alt="Lampiran Gambar" class="img-fluid rounded w-100" style="max-height:200px;">
                                    <button class="btn btn-danger btn-sm" type="button" onclick="deleteExistingFile('{{ $lampiran->id }}', this)">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="editPengaduanForm">Simpan</button>
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

    function deleteExistingFile(id, button) {
        const url = `{{ route('masyarakat.pengaduan.deleteFile', ':id') }}`.replace(':id', id);
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                button.parentElement.remove();
                alert('File berhasil dihapus.');
            } else {
                alert('Gagal menghapus file.');
            }
        });
    }
</script>
@endsection
