<?php

namespace App\Http\Controllers;

use App\Models\Nagari;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /* ─────────────────────────────────────────
     |  HELPERS
     ──────────────────────────────────────────*/

    /** Kembalikan getRoleLabel() dari user yang sedang login */
    private function rl(): string
    {
        return auth()->user()->getRoleLabel();
    }

    /** Kembalikan record Pegawai dari user yang sedang login */
    private function ap()
    {
        return auth()->user()->pegawai;
    }

    /**
     * Cek apakah user yang login boleh mengelola $pegawai tertentu.
     * Camat         → semua
     * Staf Camat    → hanya yang punya id_nagari (level nagari)
     * Kepala Nagari → hanya staf_nagari di nagari sendiri
     */
    private function canManage(Pegawai $pegawai): bool
    {
        $rl = $this->rl();
        $ap = $this->ap();

        if ($rl === 'camat') return true;

        if ($rl === 'staf_camat') {
            // Tidak boleh ke sesama staf camat atau camat (id_nagari null)
            return !is_null($pegawai->id_nagari);
        }

        if ($rl === 'wali_nagari') {
            // Hanya staf_nagari di nagari sendiri
            return $pegawai->id_nagari == $ap->id_nagari
                && $pegawai->jabatan_nagari === 'staf_nagari';
        }

        return false;
    }

    /**
     * Daftar tipe pegawai yang boleh dibuat oleh user yang login.
     * Selalu periksa ulang setiap request (jangan cache).
     */
    private function allowedTipes(): array
    {
        $rl = $this->rl();
        $tipes = [];

        $camatExists = User::where('role', 'camat')->exists();

        if ($rl === 'camat') {
            if (!$camatExists) $tipes[] = 'camat';
            $tipes[] = 'staf_camat';
            $tipes[] = 'kepala_nagari';
            $tipes[] = 'staf_nagari';
        } elseif ($rl === 'staf_camat') {
            $tipes[] = 'kepala_nagari';
            $tipes[] = 'staf_nagari';
        } elseif ($rl === 'wali_nagari') {
            $tipes[] = 'staf_nagari';
        }

        return $tipes;
    }

    /* ─────────────────────────────────────────
     |  INDEX
     ──────────────────────────────────────────*/

    public function index(Request $request)
    {
        $rl = $this->rl();
        $ap = $this->ap();

        // Staf nagari tidak punya akses manajemen pegawai
        if (in_array($rl, ['staf_nagari', 'masyarakat'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Pegawai::with(['user', 'nagari']);

        // Batasi scope data berdasarkan peran
        if ($rl === 'staf_camat') {
            $query->whereNotNull('id_nagari');
        } elseif ($rl === 'wali_nagari') {
            $query->where('id_nagari', $ap->id_nagari)
                  ->where('jabatan_nagari', 'staf_nagari');
        }
        // Camat melihat semua

        // Filter pencarian
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_pegawai', 'like', "%$q%")
                    ->orWhere('nip', 'like', "%$q%")
                    ->orWhere('nik', 'like', "%$q%");
            });
        }

        // Filter tipe
        if ($request->filled('tipe')) {
            switch ($request->tipe) {
                case 'camat':
                    $query->whereHas('user', fn($u) => $u->where('role', 'camat'));
                    break;
                case 'staf_camat':
                    $query->whereNull('id_nagari')
                          ->whereHas('user', fn($u) => $u->where('role', 'pegawai'));
                    break;
                case 'kepala_nagari':
                    $query->where('jabatan_nagari', 'kepala_nagari');
                    break;
                case 'staf_nagari':
                    $query->where('jabatan_nagari', 'staf_nagari');
                    break;
            }
        }

        // Filter nagari
        if ($request->filled('id_nagari')) {
            $query->where('id_nagari', $request->id_nagari);
        }

        $pegawais = $query->latest()->paginate(10)->withQueryString();

        // Statistik (scope sesuai peran)
        $statBase = Pegawai::query();
        if ($rl === 'wali_nagari') {
            $statBase->where('id_nagari', $ap->id_nagari);
        }

        $totalCamat       = (clone $statBase)->whereHas('user', fn($u) => $u->where('role', 'camat'))->count();
        $totalStafCamat   = (clone $statBase)->whereNull('id_nagari')->whereHas('user', fn($u) => $u->where('role', 'pegawai'))->count();
        $totalKepala      = (clone $statBase)->where('jabatan_nagari', 'kepala_nagari')->count();
        $totalStafNagari  = (clone $statBase)->where('jabatan_nagari', 'staf_nagari')->count();

        $nagaris          = Nagari::orderBy('nama_nagari')->get();
        $allowedTipes     = $this->allowedTipes();

        return view('pages.camat.pegawai.index', compact(
            'pegawais', 'nagaris', 'rl', 'ap',
            'totalCamat', 'totalStafCamat', 'totalKepala', 'totalStafNagari',
            'allowedTipes'
        ));
    }

    /* ─────────────────────────────────────────
     |  CREATE
     ──────────────────────────────────────────*/

    public function create()
    {
        $rl = $this->rl();
        $ap = $this->ap();

        if (in_array($rl, ['staf_nagari', 'masyarakat'])) abort(403);

        $allowedTipes   = $this->allowedTipes();
        if (empty($allowedTipes)) abort(403, 'Anda tidak memiliki izin untuk membuat pegawai.');

        $nagaris        = Nagari::orderBy('nama_nagari')->get();
        $camatExists    = User::where('role', 'camat')->exists();

        // Nagari yang sudah punya kepala nagari (untuk disable di select)
        $nagariWithKepala = Pegawai::where('jabatan_nagari', 'kepala_nagari')
                                   ->pluck('id_nagari')
                                   ->toArray();

        // Jika wali_nagari, batasi nagari ke milik sendiri
        $lockedNagari = ($rl === 'wali_nagari') ? $ap->id_nagari : null;

        return view('pages.camat.pegawai.create', compact(
            'nagaris', 'camatExists', 'nagariWithKepala',
            'allowedTipes', 'rl', 'ap', 'lockedNagari'
        ));
    }

    /* ─────────────────────────────────────────
     |  STORE
     ──────────────────────────────────────────*/

    public function store(Request $request)
    {
        $rl = $this->rl();
        $ap = $this->ap();

        if (in_array($rl, ['staf_nagari', 'masyarakat'])) abort(403);

        $allowedTipes = $this->allowedTipes();
        $tipe = $request->tipe;

        if (!in_array($tipe, $allowedTipes)) {
            abort(403, 'Tipe pegawai tidak diizinkan untuk peran Anda.');
        }

        // ── Validasi bisnis ──────────────────
        if ($tipe === 'camat' && User::where('role', 'camat')->exists()) {
            return back()
                ->withErrors(['tipe' => 'Camat sudah ada. Sistem hanya mengizinkan 1 Camat.'])
                ->withInput();
        }

        if ($tipe === 'kepala_nagari') {
            $nagariId = $request->id_nagari;
            if (Pegawai::where('jabatan_nagari', 'kepala_nagari')
                       ->where('id_nagari', $nagariId)->exists()) {
                return back()
                    ->withErrors(['id_nagari' => 'Nagari ini sudah memiliki Kepala Nagari.'])
                    ->withInput();
            }
        }

        // Wali nagari hanya boleh kelola nagari sendiri
        if ($rl === 'wali_nagari' && $request->id_nagari != $ap->id_nagari) {
            abort(403, 'Anda hanya dapat mengelola nagari Anda sendiri.');
        }

        // ── Validasi input ───────────────────
        $request->validate([
            'tipe'             => 'required|in:camat,staf_camat,kepala_nagari,staf_nagari',
            'nama_pegawai'     => 'required|string|max:255',
            'nip'              => 'required|string|max:20|unique:pegawai,nip',
            'nik'              => 'required|string|size:16|unique:pegawai,nik',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan'          => 'required|string|max:255',
            'alamat_pegawai'   => 'nullable|string|max:255',
            'nohp_pegawai'     => 'nullable|string|max:20',
            'email_pegawai'    => 'nullable|email|max:255',
            'deskripsi'        => 'nullable|string',
            'instagram'        => 'nullable|url|max:255',
            'twitter'          => 'nullable|url|max:255',
            'facebook'         => 'nullable|url|max:255',
            'foto_profil'      => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'id_nagari'        => 'nullable|exists:nagari,id',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'nip.unique'  => 'NIP sudah terdaftar.',
            'nik.unique'  => 'NIK sudah terdaftar.',
            'nik.size'    => 'NIK harus 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request, $tipe) {
            $userRole = $tipe === 'camat' ? 'camat' : 'pegawai';

            $user = User::create([
                'nip_nik'  => $request->nip,
                'password' => Hash::make($request->password),
                'role'     => $userRole,
                'status'   => 'aktif',
            ]);

            [$idNagari, $jabatanNagari] = match ($tipe) {
                'kepala_nagari' => [$request->id_nagari, 'kepala_nagari'],
                'staf_nagari'   => [$request->id_nagari, 'staf_nagari'],
                default         => [null, null],
            };

            $fotoPath = 'pegawai/default.jpg';
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('pegawai', 'public');
            }

            Pegawai::create([
                'id_user'          => $user->id,
                'role'             => $tipe === 'camat' ? 'camat' : 'pegawai',
                'nama_pegawai'     => $request->nama_pegawai,
                'nip'              => $request->nip,
                'nik'              => $request->nik,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan'          => $request->jabatan,
                'alamat_pegawai'   => $request->alamat_pegawai,
                'nohp_pegawai'     => $request->nohp_pegawai,
                'email_pegawai'    => $request->email_pegawai,
                'deskripsi'        => $request->deskripsi,
                'instagram'        => $request->instagram,
                'twitter'          => $request->twitter,
                'facebook'         => $request->facebook,
                'foto_profil'      => $fotoPath,
                'id_nagari'        => $idNagari,
                'jabatan_nagari'   => $jabatanNagari,
            ]);
        });

        return redirect()->route('pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /* ─────────────────────────────────────────
     |  EDIT
     ──────────────────────────────────────────*/

    public function edit($id)
    {
        $pegawai = Pegawai::with(['user', 'nagari'])->findOrFail($id);

        if (!$this->canManage($pegawai)) abort(403, 'Anda tidak memiliki izin untuk mengedit pegawai ini.');

        $rl   = $this->rl();
        $ap   = $this->ap();

        $nagaris = Nagari::orderBy('nama_nagari')->get();

        // Nagari yang sudah punya kepala nagari selain pegawai ini
        $nagariWithKepala = Pegawai::where('jabatan_nagari', 'kepala_nagari')
                                   ->where('id_pegawai', '!=', $id)
                                   ->pluck('id_nagari')
                                   ->toArray();

        $lockedNagari = ($rl === 'wali_nagari') ? $ap->id_nagari : null;

        return view('pages.camat.pegawai.edit', compact(
            'pegawai', 'nagaris', 'nagariWithKepala', 'rl', 'ap', 'lockedNagari'
        ));
    }

    /* ─────────────────────────────────────────
     |  UPDATE
     ──────────────────────────────────────────*/

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);

        if (!$this->canManage($pegawai)) abort(403, 'Anda tidak memiliki izin untuk mengubah pegawai ini.');

        $rl = $this->rl();
        $ap = $this->ap();

        // Wali nagari tidak boleh edit di luar nagari sendiri
        if ($rl === 'wali_nagari' && $pegawai->id_nagari != $ap->id_nagari) {
            abort(403);
        }

        // Cek kepala nagari unik (jika tipe diubah ke kepala_nagari)
        if ($request->tipe === 'kepala_nagari') {
            $nagariId = $request->id_nagari ?? $pegawai->id_nagari;
            if (Pegawai::where('jabatan_nagari', 'kepala_nagari')
                       ->where('id_nagari', $nagariId)
                       ->where('id_pegawai', '!=', $id)
                       ->exists()) {
                return back()
                    ->withErrors(['id_nagari' => 'Nagari ini sudah memiliki Kepala Nagari.'])
                    ->withInput();
            }
        }

        $request->validate([
            'nama_pegawai'     => 'required|string|max:255',
            'nip'              => 'required|string|max:20|unique:pegawai,nip,' . $id . ',id_pegawai',
            'nik'              => 'required|string|size:16|unique:pegawai,nik,' . $id . ',id_pegawai',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan'          => 'required|string|max:255',
            'alamat_pegawai'   => 'nullable|string|max:255',
            'nohp_pegawai'     => 'nullable|string|max:20',
            'email_pegawai'    => 'nullable|email|max:255',
            'deskripsi'        => 'nullable|string',
            'instagram'        => 'nullable|url|max:255',
            'twitter'          => 'nullable|url|max:255',
            'facebook'         => 'nullable|url|max:255',
            'foto_profil'      => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'id_nagari'        => 'nullable|exists:nagari,id',
            'password'         => 'nullable|string|min:8|confirmed',
            'status'           => 'required|in:aktif,nonaktif',
            'tipe'             => 'nullable|in:camat,staf_camat,kepala_nagari,staf_nagari',
        ], [
            'nip.unique' => 'NIP sudah terdaftar.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.size'   => 'NIK harus 16 digit.',
        ]);

        DB::transaction(function () use ($request, $pegawai, $rl) {
            // Update akun user
            $userData = ['status' => $request->status];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $pegawai->user->update($userData);

            // Foto profil
            $fotoPath = $pegawai->foto_profil;
            if ($request->hasFile('foto_profil')) {
                if ($pegawai->foto_profil && $pegawai->foto_profil !== 'pegawai/default.jpg') {
                    Storage::disk('public')->delete($pegawai->foto_profil);
                }
                $fotoPath = $request->file('foto_profil')->store('pegawai', 'public');
            }

            // Tipe (hanya camat & staf_camat boleh ubah tipe nagari)
            $idNagari      = $pegawai->id_nagari;
            $jabatanNagari = $pegawai->jabatan_nagari;

            if ($rl === 'camat' && $request->filled('tipe')) {
                $newTipe = $request->tipe;
                [$idNagari, $jabatanNagari] = match ($newTipe) {
                    'kepala_nagari' => [$request->id_nagari, 'kepala_nagari'],
                    'staf_nagari'   => [$request->id_nagari, 'staf_nagari'],
                    'staf_camat'    => [null, null],
                    default         => [$pegawai->id_nagari, $pegawai->jabatan_nagari],
                };
            } elseif ($rl === 'staf_camat' && $request->filled('tipe')) {
                $newTipe = $request->tipe;
                if (in_array($newTipe, ['kepala_nagari', 'staf_nagari'])) {
                    [$idNagari, $jabatanNagari] = match ($newTipe) {
                        'kepala_nagari' => [$request->id_nagari, 'kepala_nagari'],
                        'staf_nagari'   => [$request->id_nagari, 'staf_nagari'],
                    };
                }
            }

            $pegawai->update([
                'nama_pegawai'     => $request->nama_pegawai,
                'nip'              => $request->nip,
                'nik'              => $request->nik,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan'          => $request->jabatan,
                'alamat_pegawai'   => $request->alamat_pegawai,
                'nohp_pegawai'     => $request->nohp_pegawai,
                'email_pegawai'    => $request->email_pegawai,
                'deskripsi'        => $request->deskripsi,
                'instagram'        => $request->instagram,
                'twitter'          => $request->twitter,
                'facebook'         => $request->facebook,
                'foto_profil'      => $fotoPath,
                'id_nagari'        => $idNagari,
                'jabatan_nagari'   => $jabatanNagari,
            ]);
        });

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /* ─────────────────────────────────────────
     |  DESTROY
     ──────────────────────────────────────────*/

    public function destroy($id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);

        if (!$this->canManage($pegawai)) abort(403, 'Anda tidak memiliki izin untuk menghapus pegawai ini.');

        // Camat satu-satunya tidak boleh dihapus tanpa ada pengganti
        if ($pegawai->user->role === 'camat' && User::where('role', 'camat')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus Camat karena hanya ada 1. Tambahkan Camat lain terlebih dahulu.');
        }

        DB::transaction(function () use ($pegawai) {
            if ($pegawai->foto_profil && $pegawai->foto_profil !== 'pegawai/default.jpg') {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            $userId = $pegawai->id_user;
            $pegawai->delete();
            User::destroy($userId);
        });

        return redirect()->route('pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
