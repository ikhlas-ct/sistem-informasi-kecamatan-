<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilpegawaiController extends Controller
{
    public function profil()
    {
        $user = Auth::user();
        $profil = $user->pegawai;

        return view('pages.pegawai.profil', compact('profil'));
    }

    public function profil_update(Request $request)
    {
        $user = Auth::user();
        $profil = Pegawai::where('id_user', $user->id)->firstOrFail();

        // Validasi input
        $request->validate([
            'nama_pegawai'      => 'required|string|max:255',
            'nik'               => 'required|string|max:16|unique:pegawai,nik,' . $profil->id_pegawai . ',id_pegawai',
            'nip'               => 'nullable|string|max:20|unique:pegawai,nip,' . $profil->id_pegawai . ',id_pegawai',
            'pangkat_golongan'  => 'nullable|string|max:255',
            'jabatan'           => 'nullable|string|max:255',
            'jenis_kelamin'     => 'required|string|in:Laki-laki,Perempuan',
            'alamat_pegawai'    => 'nullable|string|max:255',
            'nohp_pegawai'      => 'nullable|string|max:15',
            'email_pegawai'     => 'nullable|email|max:255',
            'deskripsi'         => 'nullable|string|max:500',
            'instagram'         => 'nullable|string|max:255',
            'twitter'           => 'nullable|string|max:255',
            'facebook'          => 'nullable|string|max:255',
            'foto_profil'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data yang akan diupdate
        $data = $request->only([
            'nama_pegawai', 'nik', 'nip', 'pangkat_golongan', 'jabatan', 'jenis_kelamin',
            'alamat_pegawai', 'nohp_pegawai', 'email_pegawai', 'deskripsi',
            'instagram', 'twitter', 'facebook'
        ]);

        // Upload foto profil baru jika ada
        if ($request->hasFile('foto_profil')) {
            if ($profil->foto_profil) {
                Storage::disk('public')->delete($profil->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profil', 'public');
        }

        // Update data profil pegawai
        $profil->update($data);

        // Update NIP di tabel users jika berubah
        if ($request->filled('nip') && $request->nip !== $user->nip_nik) {
            $user->update(['nip_nik' => $request->nip]);
        }

        return redirect()->route('pegawai.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function password_update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            // Cek apakah current_password sesuai dengan password yang tersimpan
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai');
            }

            // Validasi: password baru tidak boleh sama dengan password lama
            if (Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Password baru tidak boleh sama dengan password lama');
            }

            // Validasi: pastikan password konfirmasi sama dengan password baru
            if ($request->password !== $request->password_confirmation) {
                return back()->with('error', 'Password konfirmasi tidak cocok dengan password baru');
            }

            // Jika semua validasi berhasil, update password
            $user->update(['password' => Hash::make($request->password)]);
        }


        // Update password
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('pegawai.profil')->with('success', 'Password berhasil diperbarui!');
    }
}
