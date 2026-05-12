@extends('layouts.user.user')

@section('title', 'Kategori Konten')

@section('content')
<div class="container">
    <div class="page-inner">



    <!-- TABLE KATEGORI -->

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Kategori</div>
        </div>
        <div class="card-body">
            <h1>Daftar Kategori</h1>
            <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#createModal">
                Tambah Kategori
            </button>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $index => $kat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kat->nama_kategori }}</td>
                            <td>{{ $kat->slug }}</td>
                            <td>
                                <span class="badge {{ $kat->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $kat->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <!-- TOMBOL EDIT -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $kat->id_kategori }}">
                                    Edit
                                </button>

                                <!-- MODAL EDIT -->
                                <div class="modal fade" id="editModal-{{ $kat->id_kategori }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $kat->id_kategori }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('kategori.update', $kat->id_kategori) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel-{{ $kat->id_kategori }}">Edit Kategori</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                                        <input type="text" name="nama_kategori" class="form-control"
                                                               value="{{ old('nama_kategori', $kat->nama_kategori) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="1" {{ $kat->status ? 'selected' : '' }}>Aktif</option>
                                                            <option value="0" {{ !$kat->status ? 'selected' : '' }}>Nonaktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL EDIT -->

                                <!-- FORM HAPUS -->
                                <form action="{{ route('kategori.destroy', $kat->id_kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                <!-- END FORM HAPUS -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="createModalLabel">Tambah Kategori</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="1">Aktif</option>
                  <option value="0">Nonaktif</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END MODAL CREATE -->

</div>
</div>
@endsection
