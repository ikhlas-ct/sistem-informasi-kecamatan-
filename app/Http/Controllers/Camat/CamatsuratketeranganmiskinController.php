<?php

namespace App\Http\Controllers\Camat;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Suratketeranganmiskin;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Pegawai\StatusSuratKeteranganNotification;


class CamatsuratketeranganmiskinController extends Controller
{
    public function surat_keterangan_miskin(Request $request)
    {
        $pegawai = auth()->user()->pegawai; // Ambil pegawai yang login (bisa camat/kepala nagari)

        $suratketeranganmiskin = Suratketeranganmiskin::query()
            ->where('validasi_pegawai', 'diterima')
            ->where('arsip', false)
            ->when($pegawai && $pegawai->id_nagari, function ($query) use ($pegawai) {
                // Filter hanya masyarakat yang nagarinya sama dengan pegawai
                $query->whereHas('masyarakat', function ($q) use ($pegawai) {
                    $q->where('id_nagari', $pegawai->id_nagari);
                });
            })
            ->when($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun'), function ($query) use ($request) {
                $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
                $query->whereDate('tanggal_pengajuan', $tanggal);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereMonth('tanggal_pengajuan', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('tanggal_pengajuan', $request->tahun);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        return view('pages.camat.suratketeranganmiskin.suratketeranganmiskin', compact('suratketeranganmiskin', 'request'));
    }

      public function camat_surat_keterangan_miskin(Request $request)
    {
        $pegawai = auth()->user()->pegawai; // Ambil pegawai yang login (bisa camat/kepala nagari)

        $suratketeranganmiskin = Suratketeranganmiskin::query()
            ->where('validasi_pegawai', 'diterima')
            ->where('status', 'selesai')
            ->when($pegawai && $pegawai->id_nagari, function ($query) use ($pegawai) {
                // Filter hanya masyarakat yang nagarinya sama dengan pegawai
                $query->whereHas('masyarakat', function ($q) use ($pegawai) {
                    $q->where('id_nagari', $pegawai->id_nagari);
                });
            })
            ->when($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun'), function ($query) use ($request) {
                $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
                $query->whereDate('tanggal_pengajuan', $tanggal);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereMonth('tanggal_pengajuan', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('tanggal_pengajuan', $request->tahun);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        return view('pages.camat.suratketeranganmiskin.suratketeranganmiskin', compact('suratketeranganmiskin', 'request'));
    }
    private function generateNomorSuratSKM($tanggalPengajuan, $lembaga = 'IVK')
    {
        $kodeJenis = '09';

        $currentMonth = Carbon::parse($tanggalPengajuan)->format('m');
        $currentYear = Carbon::parse($tanggalPengajuan)->format('Y');

        $lastSurat = Suratketeranganmiskin::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->whereNotNull('nomor_surat')
            ->where('status', 'selesai') // Hanya yang sudah diterima
            ->orderBy('nomor_surat', 'desc') // Urutkan dari yang terbesar
            ->first();

        // Jika ada surat sebelumnya, ambil nomor terakhir lalu tambah 1
        if ($lastSurat && preg_match('/\d{3}/', $lastSurat->nomor_surat, $matches)) {
            $lastNumber = (int) $matches[0]; // Ambil angka dari nomor surat terakhir
            $nomorUrut = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nomorUrut = '001'; // Jika belum ada surat, mulai dari 001
        }

        // Format bulan ke Romawi
        $bulanRomawi = [
            '01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV',
            '05' => 'V', '06' => 'VI', '07' => 'VII', '08' => 'VIII',
            '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII'
        ];
        $bulan = $bulanRomawi[$currentMonth];

        return "{$kodeJenis}.{$nomorUrut}/{$lembaga}/{$bulan}/{$currentYear}";
    }



    public function laporan(Request $request)
    {
        $suratketeranganmiskin = Suratketeranganmiskin::query()
            ->when($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun'), function ($query) use ($request) {
                $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
                $query->whereDate('tanggal_pengajuan', $tanggal);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereMonth('tanggal_pengajuan', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('tanggal_pengajuan', $request->tahun);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();


        return view('pages.camat.suratketeranganmiskin.laporan', [
            'suratketeranganmiskin' => $suratketeranganmiskin,
            'hari' => $request->hari,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,


        ]);
    }

    public function laporan_selesai(Request $request)
    {
        $suratketeranganmiskin = Suratketeranganmiskin::query()
            ->where('arsip', true)
            ->where('validasi_camat', 'diterima')
            ->where('validasi_pegawai', 'diterima')
            ->where('status', 'selesai')
            ->when($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun'), function ($query) use ($request) {
                $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
                $query->whereDate('tanggal_pengajuan', $tanggal);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereMonth('tanggal_pengajuan', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('tanggal_pengajuan', $request->tahun);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.camat.suratketeranganmiskin.laporan', [
            'suratketeranganmiskin' => $suratketeranganmiskin,
            'hari' => $request->hari,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);
    }

        public function print($id)
    {
        $surat = Suratketeranganmiskin::with('masyarakat')->findOrFail($id);
        $tanggungan = $surat->anggota;
        $surat->batas_penghasilan = $surat->pendapatan ?? 0;

        // Ambil Camat
        $camat = Pegawai::where('role', 'camat')->first();

        // Ambil Wali Nagari berdasarkan nagari masyarakat
        $waliNagari = null;
        if ($surat->masyarakat && $surat->masyarakat->id_nagari) {
            $waliNagari = Pegawai::where('jabatan_nagari', 'kepala_nagari')
                ->where('id_nagari', $surat->masyarakat->id_nagari)
                ->first();
        }

        return view('pages.camat.suratketeranganmiskin.print', compact('surat', 'tanggungan', 'camat', 'waliNagari'));
    }
    public function arsip(Request $request)
    {
        $suratketeranganmiskin = Suratketeranganmiskin::query()
            ->where('arsip', true)
            ->where('validasi_nagari', 'diterima')
            ->where('validasi_pegawai', 'diterima')
            ->where('status', 'selesai')

            ->when($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun'), function ($query) use ($request) {
                $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
                $query->whereDate('tanggal_pengajuan', $tanggal);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereMonth('tanggal_pengajuan', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('tanggal_pengajuan', $request->tahun);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        return view('pages.camat.suratketeranganmiskin.arsip', compact('suratketeranganmiskin', 'request'));
    }

    // Contoh fungsi verifikasi untuk walinagari/nagari
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'validasi_pengantar' => 'required|in:valid_nagari,ditolak',
            'validasi_pernyataan' => 'required|in:valid_nagari,ditolak',
        ]);

        $surat = Suratketeranganmiskin::findOrFail($id);
        $masyarakat = $surat->masyarakat;

        // Jika salah satu dokumen tidak valid, tolak
        if ($request->validasi_pengantar !== 'valid_nagari' || $request->validasi_pernyataan !== 'valid_nagari') {
            $surat->status = 'ditolak';
            $surat->validasi_nagari = 'ditolak';
            $surat->validasi_pegawai = 'ditolak';
            $surat->alasan_penolakan = 'Dokumen tidak valid atau tidak lengkap';
            $surat->validasi_pengantar = $request->validasi_pengantar;
            $surat->validasi_pernyataan = $request->validasi_pernyataan;
            $surat->save();

            // Notifikasi ke masyarakat jika perlu
            if ($masyarakat && $masyarakat->user) {
                $masyarakat->user->notify(new StatusSuratKeteranganNotification($surat));
            }

            return redirect()->route('nagari.surat_keterangan_miskin')->with('success', 'Pengajuan ditolak karena dokumen tidak valid.');
        }

        // Ambil singkatan nagari dari masyarakat
        $lembaga = $masyarakat && $masyarakat->nagari && $masyarakat->nagari->singkatan
            ? $masyarakat->nagari->singkatan
            : 'IVK';

        $nomorSurat = $this->generateNomorSuratSKM($surat->tanggal_pengajuan, $lembaga);

        $surat->update([
            'status' => 'selesai',
            'validasi_nagari' => 'diterima',
            'validasi_pengantar' => $request->validasi_pengantar,
            'validasi_pernyataan' => $request->validasi_pernyataan,
            'tanggal_selesai' => now(),
            'nomor_surat' => $nomorSurat,
        ]);

        // Notifikasi ke masyarakat jika perlu
        if ($masyarakat && $masyarakat->user) {
            $masyarakat->user->notify(new StatusSuratKeteranganNotification($surat, 'masyarakat'));
        }

        return redirect()->route('nagari.surat_keterangan_miskin')->with('success', 'Pengajuan berhasil diterima.');
    }
}
