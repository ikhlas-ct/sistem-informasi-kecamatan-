<?php

namespace App\Http\Controllers;

use App\Models\Kecamatansetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KecamatanSettingController extends Controller
{
    // ────────────────────────────────────────────────
    // Tampilkan form pengaturan (edit / create)
    // ────────────────────────────────────────────────
    public function edit()
    {
        $setting = Kecamatansetting::first() ?? new Kecamatansetting();
        return view('pages.setting.setting_kecamatan', compact('setting'));
    }

    // ────────────────────────────────────────────────
    // Simpan semua pengaturan (satu form, satu action)
    // ────────────────────────────────────────────────
    public function update(Request $request)
    {
        $request->validate([
            // Identitas
            'nama_kecamatan'          => 'nullable|string|max:255',
            'kode_kecamatan'          => 'nullable|string|max:50',
            'kode_pos_kecamatan'      => 'nullable|string|max:10',
            'nama_kabupaten'          => 'nullable|string|max:255',
            'kode_kabupaten'          => 'nullable|string|max:50',
            'provinsi'                => 'nullable|string|max:255',
            'kode_provinsi'           => 'nullable|string|max:50',
            'logo'                    => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            // Kontak & Sosial
            'alamat_kecamatan'        => 'nullable|string',
            'email_kecamatan'         => 'nullable|email|max:255',
            'nomor_telepon_kecamatan' => 'nullable|string|max:20',
            'social_facebook'         => 'nullable|url|max:255',
            'social_instagram'        => 'nullable|url|max:255',
            'social_twitter'          => 'nullable|url|max:255',
            // Pengantar
            'title_pengantar'         => 'nullable|string|max:255',
            'paragraf_pengantar'      => 'nullable|string',
            'gambar_pengantar'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
            // Profil teks (rich-text disimpan di DB langsung)
            'visi_misi'               => 'nullable|string',
            'sejarah'                 => 'nullable|string',
            'geografis'               => 'nullable|string',
            'tugas_pokok'             => 'nullable|string',
            'fungsi'                  => 'nullable|string',
            'uraian_tugas'            => 'nullable|string',
            // Struktur
            'gambar_struktur'         => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        $setting = Kecamatansetting::firstOrNew([]);

        // ── Isian teks ─────────────────────────────────
        $textFields = [
            'nama_kecamatan', 'kode_kecamatan', 'kode_pos_kecamatan',
            'nama_kabupaten', 'kode_kabupaten', 'provinsi', 'kode_provinsi',
            'alamat_kecamatan', 'email_kecamatan', 'nomor_telepon_kecamatan',
            'social_facebook', 'social_instagram', 'social_twitter',
            'title_pengantar', 'paragraf_pengantar',
            'visi_misi', 'sejarah', 'geografis',
            'tugas_pokok', 'fungsi', 'uraian_tugas',
        ];
        foreach ($textFields as $field) {
            $setting->$field = $request->input($field);
        }

        // ── Upload logo ─────────────────────────────────
        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $setting->logo = $request->file('logo')
                ->store('kecamatan/logo', 'public');
        }

        // ── Upload gambar pengantar ─────────────────────
        if ($request->hasFile('gambar_pengantar')) {
            if ($setting->gambar_pengantar) Storage::disk('public')->delete($setting->gambar_pengantar);
            $setting->gambar_pengantar = $request->file('gambar_pengantar')
                ->store('kecamatan/pengantar', 'public');
        }

        // ── Upload gambar struktur ──────────────────────
        if ($request->hasFile('gambar_struktur')) {
            if ($setting->gambar_struktur) Storage::disk('public')->delete($setting->gambar_struktur);
            $setting->gambar_struktur = $request->file('gambar_struktur')
                ->store('kecamatan/struktur', 'public');
        }

        $setting->save();

        return redirect()->route('kecamatan.setting.edit')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    // ────────────────────────────────────────────────
    // Upload gambar dari Summernote (inline images)
    // ────────────────────────────────────────────────
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:4096',
        ]);

        $path = $request->file('image')->store('kecamatan/content', 'public');
        $url  = asset('storage/' . $path);

        return response()->json(['url' => $url]);
    }

    // ────────────────────────────────────────────────
    // Hapus gambar inline Summernote dari storage
    // ────────────────────────────────────────────────
    public function deleteImage(Request $request)
    {
        $src = $request->input('src');

        // Ambil path relatif dari URL
        $storagePath = ltrim(
            str_replace(asset('storage'), '', $src),
            '/'
        );

        if ($storagePath && Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }

        return response()->json(['deleted' => true]);
    }
}
