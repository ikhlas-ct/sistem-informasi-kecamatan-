<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, langsung redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('pages.login.login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'nip_nik'  => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nip_nik', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek status akun
            if ($user->status === 'nonaktif') {
                Auth::logout();
                return back()->withErrors([
                    'nip_nik' => 'Akun Anda sedang nonaktif. Hubungi administrator.',
                ])->onlyInput('nip_nik');
            }

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'nip_nik' => 'Kode unik atau password salah.',
        ])->onlyInput('nip_nik');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }

    // ─────────────────────────────────────────────
    // HELPER: redirect berdasarkan role
    // ─────────────────────────────────────────────
    private function redirectByRole($user)
    {
        switch ($user->role) {
            case 'camat':
                return redirect()->route('camat.dashboard')
                    ->with('success', 'Login berhasil sebagai Camat!');

            case 'pegawai':
                // Pegawai nagari (kepala/staf) vs staf camat
                $pegawai = $user->pegawai;
                if ($pegawai && !is_null($pegawai->id_nagari)) {
                    return redirect()->route('pegawai.dashboard')
                        ->with('success', 'Login berhasil sebagai ' . ucfirst($pegawai->jabatan_nagari ?? 'Pegawai') . '!');
                }
                // Staf camat (id_nagari null)
                return redirect()->route('pegawai.dashboard')
                    ->with('success', 'Login berhasil sebagai Staf Kecamatan!');

            case 'sekolah':
                return redirect()->route('sekolah.dashboard')
                    ->with('success', 'Login berhasil sebagai Sekolah!');

            case 'siswa':
                return redirect()->route('siswa.dashboard')
                    ->with('success', 'Login berhasil sebagai Siswa!');

            case 'masyarakat':
                return redirect()->route('home')
                    ->with('success', 'Login berhasil!');

            default:
                return redirect()->route('home')
                    ->with('success', 'Login berhasil!');
        }
    }
}
