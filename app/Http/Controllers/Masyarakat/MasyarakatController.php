<?php

namespace App\Http\Controllers\Masyarakat;

use App\Models\Nagari;
use App\Models\User;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use App\Models\Kecamatansetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MasyarakatController extends Controller
{
    public function dashboard()
    {
        $user        = Auth::user();
        $masyarakat  = $user->masyarakat;

        return view('pages.masyarakat.dashboard', compact('user', 'masyarakat'));
    }

    // ─────────────────────────────────────────────
    // FORM PENDAFTARAN
    // ─────────────────────────────────────────────

    public function create()
    {
        return view('pages.login.pendaftaran');
    }

    // ─────────────────────────────────────────────
    // PROSES PENDAFTARAN
    // Alur:
    //   1. Validasi input dasar
    //   2. Cari NIK di tabel masyarakat
    //   3. Cocokkan KK
    //   4. Cek apakah akun sudah aktif (password ≠ null)
    //   5. Jika belum aktif → buat / update user & link ke masyarakat
    // ─────────────────────────────────────────────

    public function store(Request $request)
    {
        // ── 1. Validasi input ──────────────────────────────────────────
        $request->validate([
            'nik'                   => 'required|string|size:16',
            'kk'                    => 'required|string|size:16',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'nik.required'                  => 'NIK wajib diisi.',
            'nik.size'                      => 'NIK harus tepat 16 digit.',
            'kk.required'                   => 'Nomor KK wajib diisi.',
            'kk.size'                       => 'Nomor KK harus tepat 16 digit.',
            'password.required'             => 'Password wajib diisi.',
            'password.min'                  => 'Password minimal 8 karakter.',
            'password.confirmed'            => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        // ── 2. Cari masyarakat berdasarkan NIK ────────────────────────
        $masyarakat = Masyarakat::where('nik', $request->nik)->first();

        if (! $masyarakat) {
            return back()
                ->withErrors(['nik' => 'NIK tidak ditemukan dalam sistem. Hubungi petugas nagari Anda.'])
                ->withInput();
        }

        // ── 3. Cocokkan Nomor KK ──────────────────────────────────────
        if ($masyarakat->kk !== $request->kk) {
            return back()
                ->withErrors(['kk' => 'Nomor KK tidak sesuai dengan NIK yang terdaftar.'])
                ->withInput();
        }

        // ── 4. Cek status akun ────────────────────────────────────────
        if ($masyarakat->id_user) {
            $existingUser = User::find($masyarakat->id_user);

            if ($existingUser) {
                // Password sudah di-set → akun aktif, tolak pendaftaran ulang
                if (! is_null($existingUser->password)) {
                    return back()
                        ->withErrors(['nik' => 'Akun dengan NIK ini sudah aktif. Silakan login menggunakan akun Anda.'])
                        ->withInput();
                }

                // Password masih null → aktifkan akun dengan set password
                $existingUser->update([
                    'password' => Hash::make($request->password),
                    'status'   => 'aktif',
                ]);

                return redirect()->route('login')
                    ->with('success', 'Akun berhasil diaktifkan! Silakan login dengan NIK dan password Anda.');
            }
        }

        // ── 5. Belum ada user sama sekali → buat user baru ────────────
        $newUser = User::create([
            'nip_nik'  => $request->nik,
            'password' => Hash::make($request->password),
            'role'     => 'masyarakat',
            'status'   => 'aktif',
        ]);

        // Hubungkan user baru ke data masyarakat yang sudah ada
        $masyarakat->update(['id_user' => $newUser->id]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login dengan NIK dan password Anda.');
    }
}
