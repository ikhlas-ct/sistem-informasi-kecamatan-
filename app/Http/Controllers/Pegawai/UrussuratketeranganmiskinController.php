<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Suratketeranganmiskin;
use App\Notifications\Pegawai\StatusSuratKeteranganNotification;
use App\Models\User;

class UrussuratketeranganmiskinController extends Controller
{

    public function surat_keterangan_miskin(Request $request)
    {
        $pegawai = auth()->user()->pegawai;

        $query = Suratketeranganmiskin::whereHas('masyarakat', function($q) use ($pegawai) {
            // Filter hanya masyarakat yang nagarinya sama dengan pegawai
            $q->where('id_nagari', $pegawai->id_nagari);
        });

        if ($request->filled('hari')) {
            $query->whereDay('tanggal_pengajuan', $request->hari);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pengajuan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pengajuan', $request->tahun);
        }

        $suratketeranganmiskin = $query->paginate(10);

        return view('pages.pegawai.suratketeranganmiskin.suratketeranganmiskin', compact('suratketeranganmiskin', 'request'));
    }




    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'bantuan_untuk' => 'required|string|max:255',
            'validasi_pengantar' => 'required|in:valid_pegawai,ditolak',
            'validasi_pernyataan' => 'required|in:valid_pegawai,ditolak',
        ]);

        $surat = Suratketeranganmiskin::findOrFail($id);
        $masyarakat = $surat->masyarakat;

        // Jika salah satu dokumen tidak valid, tolak dan notif masyarakat
        if ($request->validasi_pengantar !== 'valid_pegawai' || $request->validasi_pernyataan !== 'valid_pegawai') {
            $surat->status = 'ditolak';
            $surat->validasi_pegawai = 'ditolak';
            $surat->validasi_nagari = 'ditolak';
            $surat->id_pegawai = auth()->user()->pegawai->id_pegawai;
            $surat->alasan_penolakan = 'Dokumen tidak valid atau tidak lengkap';
            $surat->validasi_pengantar = $request->validasi_pengantar;
            $surat->validasi_pernyataan = $request->validasi_pernyataan;
            $surat->save();

            // Notifikasi ke masyarakat hanya saat ditolak
            if ($masyarakat && $masyarakat->user) {
                $masyarakat->user->notify(new StatusSuratKeteranganNotification($surat));
            }

            return redirect()->route('pegawai.surat_keterangan_miskin')->with('success', 'Pengajuan ditolak karena dokumen tidak valid.');
        }

        // Jika semua dokumen valid, terima dan notif masyarakat
        $surat->update([
            'status' => 'diproses',
            'validasi_pegawai' => 'diterima',
            'validasi_nagari' => 'diproses',
            'bantuan_untuk' => $request->bantuan_untuk,
            'id_pegawai' => auth()->user()->pegawai->id_pegawai,
            'validasi_pengantar' => $request->validasi_pengantar,
            'validasi_pernyataan' => $request->validasi_pernyataan,
        ]);

        // Notifikasi ke masyarakat hanya saat diterima ke camat
        if ($masyarakat && $masyarakat->user) {
            $masyarakat->user->notify(new StatusSuratKeteranganNotification($surat, 'masyarakat'));
        }

        $jumlahSurat = Suratketeranganmiskin::where('validasi_pegawai', 'diterima')
            ->where('validasi_nagari', 'diproses')
            ->count();

        $camatUsers = User::where('role', 'camat')->get();
        foreach ($camatUsers as $camat) {
            $existing = $camat->unreadNotifications->first(function($notif) {
                return isset($notif->data['type']) && $notif->data['type'] === 'surat';
            });
            if ($existing) {
                $data = $existing->data;
                $data['message'] = 'Ada ' . $jumlahSurat . ' surat keterangan miskin baru yang perlu diperiksa.';
                $data['count'] = $jumlahSurat;
                $existing->update(['data' => $data]);
            } else {
                $camat->notify(new StatusSuratKeteranganNotification(null, 'camat', $jumlahSurat, ['type' => 'surat']));
            }
        }

        return redirect()->route('pegawai.surat_keterangan_miskin')->with('success', 'Pengajuan berhasil diterima.');
    }

}
