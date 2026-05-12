<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\User;
use App\Models\Nagari;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PengaturanmasyarakatController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $query = Masyarakat::select('masyarakat.*')
            ->join('users', 'users.id', '=', 'masyarakat.id_user')
            ->with('user')
            ->where('users.role', 'masyarakat')
            // Urutkan: 'aktif' dulu, lalu 'nonaktif'
            ->orderByRaw("FIELD(users.status, 'aktif', 'nonaktif')")
            // Selanjutnya urut alfabet nama masyarakat
            ->orderBy('masyarakat.nama_masyarakat')
            // Jika ada kata kunci, filter fulltext
            ->when($keyword, fn($q) =>
                $q->whereRaw(
                    "MATCH(masyarakat.nama_masyarakat, masyarakat.nik) AGAINST(? IN BOOLEAN MODE)",
                    ["{$keyword}*"]
                )
            );

        $masyarakats = $query
            ->paginate(10)
            ->appends(['search' => $keyword]);

            $nagari= Nagari::all();

        return view('pages.pegawai.masyarakat.index', compact('masyarakats', 'keyword', 'nagari'));
    }


    // Menyimpan data masyarakat baru dan otomatis membuat data user
    public function store(Request $request)
    {
        // Validasi input masyarakat (tanpa id_user karena akan dibuat otomatis)
        $validatedData = $request->validate([
            'role'                       => 'required|in:masyarakat,pegawai',
            'nama_masyarakat'               => 'required|string|max:255',
            'nik'                        => 'required|string|size:16|unique:masyarakat,nik',
            'jabatan'                    => 'required|string|max:255',
            'password'                   => 'required|string|max:255',
        ]);


        $user = User::create([
            'nip_nik'  => $validatedData['nik'],
            'password' => Hash::make($validatedData['password']),
            'role'     => $validatedData['role'],

        ]);

        $validatedData['id_user'] = $user->id;

        // Simpan data masyarakat
        Masyarakat::create($validatedData);

        return redirect()->route('masyarakat.index')
                         ->with('success', 'masyarakat dan user berhasil dibuat');
    }

    // Menampilkan detail masyarakat berdasarkan id_masyarakat
    public function show($id_masyarakat)
    {
        $masyarakat = Masyarakat::with([
            'konten',                  
            'pengaduan.balasanpengaduan',
            'pelayananadministrasi',
        ])->findOrFail($id_masyarakat);
      return view('pages.pegawai.masyarakat.show', compact('masyarakat'));
    }

    public function updatePassword(Request $request, Masyarakat $masyarakat)
{
  $request->validate([
      'password' => 'required|string|min:8|confirmed',
  ]);

  // Asumsikan relasi User ada sebagai $masyarakat->user
  $user = $masyarakat->user;
  $user->password = Hash::make($request->password);
  $user->save();

  return back()->with('success', 'Password berhasil diubah!');
}

public function toggleStatus(Request $request, masyarakat $masyarakat)
{
;

  $newStatus = $request->input('status');


  $masyarakat->user->update(['status' => $newStatus]);

  return back()->with('success', "Status berhasil diubah menjadi “{$newStatus}”.");
}
}
