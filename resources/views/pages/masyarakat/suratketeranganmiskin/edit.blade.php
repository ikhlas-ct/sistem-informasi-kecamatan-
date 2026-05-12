@extends('layouts.user.user')

@section('title', 'Edit Surat Keterangan Miskin')

@section('content')
<div class="container py-4">
    <div class="page-inner">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <div class="card-title">
                    <i class="fas fa-edit me-2"></i> Edit Surat Keterangan Miskin
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('masyarakat.surat_keterangan_miskin_update', $suratketeranganmiskin->id_pelayanan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Alasan Pembuatan -->
                    <div class="mb-3">
                        <label for="alasan_pembuatan" class="form-label">Alasan Pembuatan</label>
                        <textarea class="form-control" id="alasan_pembuatan" name="alasan_pembuatan" rows="3" required>{{ $suratketeranganmiskin->alasan_pembuatan }}</textarea>
                    </div>

                    <!-- Surat Pengantar RT/RW -->
                    <div class="mb-3">
                        <label for="surat_pengantar_rt_rw" class="form-label">Surat Pengantar RT/RW</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="surat_pengantar_rt_rw" name="surat_pengantar_rt_rw">
                            <a href="{{ Storage::url($suratketeranganmiskin->surat_pengantar_rt_rw) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-file-pdf me-1"></i> Lihat
                            </a>
                        </div>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah file.</small>
                    </div>

                    <!-- Surat Pernyataan Pribadi -->
                    <div class="mb-3">
                        <label for="surat_pennyataan_pribadi" class="form-label">Surat Pernyataan Pribadi</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="surat_pennyataan_pribadi" name="surat_pennyataan_pribadi">
                            <a href="{{ Storage::url($suratketeranganmiskin->surat_pennyataan_pribadi) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-file-pdf me-1"></i> Lihat
                            </a>
                        </div>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah file.</small>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('masyarakat.surat_keterangan_miskin') }}" class="btn btn-secondary">
                            <i class="fas fa-times-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
