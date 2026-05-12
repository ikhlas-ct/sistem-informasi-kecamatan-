<?php

namespace App\Http\Controllers\Masyarakat;

use App\Models\User;
use App\Models\Nagari;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilmasyarakatController extends Controller
{
    public function profil()
    {
        $user = Auth::user();
        $profil = $user->masyarakat;
        $nagari = Nagari::all();

        return view('pages.masyarakat.profil', compact('profil', 'nagari'));
    }

    public function profil_update(Request $request)
    {
        $user = Auth::user();
        $profil = Masyarakat::where('id_user', $user->id)->firstOrFail();


        $request->validate([
            'nik' => 'required|unique:masyarakat,nik,' . $profil->id_masyarakat . ',id_masyarakat',
            'kk' => 'required|string|max:16',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'no_hp' => 'required|string|max:15',
            'nama_masyarakat' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'scan_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'scan_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_diri_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'akta_kelahiran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_diri_akta' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'instagram'         => 'nullable|string|max:255',
            'twitter'           => 'nullable|string|max:255',
            'facebook'          => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string|max:2500',
            'pekerjaan'         => 'nullable|string|max:255',
            'id_nagari' => 'nullable',

        ]);

        $data = $request->only([
            'nik', 'kk', 'jenis_kelamin', 'no_hp',
            'nama_masyarakat', 'nama_ibu', 'alamat', 'instagram', 'twitter', 'facebook', 'deskripsi', 'pekerjaan', 'id_nagari'
        ]);

        // Handle file uploads
        $fileFields = [
            'scan_ktp', 'scan_kk', 'foto_diri_ktp',
            'akta_kelahiran', 'foto_diri_akta', 'foto_profil',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($profil->$field) {
                    Storage::disk('public')->delete($profil->$field);
                }
                // Store new file
                $data[$field] = $request->file($field)->store($field, 'public');
            }
        }

        // Update profil masyarakat
        $profil->update($data);

        // Update NIK di tabel users jika berubah
        if ($request->nik !== $user->nip_nik) {
            $user->update(['nip_nik' => $request->nik]);
        }

        return redirect()->route('masyarakat.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }



    public function password_update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('masyarakat.profil')
            ->with('success', 'Password berhasil diperbarui!');
    }
}
