@extends('layouts.user.user')

@section('title', 'Surat Keterangan Miskin')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center">
                <div class="card-title">
                    <i class="fas fa-file-alt me-2"></i> Daftar Surat Keterangan Miskin
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('camat.surat_keterangan_miskin') }}" method="GET" class="mb-4">
                    <div class="row">
                        <!-- Tanggal -->
                        <div class="col-md-3">
                            <label for="hari">Hari</label>
                            <input type="number" id="hari" name="hari" class="form-control" min="1" max="31"
                                   placeholder="1-31" value="{{ old('hari', $request->hari) }}">
                        </div>

                        <!-- Bulan -->
                        <div class="col-md-3">
                            <label for="bulan">Bulan</label>
                            <select id="bulan" name="bulan" class="form-control">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $request->bulan == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Tahun -->
                        <div class="col-md-3">
                            <label for="tahun">Tahun</label>
                            <select id="tahun" name="tahun" class="form-control">
                                <option value="">Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" {{ $request->tahun == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Tombol Cari -->
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Cari</button>

                            <!-- Tombol Print -->
                            <a href="" class="btn btn-secondary" target="_blank">Print</a>
                        </div>
                    </div>
                </form>

                @include('partials.alert.alert')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Pengaju</th>
                                <th>Alasan Pembuatan</th>
                                <th>Surat Pengantar RT/RW</th>
                                <th>Surat Pernyataan Pribadi</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratketeranganmiskin as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->masyarakat->nama_masyarakat }}</td>

                                    <td>
                                        <span title="{{ $item->alasan_pembuatan }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                            {{ Str::words($item->alasan_pembuatan, 3, '...') }}
                                        </span>
                                    </td>
                                    <td>
                                          <a href="{{ Storage::url($item->surat_pengantar_rt_rw) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Lihat File
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ Storage::url($item->surat_pernyataan_pribadi) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Lihat File
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status === 'selesai')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> {{ ucfirst($item->status) }}
                                            </span>
                                        @elseif ($item->status === 'Ditolak')
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
                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>

                                        <!-- Tombol Terima -->
                                        <form action="{{ route('camat.surat_keterangan_miskin_terima', ['id' => $item->id_pelayanan]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success mb-2" onclick="return confirm('Apakah Anda yakin ingin menerima pengajuan ini?')">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('camat.surat_keterangan_miskin_tolak', ['id' => $item->id_pelayanan]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-secondary" onclick="printSurat('{{ route('camat.surat_keterangan_miskin_print', ['id' => $item->id_pelayanan]) }}')">
                                            <i class="fas fa-print"></i> Print
                                        </button>

                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail Pengajuan</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Bagian Data Diri Pengaju -->
                                                <div class="bg-primary text-white p-2 rounded mb-3">
                                                    <h6 class="mb-0 text-uppercase">Data Diri Pengaju</h6>
                                                </div>
                                                <div class="mb-2">
                                                    <p class="mb-1"><strong>Nama Pengaju:</strong> {{ $item->masyarakat->nama_masyarakat }}</p>
                                                    <p class="mb-1"><strong>NIK:</strong> {{ $item->masyarakat->nik }}</p>
                                                    <p class="mb-1"><strong>Alasan Pembuatan:</strong> {{ $item->alasan_pembuatan }}</p>
                                                    <p class="mb-1"><strong>Status:</strong> {{ $item->status }}</p>
                                                    <p class="mb-1"><strong>Tanggal Pengajuan:</strong> {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}</p>
                                                </div>

                                                <!-- Bagian File Foto -->
                                                <div class="bg-primary text-white p-2 rounded mb-3">
                                                    <h6 class="mb-0 text-uppercase">File Foto</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Foto Kartu Keluarga:</strong></p>
                                                        @if($item->masyarakat->scan_kk)
                                                            <img src="{{ Storage::url($item->masyarakat->scan_kk) }}" alt="Foto KK" class="img-fluid rounded w-100" style="max-height:200px;">
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Foto KTP:</strong></p>
                                                        @if($item->masyarakat->scan_ktp)
                                                            <img src="{{ Storage::url($item->masyarakat->scan_ktp) }}" alt="Foto KTP" class="img-fluid rounded w-100" style="max-height:200px;">
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Foto Akta:</strong></p>
                                                        @if($item->masyarakat->akta_kelahiran)
                                                            <img src="{{ Storage::url($item->masyarakat->akta_kelahiran) }}" alt="Foto Akta" class="img-fluid rounded w-100" style="max-height:200px;">
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Foto Diri dengan KTP:</strong></p>
                                                        @if($item->masyarakat->foto_diri_ktp)
                                                            <img src="{{ Storage::url($item->masyarakat->foto_diri_ktp) }}" alt="Foto Diri KTP" class="img-fluid rounded w-100" style="max-height:200px;">
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Foto Diri Akta:</strong></p>
                                                        @if($item->masyarakat->foto_diri_akta)
                                                            <img src="{{ Storage::url($item->masyarakat->foto_diri_akta) }}" alt="Foto Diri Akta" class="img-fluid rounded w-100" style="max-height:200px;">
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Bagian File Dokumen -->
                                                <div class="bg-primary text-white p-2 rounded mb-3">
                                                    <h6 class="mb-0 text-uppercase">File Dokumen Pendukung</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Surat Pengantar RT/RW:</strong></p>
                                                        @if($item->surat_pengantar_rt_rw)
                                                            <a href="{{ Storage::url($item->surat_pengantar_rt_rw) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-eye"></i> Lihat File
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Surat Pernyataan Pribadi:</strong></p>
                                                        @if($item->surat_pernyataan_pribadi)
                                                            <a href="{{ Storage::url($item->surat_pernyataan_pribadi) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-eye"></i> Lihat File
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                </div>
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
            </div>
        </div>
    </div>
</div>

<iframe id="print-frame" name="print-frame" style="display: none;"></iframe>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('[data-bs-target^="#detailModal"]');

        detailButtons.forEach(button => {
            button.addEventListener('click', function () {
                const modalId = this.getAttribute('data-bs-target');
                const modal = document.querySelector(modalId);
                const detailUrl = this.getAttribute('data-url');

                fetch(detailUrl)
                    .then(response => response.json())
                    .then(data => {
                        modal.querySelector('.modal-body').innerHTML = `
                            <p><strong>Nama Pengaju:</strong> ${data.masyarakat.nama_masyarakat}</p>
                            <p><strong>Alasan Pembuatan:</strong> ${data.alasan_pembuatan}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                            <p><strong>Tanggal Pengajuan:</strong> ${data.tanggal_pengajuan}</p>
                        `;
                    });
            });
        });
    });
    function printSurat(url) {
        const iframe = document.getElementById('print-frame');
        iframe.src = url;

        iframe.onload = function () {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        };
    }
</script>
@endsection
