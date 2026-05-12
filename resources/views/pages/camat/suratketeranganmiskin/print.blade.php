<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Surat Keterangan Kurang Mampu</title>
        <style>
            body {
                font-family: 'Times New Roman', serif;
                line-height: 1.6;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 21cm;
                min-height: 29.7cm;
                margin: auto;
                padding: 1cm 2cm 2cm 2cm;
                /* atas 1cm, kanan 2cm, bawah 2cm, kiri 2cm */
                border: 1px solid #000;
                box-sizing: border-box;
                page-break-inside: avoid;
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
                word-break: break-word;
                /* memecah kata panjang */
                padding: 0 20px;
                /* sedikit ruang kiri-kanan */
                text-align: center;
                /* atau justify sesuai selera */
            }

            .kop h3,
            .kop h4 {
                margin: 0;
            }

            .kop p {
                margin: 4px 0;
            }

            hr {
                border: 1px solid black;
                margin-top: 10px;
            }

            .title {
                text-align: center;
                margin-top: 20px;
                text-decoration: underline;
                font-weight: bold;
            }

            .title h4 {
                margin: 0;
                /* Supaya tulisan "SURAT KETERANGAN KURANG MAMPU" juga nempel */
            }

            .nomor {
                text-align: center;
                margin: 0;
                /* Supaya langsung nempel ke atas */
                padding: 0;
            }

            .nomor p {
                margin: 0;
                /* Tambahan supaya teks di <p> juga tidak berjarak */
            }

            .content {
                margin-top: 20px;
            }

            .content p {
                text-align: justify;
                text-indent: 30px;
            }

            .data-table {
                margin-left: 40px;
                margin-bottom: 20px;
                width: 100%;
                border-collapse: collapse;
            }

            .data-table td {
                vertical-align: top;
                padding: 2px 0;
                /* lebih kecil dari sebelumnya */
                line-height: 1;
                /* jarak antar baris jadi 1 */
            }

            .data-table tr td:first-child {
                width: 180px;
            }

            .dependents-table {
                width: 100%;
                border-collapse: collapse;
                margin-left: 40px;
                margin-top: 10px;
            }

            .dependents-table th,
            .dependents-table td {
                border: 1px solid #000;
                padding: 6px;
                text-align: center;
            }

            .dependents-table th {
                background: #f0f0f0;
            }

            .signature {
                margin-top: 30px;
                width: 100%;
                overflow: hidden;
                page-break-inside: avoid;
                display: table;
            }

            .signature .left-sign {
                float: left;
                text-align: center;
            }

            .signature .right-sign {
                float: right;
                text-align: center;
            }

            .signature p {
                margin: 4px 0;
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

                .container {
                    page-break-after: avoid;
                    page-break-before: avoid;
                    page-break-inside: avoid;
                }
            }
        </style>
    </head>

    <body>
        <div class="container">
            <!-- HEADER -->
            <div class="header">
                <img src="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}"
                    alt="Logo">
                <div class="kop">
                    @php
                        $nagari = $surat->masyarakat->nagari ?? null;
                    @endphp

                    <h3>PEMERINTAH KABUPATEN AGAM</h3>
                    <h3>KECAMATAN IV KOTO</h3>
                    <h3>WALINAGARI {{ strtoupper($nagari->nama_nagari ?? 'BALINGKA') }}</h3>
                    <p class="address">
                        <i>Alamat : {{ $nagari->alamat ?? $settings->alamat_kecamatan }}</i>
                    </p>
                </div>
                <hr>
            </div>

            <!-- JUDUL & NOMOR SURAT -->
            <div class="title">
                <h4>SURAT KETERANGAN KURANG MAMPU</h4>
            </div>
            <div class="nomor">
                <p>Nomor : {{ $surat->nomor_surat }}</p>
            </div>

            <!-- ISI SURAT -->
            <div class="content">
                <p>
                    Yang bertanda tangan di bawah ini Wali Nagari Balingka Kecamatan IV Koto Kabupaten Agam, dengan ini
                    menerangkan bahwa:
                </p>

                <table class="data-table">
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $surat->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tempat / Tgl Lahir</td>
                        <td>: {{ $surat->tempat_lahir ?? '-' }},
                            {{ \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d-m-Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>: {{ $surat->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Sekarang</td>
                        <td>: {{ $surat->alamat ?? '-' }}</td>
                    </tr>
                </table>

                <p>
                    Menurut sepengetahuan kami orang tersebut di atas adalah keluarga <strong>Kurang Mampu</strong>,
                    mempunyai penghasilan kurang dari <strong>Rp.
                        {{ number_format($surat->batas_penghasilan, 0, ',', '.') }},-/bulan</strong>, dengan jumlah
                    tanggungan keluarga <strong>{{ count($tanggungan) }} orang</strong> sebagai berikut:
                </p>

                <!-- TABEL TANGGUNGAN -->
                <table class="dependents-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Umur</th>
                            <th>Hubungan</th>
                            <th>Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tanggungan as $i => $anggota)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="text-align: left; padding-left: 8px;">{{ $anggota->nama }}</td>
                                <td>
                                    @if ($anggota->jk === 'L')
                                        Laki-Laki
                                    @elseif($anggota->jk === 'P')
                                        Perempuan
                                    @else
                                        {{ $anggota->jk ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if ($anggota->umur)
                                        {{ $anggota->umur }} Tahun
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $anggota->hubungan }}</td>
                                <td>{{ $anggota->pekerjaan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data tanggungan keluarga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <p>
                    Surat keterangan ini dibuat sebagai salah satu syarat untuk Pengurusan Administrasi di Pengadilan
                    Agama Maninjau dari orang tersebut di atas.
                </p>

                <p>
                    Demikianlah surat keterangan ini dikeluarkan untuk dapat diketahui dan dipergunakan sebagaimana
                    mestinya.
                </p>
            </div>

            <!-- TANDA TANGAN -->
            <div class="signature">
                <!-- Tanda tangan kiri (Camat) -->
                <div class="left-sign">
                    <p>Mengetahui,</p>
                    <p>Camat IV Koto</p>
                    <br><br><br>
                    <p><strong>{{ $camat->nama_pegawai ?? '-' }}</strong></p>
                    <p>NIP: {{ $camat->nip ?? '-' }}</p>
                </div>

                <!-- Tanda tangan kanan (Wali Nagari) -->
                <div class="right-sign">
                    <p>
                        {{ $surat->masyarakat && $surat->masyarakat->nagari ? $surat->masyarakat->nagari->nama_nagari : '-' }},
                        {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->format('d F Y') ?? '-' }}
                    </p>
                    <p>Wali Nagari</p>
                    <br><br><br>
                    <p><strong>{{ $waliNagari->nama_pegawai ?? '-' }}</strong></p>
                    <p>NIP: {{ $waliNagari->nip ?? '-' }}</p>
                </div>
            </div>
        </div>

        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </body>

</html>
