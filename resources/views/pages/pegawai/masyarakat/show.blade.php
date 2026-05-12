@extends('layouts.user.user')

@section('title', 'Detail Masyarakat')

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('pegawai.profil') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Masyarakat</li>
      </ol>
    </nav>

    {{-- Konten Utama --}}
    <div class="container-fluid ps-md-4">

        {{-- Header Page --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">Detail Masyarakat</h1>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
        </div>

        {{-- Informasi Masyarakat Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Masyarakat</h5>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-4 text-center">
                        @php
                            $foto = $masyarakat->foto_profil
                                ? asset('storage/'.$masyarakat->foto_profil)
                                : asset('defaultimage/no_image_available.jpg');
                        @endphp
                        <img src="{{ $foto }}" class="img-thumbnail rounded-circle mb-2" width="150" alt="Foto Profil">
                        <h5 class="mt-2">{{ $masyarakat->nama_masyarakat }}</h5>
                        <small class="text-muted">{{ $masyarakat->alamat }}</small>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <table class="table">
                                <tbody>
                                  <tr><th>ID Masyarakat</th>      <td>{{ $masyarakat->id_masyarakat }}</td></tr>
                                  <tr><th>NIK</th>                <td>{{ $masyarakat->nik }}</td></tr>
                                  <tr><th>ID User</th>            <td>{{ $masyarakat->id_user }}</td></tr>
                                  <tr><th>KK</th>                 <td>{{ $masyarakat->kk }}</td></tr>
                                  <tr><th>Jenis Kelamin</th>      <td>{{ ucfirst($masyarakat->jenis_kelamin) }}</td></tr>
                                  <tr><th>No HP</th>              <td>{{ $masyarakat->no_hp }}</td></tr>
                                  <tr><th>Nama Masyarakat</th>    <td>{{ $masyarakat->nama_masyarakat }}</td></tr>
                                  <tr><th>Nama Ibu</th>           <td>{{ $masyarakat->nama_ibu }}</td></tr>
                                  <tr><th>Alamat</th>             <td>{!! nl2br(e($masyarakat->alamat)) !!}</td></tr>
                                  <tr><th>Pekerjaan</th>          <td>{{ $masyarakat->pekerjaan }}</td></tr>

                                  {{-- file upload: tampilkan sebagai link atau thumbnail --}}
                                  <tr>
                                    <th>Scan KTP</th>
                                    <td>
                                      @if($masyarakat->scan_ktp)
                                        <a href="{{ asset('storage/'.$masyarakat->scan_ktp) }}" target="_blank">Lihat Scan KTP</a>
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Scan KK</th>
                                    <td>
                                      @if($masyarakat->scan_kk)
                                        <a href="{{ asset('storage/'.$masyarakat->scan_kk) }}" target="_blank">Lihat Scan KK</a>
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Foto Diri KTP</th>
                                    <td>
                                      @if($masyarakat->foto_diri_ktp)
                                        <img src="{{ asset('storage/'.$masyarakat->foto_diri_ktp) }}" width="100">
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Foto Diri Akta</th>
                                    <td>
                                      @if($masyarakat->foto_diri_akta)
                                        <img src="{{ asset('storage/'.$masyarakat->foto_diri_akta) }}" width="100">
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Akta Kelahiran</th>
                                    <td>
                                      @if($masyarakat->akta_kelahiran)
                                        <img src="{{ asset('storage/'.$masyarakat->akta_kelahiran) }}" width="100">
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>




                                  <tr>
                                    <th>Foto Profil</th>
                                    <td>
                                      @if($masyarakat->foto_profil)
                                        <img src="{{ asset('storage/'.$masyarakat->foto_profil) }}" width="100">
                                      @else
                                        -
                                      @endif
                                    </td>
                                  </tr>
                                  <tr><th>Deskripsi</th>          <td>{!! nl2br(e($masyarakat->deskripsi)) !!}</td></tr>
                                  <tr><th>Facebook</th>           <td>{{ $masyarakat->facebook }}</td></tr>
                                  <tr><th>Instagram</th>          <td>{{ $masyarakat->instagram }}</td></tr>
                                  <tr><th>Twitter</th>            <td>{{ $masyarakat->twitter }}</td></tr>
                                </tbody>
                              </table>

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
              @if($masyarakat->konten->isEmpty())
                <div class="p-3 text-center text-muted">
                  Masyarakat ini belum membuat konten apapun.
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
                      @foreach($masyarakat->konten as $i => $k)
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

      {{-- Pengaduan yang Diajukan --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Pengaduan yang Diajukan</h5>
    </div>
    <div class="card-body p-0">
        @if($masyarakat->pengaduan->isEmpty())
            <div class="p-3 text-center text-muted">Belum ada pengaduan yang diajukan.</div>
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
                    @foreach($masyarakat->pengaduan as $i => $pengaduan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $pengaduan->judul_pengaduan }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $pengaduan->status=='selesai'?'success':'warning' }}">
                                {{ ucfirst($pengaduan->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('balasan_pengaduan.index', $pengaduan->id_pengaduan) }}" class="btn btn-sm btn-info">Lihat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Surat Keterangan Miskin yang Diajukan --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Surat Keterangan Miskin yang Diajukan</h5>
    </div>
    <div class="card-body p-0">
        @if($masyarakat->pelayananadministrasi->isEmpty())
            <div class="p-3 text-center text-muted">Belum ada surat keterangan miskin yang diajukan.</div>
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
                    @foreach($masyarakat->pelayananadministrasi as $j => $surat)
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ $masyarakat->nama_masyarakat }}</td>
                        <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $surat->status=='selesai'?'success':'secondary' }}">
                                {{ ucfirst($surat->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pegawai.surat_keterangan_miskin', $surat->id_pelayanan) }}" class="btn btn-sm btn-info">Detail</a>
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
