@extends('layouts.user.user')

@section('title', 'Data Masyarakat')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar penduduk</div>
            </div>
            <div class="card-body">
                <!-- Tombol Tambah Masyarakat -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahMasyarakatModal">
                    Tambah Penduduk
                </button>
                <form action="{{ route('camat.masyarakat.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            value="{{ old('search', $keyword) }}"
                            class="form-control"
                            placeholder="Cari nama atau NIK..."
                        >
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>

                @include('partials.alert.alert')

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Masyarakat</th>
                                <th>NIK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($masyarakats as $index => $masyarakat)
                                <tr>
                                    <td>{{ $loop->iteration + ($masyarakats->currentPage() - 1) * $masyarakats->perPage() }}</td>
                                    <td>{{ $masyarakat->nama_masyarakat }}</td>
                                    <td>{{ $masyarakat->nik }}</td>
                                    <td>
                                        @if ($masyarakat->user->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('camat.masyarakat.show', $masyarakat->id_masyarakat) }}"
                                           class="btn btn-info btn-sm">Lihat</a>

                                        <!-- tombol pemicu modal -->
                                        <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPasswordModal-{{ $masyarakat->id_masyarakat }}">
                                          Edit Password
                                        </button>

                                        <form action="{{ route('camat.masyarakat.toggleStatus', $masyarakat->id_masyarakat) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                   name="status"
                                                   value="{{ $masyarakat->user->status === 'aktif' ? 'nonaktif' : 'aktif' }}">
                                            <button type="submit"
                                                    class="btn btn-sm btn-{{ $masyarakat->user->status === 'aktif' ? 'danger' : 'success' }}"
                                                    onclick="return confirm('Yakin ingin {{ $masyarakat->user->status === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }} masyarakat ini?')">
                                                {{ $masyarakat->user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Modal Edit Masyarakat -->
                                        <div class="modal fade" id="editMasyarakatModal-{{ $masyarakat->id_masyarakat }}" tabindex="-1" aria-labelledby="editMasyarakatLabel-{{ $masyarakat->id_masyarakat }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="editMasyarakatLabel-{{ $masyarakat->id_masyarakat }}">Edit Masyarakat</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('camat.masyarakat.update', $masyarakat->id_masyarakat) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="nama_masyarakat_edit_{{ $masyarakat->id_masyarakat }}" class="form-label">Nama Masyarakat</label>
                                                                    <input type="text" name="nama_masyarakat" id="nama_masyarakat_edit_{{ $masyarakat->id_masyarakat }}" class="form-control" value="{{ $masyarakat->nama_masyarakat }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="nik_edit_{{ $masyarakat->id_masyarakat }}" class="form-label">NIK</label>
                                                                    <input type="text" name="nik" id="nik_edit_{{ $masyarakat->id_masyarakat }}" class="form-control" value="{{ $masyarakat->nik }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="kk_edit_{{ $masyarakat->id_masyarakat }}" class="form-label">No KK</label>
                                                                    <input type="text" name="kk" id="kk_edit_{{ $masyarakat->id_masyarakat }}" class="form-control" value="{{ $masyarakat->kk }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="nama_ibu_edit_{{ $masyarakat->id_masyarakat }}" class="form-label">Nama Ibu</label>
                                                                    <input type="text" name="nama_ibu" id="nama_ibu_edit_{{ $masyarakat->id_masyarakat }}" class="form-control" value="{{ $masyarakat->nama_ibu }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="id_nagari_edit_{{ $masyarakat->id_masyarakat }}" class="form-label">Nagari</label>
                                                                    <select name="id_nagari" id="id_nagari_edit_{{ $masyarakat->id_masyarakat }}" class="form-control" required>
                                                                        @foreach($nagari as $n)
                                                                            <option value="{{ $n->id }}" {{ $masyarakat->id_nagari == $n->id ? 'selected' : '' }}>{{ $n->nama_nagari }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer mt-3">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-warning">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade"
                                     id="editPasswordModal-{{ $masyarakat->id_masyarakat }}"
                                     tabindex="-1"
                                     aria-labelledby="editPasswordLabel-{{ $masyarakat->id_masyarakat }}"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('camat.masyarakat.updatePassword', $masyarakat->id_masyarakat) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <div class="modal-header bg-warning text-white">
                                                    <h5 class="modal-title"
                                                        id="editPasswordLabel-{{ $masyarakat->id_masyarakat }}">
                                                        Ubah Password: {{ $masyarakat->nama_masyarakat }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="password-{{ $masyarakat->id_masyarakat }}"
                                                               class="form-label">Password Baru</label>
                                                        <input type="password"
                                                               name="password"
                                                               id="password-{{ $masyarakat->id_masyarakat }}"
                                                               class="form-control @error('password') is-invalid @enderror"
                                                               required>
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="password_confirmation-{{ $masyarakat->id_masyarakat }}"
                                                               class="form-label">Konfirmasi Password</label>
                                                        <input type="password"
                                                               name="password_confirmation"
                                                               id="password_confirmation-{{ $masyarakat->id_masyarakat }}"
                                                               class="form-control"
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit"
                                                            class="btn btn-warning">Simpan Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <section id="blog-pagination" class="blog-pagination section">
                        <div class="container">
                            <ul class="pagination justify-content-end">
                                {{ $masyarakats->links('pagination::custom') }}
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Masyarakat -->
<div class="modal fade" id="tambahMasyarakatModal" tabindex="-1" aria-labelledby="tambahMasyarakatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahMasyarakatLabel">Tambah Masyarakat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('camat.masyarakat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" name="nik" id="nik" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kk" class="form-label">No KK</label>
                            <input type="text" name="kk" id="kk" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_masyarakat" class="form-label">Nama Masyarakat</label>
                            <input type="text" name="nama_masyarakat" id="nama_masyarakat" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_ibu" class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_nagari" class="form-label">Nagari</label>
                            <select name="id_nagari" id="id_nagari" class="form-control" required>
                                <option value="">Pilih Nagari</option>
                                @foreach($nagari as $n)
                                    <option value="{{ $n->id }}">{{ $n->nama_nagari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="twitter" class="form-label">Twitter</label>
                            <input type="text" name="twitter" id="twitter" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="text" name="facebook" id="facebook" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan" class="form-control">
                        </div>
                        <!-- File upload opsional -->
                        <div class="col-md-6 mb-3">
                            <label for="foto_profil" class="form-label">Foto Profil</label>
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
