<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Surat Keterangan Kurang Mampu</title>
    <link rel="icon" href="{{ asset('storage/' . ($settings->logo ?? 'defaultimage/default_logo.png')) }}">

    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .container {
            width: 21cm;
            min-height: 29.7cm;
            margin: auto;
            padding: 2cm;
            border: 2px solid #000;
            box-sizing: border-box;
            background: #fff;
            position: relative;
        }

        .header {
            text-align: center;
            position: relative;
        }

        .header img {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 100px;
            z-index: 10;

        }

        .kop {
            text-align: center;
            font-size: 18px;
            padding-left: 100px;
        }

        .kop .address {
        display: block;
        clear: both;
        margin-top: 4px;
        word-break: break-word; /* memecah kata panjang */
        padding: 0 20px;        /* sedikit ruang kiri-kanan */
        text-align: center;     /* atau justify sesuai selera */
        }


        .kop h3, .kop h4 {
            margin: 0;
            font-weight: bold;
        }

        .kop p {
            margin: 4px 0;
            font-size: 14px;
        }

        hr {
            border: 1px solid black;
            margin-top: 10px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            position: relative;
            page-break-inside: avoid;
        }

        .signature p {
            margin: 2px 0;
            line-height: 1.2;
        }

        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}" alt="Logo">
            <div class="kop">
                <h3>PEMERINTAH KABUPATEN AGAM</h3>
                <h3>{{ strtoupper($settings->nama_kecamatan) }}</h3>
                <p class="address"><i>Alamat : {{ $settings->alamat_kecamatan }}</i></p>
            </div>
            <hr>
        </div>

        <div class="text-bl">
            <h2 style="text-align: center;"><strong>LAPORAN SURAT KETERANGAN KURANG MAMPU</strong></h2>
            @php
                use Carbon\Carbon;

                $periode = "Semua Periode";

                if (isset($hari) && isset($bulan) && isset($tahun)) {
                    $periode = Carbon::createFromDate($tahun, $bulan, $hari)->translatedFormat('d F Y');
                } elseif (isset($bulan) && isset($tahun)) {
                    $periode = Carbon::create($tahun, $bulan)->translatedFormat('F Y');
                } elseif (isset($tahun)) {
                    $periode = "Tahun $tahun";
                }
            @endphp
            <p style="font-size: 16px; font-weight: bold; text-align: center;">Periode: {{ $periode }}</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemohon</th>
                        <th>Nama Pegawai</th>
                        <th>Alamat</th>
                        <th>Tanggal Selesai</th>
                        <th>Alasan Pembuatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratketeranganmiskin as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $item->masyarakat->nama_masyarakat ?? '-' }}</td>
                            <td>{{ $item->pegawai->nama_pegawai ?? '-' }}</td>
                            <td>{{ $item->masyarakat->alamat ?? '-' }}</td>
                            <td style="text-align: center;">
                                {{ $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>{{ $item->alasan_pembuatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="signature">
            <p>{{ $settings->nama_kecamatan }}, {{ Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Camat {{ $settings->nama_kecamatan }}</p>
            <br><br><br>
            <p><strong>{{ $settings->camat->nama_pegawai }}</strong></p>
            <p>NIP: {{ $settings->camat->nip }}</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
