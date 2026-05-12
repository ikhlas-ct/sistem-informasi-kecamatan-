<?php

namespace App\Http\Controllers\Camat;

use App\Models\Pegawai;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Kecamatansetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function dashboard()
    {
        return view('pages.camat.profil.setting', compact('settings'));
    }
    public function edit()
    {
        $settings = Kecamatansetting::first(); // Ambil data pertama
        return view('pages.camat.profil.setting', compact('settings'));
    }
    public function update(Request $request)
{
    $validated = $request->validate([
        'nama_kecamatan'         => 'nullable|string|max:255',
        'kode_kecamatan'         => 'nullable|string|max:50',
        'kode_pos_kecamatan'     => 'nullable|string|max:10',
        'id_pegawai'             => 'nullable|exists:pegawai,id_pegawai',
        'alamat_kecamatan'       => 'nullable|string',
        'email_kecamatan'        => 'nullable|email|max:255',
        'nomor_telepon_kecamatan'=> 'nullable|string|max:20',
        'nama_kabupaten'         => 'nullable|string|max:255',
        'kode_kabupaten'         => 'nullable|string|max:50',
        'provinsi'               => 'nullable|string|max:255',
        'kode_provinsi'          => 'nullable|string|max:50',
        'logo'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'social_facebook'        => 'nullable|url|max:255',
        'social_instagram'       => 'nullable|url|max:255',
        'social_twitter'         => 'nullable|url|max:255',
    ]);

    // Ambil data pengaturan atau buat baru jika belum ada
    $setting = Kecamatansetting::firstOrNew([]);

    $camat = Pegawai::whereHas('user', function ($query) {
        $query->where('role', 'camat')->where('status', 'aktif');
    })->first();

    if ($camat) {
        $setting->id_pegawai = $camat->id_pegawai;
    }



    if ($request->hasFile('logo')) {
        // Hapus logo lama jika ada
        if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
            Storage::delete('public/' . $setting->logo);
        }

        // Simpan logo baru di folder 'logos' dalam disk 'public'
        $file = $request->file('logo');
        $path = $file->store('logos', 'public'); // Contoh: menghasilkan 'logos/filename.jpg'

        $setting->logo = $path; // Simpan path ke database
    }

    // Hapus key 'logo' dari validated agar tidak menimpa field logo
    unset($validated['logo']);

    // Update field lain menggunakan data yang divalidasi
    $setting->fill($validated);
    $setting->save();

    return redirect()->route('camat.settings.edit')->with('success', 'Settings updated successfully!');
}




public function pengantar()
{
    return view('pages.camat.profil.kata_pengantar' );
}
public function pengatar_update(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'title_pengantar'    => 'nullable|string|max:255',
        'paragraf_pengantar' => 'nullable|string',
        'gambar_pengantar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Ambil data settings (asumsikan hanya ada satu record)
    $settings = Kecamatansetting::firstOrNew([]);

    // Proses upload gambar jika ada
    if ($request->hasFile('gambar_pengantar')) {
        // Hapus gambar lama jika ada
        if ($settings->gambar_pengantar && Storage::disk('public')->exists($settings->gambar_pengantar)) {
            Storage::disk('public')->delete($settings->gambar_pengantar);
        }
        $path = $request->file('gambar_pengantar')->store('settings', 'public');
        $validated['gambar_pengantar'] = $path;
    }

    // Update data settings
    $settings->update($validated);

    return redirect()->back()->with('success', 'Pengaturan pengantar berhasil diperbarui.');
}


 }



