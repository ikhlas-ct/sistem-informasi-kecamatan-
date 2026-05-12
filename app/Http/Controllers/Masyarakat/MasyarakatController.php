<?php

namespace App\Http\Controllers\Masyarakat;


use App\Models\Nagari;
use App\Models\User;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Kecamatansetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MasyarakatController extends Controller
{
    public function dashboard()
    {



        $user = Auth::user();
        $masyarakat = $user->masyarakat;


        return view('pages.masyarakat.dashboard',compact('user','masyarakat'));
    }

    // Menampilkan form pendaftaran
    public function create()
    {
        $nagari = Nagari::all();
        return view('pages.login.pendaftaran', compact('nagari'));
    }

    // Menyimpan data masyarakat baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|string|size:16|unique:masyarakat,nik',
            'kk' => 'required|string|size:16',
            'nama_masyarakat' => 'required|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'id_nagari' => 'required|exists:nagari,id',
            'instagram' => 'nullable|string|max:100',
            'twitter' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:100',
            // file upload
            'scan_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_diri_ktp' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'scan_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_diri_akta' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'foto_profil' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        foreach (['scan_ktp', 'foto_diri_ktp', 'scan_kk', 'akta_kelahiran', 'foto_diri_akta', 'foto_profil'] as $field) {
            if ($request->hasFile($field)) {
                $validatedData[$field] = $request->file($field)->store('masyarakat/'.$field, 'public');
            }
        }

        Masyarakat::create($validatedData);

        return redirect()->route('masyarakat.index')
            ->with('success', 'Data masyarakat berhasil ditambahkan!');
    }



    // Menampilkan daftar masyarakat (untuk admin)
    public function index()
    {
        $masyarakat = Masyarakat::with('user')->get();
        return view('masyarakat.index', compact('masyarakat'));
    }

    // Menampilkan detail masyarakat
    public function show($id)
    {
        $masyarakat = Masyarakat::with('user')->findOrFail($id);
        return view('masyarakat.show', compact('masyarakat'));
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $masyarakat = Masyarakat::with('user')->findOrFail($id);
        return view('masyarakat.edit', compact('masyarakat'));
    }

    // Mengupdate data masyarakat
    public function update(Request $request, $id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        $validated = $request->validate([
            'nama_masyarakat' => 'required|string|max:100',
            'kk' => 'required|string|size:16',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required|string|max:20',
            'nama_ibu' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'scan_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scan_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_diri_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_diri_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pekerjaan' => 'nullable|string|max:255',

        ]);

        try {
            // Handle file uploads
            $fileFields = [
                'scan_ktp', 'scan_kk', 'foto_diri_ktp',
                'foto_diri_kk', 'akta_kelahiran', 'foto_profil',

            ];

            $filePaths = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($masyarakat->$field) {
                        Storage::delete('public/' . $masyarakat->$field);
                    }

                    // Simpan file baru
                    $path = $request->file($field)->store('public/masyarakat');
                    $filePaths[$field] = str_replace('public/', '', $path);
                }
            }

            // Update data masyarakat
            $masyarakat->update([
                'kk' => $validated['kk'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_hp' => $validated['no_hp'],
                'nama_masyarakat' => $validated['nama_masyarakat'],
                'nama_ibu' => $validated['nama_ibu'] ?? null,
                'alamat' => $validated['alamat'],
                'scan_ktp' => $filePaths['scan_ktp'] ?? $masyarakat->scan_ktp,
                'scan_kk' => $filePaths['scan_kk'] ?? $masyarakat->scan_kk,
                'foto_diri_ktp' => $filePaths['foto_diri_ktp'] ?? $masyarakat->foto_diri_ktp,
                'foto_diri_kk' => $filePaths['foto_diri_kk'] ?? $masyarakat->foto_diri_kk,
                'akta_kelahiran' => $filePaths['akta_kelahiran'] ?? $masyarakat->akta_kelahiran,
                'foto_profil' => $filePaths['foto_profil'] ?? $masyarakat->foto_profil,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]);

            return redirect()->route('masyarakat.show', $masyarakat->id_masyarakat)
                ->with('success', 'Data masyarakat berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    // Menghapus data masyarakat
    public function destroy($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        try {
            // Mulai transaksi database
            \DB::beginTransaction();

            // Hapus file-file yang terkait
            $fileFields = [
                'scan_ktp', 'scan_kk', 'foto_diri_ktp',
                'foto_diri_kk', 'akta_kelahiran', 'foto_profil'
            ];

            foreach ($fileFields as $field) {
                if ($masyarakat->$field) {
                    Storage::delete('public/' . $masyarakat->$field);
                }
            }

            // Hapus user terkait
            User::where('id', $masyarakat->id_user)->delete();

            // Hapus data masyarakat
            $masyarakat->delete();

            // Commit transaksi
            \DB::commit();

            return redirect()->route('masyarakat.index')
                ->with('success', 'Data masyarakat berhasil dihapus.');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            \DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }












}
