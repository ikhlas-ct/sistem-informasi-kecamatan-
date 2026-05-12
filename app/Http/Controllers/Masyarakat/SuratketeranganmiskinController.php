<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suratketeranganmiskin;
use App\Models\Masyarakat;
use App\Models\Surat_anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Notifications\Pegawai\StatusSuratKeteranganNotification;


class SuratketeranganmiskinController extends Controller
{
    // Menampilkan daftar surat keterangan miskin
    public function surat_keterangan_miskin()
    {
        $user_id = Auth::id();
        $masyarakat = Masyarakat::where('id_user', $user_id)->first();
        $suratketeranganmiskin = Suratketeranganmiskin::where('id_masyarakat', $masyarakat->id_masyarakat)->get();
        return view('pages.masyarakat.suratketeranganmiskin.suratketeranganmiskin', compact('suratketeranganmiskin'));
    }

    // Menyimpan surat keterangan miskin baru
    public function surat_keterangan_miskin_store(Request $request)
    {
        $request->validate([
            'alasan_pembuatan' => 'required',
            'surat_pengantar_rt_rw' => 'required|file|mimes:pdf|max:2048',
            'surat_pernyataan_pribadi' => 'required|file|mimes:pdf|max:2048',
            'nama_lengkap' => 'required|string',
            'alamat' => 'required|string',
            'pendapatan' => 'required|numeric',
            'pekerjaan_pengaju' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'anggota.nama.*' => 'required|string',
            'anggota.jk.*' => 'required|string',
            'anggota.umur.*' => 'required|numeric',
            'anggota.hubungan.*' => 'required|string',
            'anggota.pekerjaan.*' => 'required|string',

        ]);

        $user_id = Auth::id();
        $masyarakat = Masyarakat::where('id_user', $user_id)->first();

        $surat_pengantar_rt_rw_path = $request->file('surat_pengantar_rt_rw')->store('surat_pengantar_rt_rw', 'public');
        $surat_pernyataan_pribadi_path = $request->file('surat_pernyataan_pribadi')->store('surat_pernyataan_pribadi', 'public');

        // Buat record surat keterangan miskin
        $surat = Suratketeranganmiskin::create([
            'id_masyarakat' => $masyarakat->id_masyarakat,
            'tanggal_pengajuan' => now(),
            'alasan_pembuatan' => $request->alasan_pembuatan,
            'surat_pengantar_rt_rw' => $surat_pengantar_rt_rw_path,
            'surat_pernyataan_pribadi' => $surat_pernyataan_pribadi_path,
            'status' => 'pending',
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'bantuan_untuk' => $request->bantuan_untuk,
            'pendapatan' => $request->pendapatan,
            'pekerjaan' => $request->pekerjaan_pengaju,
        ]);

        // Simpan anggota keluarga
        if ($request->has('anggota')) {
            foreach ($request->anggota['nama'] as $i => $nama) {
                Surat_anggota::create([
                    'id_pelayanan' => $surat->id_pelayanan,
                    'nama' => $nama,
                    'jk' => $request->anggota['jk'][$i],
                    'umur' => $request->anggota['umur'][$i],
                    'hubungan' => $request->anggota['hubungan'][$i],
                    'pekerjaan' => $request->anggota['pekerjaan'][$i],
                ]);
            }
        }

        // Notifikasi pegawai (tetap seperti sebelumnya)
        $countPending = Suratketeranganmiskin::where('status', 'pending')->count();
        $pegawaiUsers = User::where('role', 'pegawai')->get();
        foreach ($pegawaiUsers as $pegawai) {
            $existing = $pegawai->unreadNotifications->first(function($notif) {
                return isset($notif->data['type']) && $notif->data['type'] === 'surat';
            });

            if ($existing) {
                $data = $existing->data;
                $data['message'] = 'Ada ' . $countPending . ' surat keterangan miskin baru yang menunggu pemeriksaan.';
                $data['count'] = $countPending;
                $existing->update(['data' => $data]);
            } else {
                $pegawai->notify(new StatusSuratKeteranganNotification(null, 'pegawai', $countPending, ['type' => 'surat']));
            }
        }

        return redirect()->route('masyarakat.surat_keterangan_miskin')
            ->with('success', 'Surat keterangan miskin berhasil diajukan.');
    }

    // Menampilkan form untuk mengedit surat keterangan miskin

    // Memperbarui surat keterangan miskin
    public function surat_keterangan_miskin_update(Request $request, $id)
    {
        $request->validate([
            'alasan_pembuatan' => 'required',
            'nama_lengkap' => 'required|string',
            'alamat' => 'required|string',
            'pendapatan' => 'required|numeric',
            'pekerjaan_pengaju' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'surat_pengantar_rt_rw' => 'nullable|file|mimes:pdf|max:2048',
            'surat_pernyataan_pribadi' => 'nullable|file|mimes:pdf|max:2048',
            'anggota.nama.*' => 'required|string',
            'anggota.jk.*' => 'required|string',
            'anggota.umur.*' => 'required|numeric',
            'anggota.hubungan.*' => 'required|string',
            'anggota.pekerjaan.*' => 'required|string',
        ]);

        $user_id = Auth::id();
        $masyarakat = Masyarakat::where('id_user', $user_id)->first();
        $suratketeranganmiskin = Suratketeranganmiskin::where('id_pelayanan', $id)
    ->where('id_masyarakat', $masyarakat->id_masyarakat)
    ->firstOrFail();

        $data = [
            'alasan_pembuatan' => $request->alasan_pembuatan,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'pendapatan' => $request->pendapatan,
            'pekerjaan' => $request->pekerjaan_pengaju,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ];

        if ($request->hasFile('surat_pengantar_rt_rw')) {
            // Hapus file lama jika ada
            if ($suratketeranganmiskin->surat_pengantar_rt_rw && Storage::exists('public/' . $suratketeranganmiskin->surat_pengantar_rt_rw)) {
                Storage::delete('public/' . $suratketeranganmiskin->surat_pengantar_rt_rw);
            }
            // Simpan file baru
            $data['surat_pengantar_rt_rw'] = $request->file('surat_pengantar_rt_rw')->store('surat_pengantar_rt_rw', 'public');
        }

        if ($request->hasFile('surat_pernyataan_pribadi')) {
            // Hapus file lama jika ada
            if ($suratketeranganmiskin->surat_pernyataan_pribadi && Storage::exists('public/' . $suratketeranganmiskin->surat_pernyataan_pribadi)) {
                Storage::delete('public/' . $suratketeranganmiskin->surat_pernyataan_pribadi);
            }
            // Simpan file baru
            $data['surat_pernyataan_pribadi'] = $request->file('surat_pernyataan_pribadi')->store('surat_pernyataan_pribadi', 'public');
        }

        if ($request->hasFile('surat_pengantar_rt_rw') && $suratketeranganmiskin->validasi_pengantar === 'ditolak') {
            $data['validasi_pengantar'] = 'pending';
        }
        if ($request->hasFile('surat_pernyataan_pribadi') && $suratketeranganmiskin->validasi_pernyataan === 'ditolak') {
            $data['validasi_pernyataan'] = 'pending';
        }

        $suratketeranganmiskin->update($data);

        // Refresh data dari database agar field validasi sudah ter-update
        $suratketeranganmiskin->refresh();

        if (
            ($request->hasFile('surat_pengantar_rt_rw') && $suratketeranganmiskin->validasi_pengantar === 'pending') ||
            ($request->hasFile('surat_pernyataan_pribadi') && $suratketeranganmiskin->validasi_pernyataan === 'pending')
        ) {
            $suratketeranganmiskin->update(['status' => 'pending']);
        }

        if ($suratketeranganmiskin->validasi_pengantar === 'ditolak' && $suratketeranganmiskin->validasi_pernyataan === 'ditolak') {
        $suratketeranganmiskin->update(['validasi_pegawai' => 'diproses']);
        $suratketeranganmiskin->update(['validasi_nagari' => 'diproses']);
        $suratketeranganmiskin->update(['status' => 'pending']);


        }

        // Update anggota keluarga: hapus semua lalu insert ulang
        Surat_anggota::where('id_pelayanan', $suratketeranganmiskin->id_pelayanan)->delete();
        if ($request->has('anggota')) {
            foreach ($request->anggota['nama'] as $i => $nama) {
                Surat_anggota::create([
                    'id_pelayanan' => $suratketeranganmiskin->id_pelayanan,
                    'nama' => $nama,
                    'jk' => $request->anggota['jk'][$i],
                    'umur' => $request->anggota['umur'][$i],
                    'hubungan' => $request->anggota['hubungan'][$i],
                    'pekerjaan' => $request->anggota['pekerjaan'][$i],
                ]);
            }
        }

        return redirect()->route('masyarakat.surat_keterangan_miskin')->with('success', 'Surat keterangan miskin berhasil diperbarui.');
    }

    // Menghapus surat keterangan miskin
    public function surat_keterangan_miskin_destroy($id)
    {
        $user_id = Auth::id();
        $masyarakat = Masyarakat::where('id_user', $user_id)->first();
        $suratketeranganmiskin = Suratketeranganmiskin::where('id_masyarakat', $masyarakat->id_masyarakat)->findOrFail($id);

        // Delete files
        if ($suratketeranganmiskin->surat_pengantar_rt_rw && Storage::exists('public/' . $suratketeranganmiskin->surat_pengantar_rt_rw)) {
            Storage::delete('public/' . $suratketeranganmiskin->surat_pengantar_rt_rw);
        }

        if ($suratketeranganmiskin->surat_pernyataan_pribadi && Storage::exists('public/' . $suratketeranganmiskin->surat_pernyataan_pribadi)) {
            Storage::delete('public/' . $suratketeranganmiskin->surat_pernyataan_pribadi);
        }

        $suratketeranganmiskin->delete();

        return redirect()->route('masyarakat.surat_keterangan_miskin')->with('success', 'Surat keterangan miskin berhasil dihapus.');
    }
}
