@extends('layouts.user.user')

@section('title', 'Detail Pegawai')

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('pegawai.profil') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Pegawai</li>
      </ol>
    </nav>

    {{-- Konten Utama dengan padding kiri agar tidak terlalu dekat dengan sidebar --}}
    <div class="container-fluid ps-md-4">

        {{-- Header Page --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">Detail Pegawai</h1>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
        </div>

        {{-- Informasi Pegawai Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-4 text-center">
                        @php
                            $foto = $pegawai->foto_profil
                                ? asset('storage/'.$pegawai->foto_profil)
                                : asset('defaultimage/no_image_available.jpg');
                        @endphp
                        <img src="{{ $foto }}" class="img-thumbnail rounded-circle mb-2" width="150" alt="Foto Profil">
                        <h5 class="mt-2">{{ $pegawai->nama_pegawai }}</h5>
                        <small class="text-muted">{{ $pegawai->jabatan }}</small>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <div class="col-md-8">
                                    <table class="table table-borderless mb-0">
                                      <tbody>
                                

                                        <tr>
                                          <th scope="row">NIK</th>
                                          <td>{{ $pegawai->nik }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">NIP</th>
                                          <td>{{ $pegawai->nip }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Role</th>
                                          <td>{{ ucfirst($pegawai->role) }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Jenis Kelamin</th>
                                          <td>{{ ucfirst($pegawai->jenis_kelamin) }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Pangkat / Golongan</th>
                                          <td>{{ $pegawai->pangkat_golongan }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Jabatan</th>
                                          <td>{{ $pegawai->jabatan }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Alamat</th>
                                          <td>{!! nl2br(e($pegawai->alamat_pegawai)) !!}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">No HP</th>
                                          <td>{{ $pegawai->nohp_pegawai }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Email</th>
                                          <td>{{ $pegawai->email_pegawai }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Twitter</th>
                                          <td>{{ $pegawai->twitter ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Facebook</th>
                                          <td>{{ $pegawai->facebook ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Instagram</th>
                                          <td>{{ $pegawai->instagram ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Deskripsi</th>
                                          <td>{!! nl2br(e($pegawai->deskripsi)) !!}</td>
                                        </tr>


                                      </tbody>
                                    </table>
                                  </div>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten yang Dibuat --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
              <h5 class="mb-0">Konten yang Dibuat</h5>
            </div>
            <div class="card-body p-0">
              @if($pegawai->konten->isEmpty())
                <div class="p-3 text-center text-muted">
                  Pegawai ini belum membuat konten apapun.
                </div>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>No</th>
                        <th>Jenis Konten</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pegawai->konten as $i => $k)
                        <tr>
                          <td>{{ $i + 1 }}</td>
                          <td>{{ ucwords(str_replace('_', ' ', $k->jenis_konten)) }}</td>
                          <td>{{ $k->judul }}</td>
                          <td>{{ $k->created_at->translatedFormat('d F Y') }}</td>
                          <td class="text-center">
                            <a href="{{ route('konten.detail', ['jenis_konten'=>$k->jenis_konten,'slug'=>$k->slug]) }}" class="btn btn-sm btn-info">
                              Lihat
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
        </div>

        {{-- Pengaduan yang Ditangani --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pengaduan yang Ditangani</h5>
            </div>
            <div class="card-body p-0">
                @if($pegawai->balasanpengaduan->isEmpty())
                    <div class="p-3 text-center text-muted">Belum ada pengaduan yang ditangani.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Pengaduan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pegawai->balasanpengaduan as $i => $balasan)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $balasan->pengaduan->judul_pengaduan }}</td>
                                <td>{{ \Carbon\Carbon::parse($balasan->pengaduan->tanggal_pengaduan)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $balasan->pengaduan->status=='selesai'?'success':'warning' }}">
                                        {{ ucfirst($balasan->pengaduan->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('balasan_pengaduan.index',$balasan->pengaduan->id_pengaduan) }}" class="btn btn-sm btn-info">Lihat</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Surat Keterangan Miskin --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Surat Keterangan Miskin yang Ditangani</h5>
            </div>
            <div class="card-body p-0">
                @if($pegawai->pelayananadministrasi->isEmpty())
                    <div class="p-3 text-center text-muted">Belum ada surat keterangan miskin yang ditangani.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pemohon</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pegawai->pelayananadministrasi as $j => $surat)
                            <tr>
                                <td>{{ $j + 1 }}</td>
                                <td>{{ $surat->masyarakat->nama_masyarakat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $surat->status=='selesai'?'success':'secondary' }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('camat.surat_keterangan_miskin_print',$surat->id_pelayanan) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div> {{-- end container-fluid --}}

@endsection
