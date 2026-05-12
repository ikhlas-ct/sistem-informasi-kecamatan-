<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.login.login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'nip_nik' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nip_nik', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user(); // Ambil data user yang login

            // Cek role untuk redirect sesuai dashboard
            if ($user->role === 'camat') {
                return redirect()->route('camat.dashboard')->with('success', 'Login berhasil sebagai Camat!');
            } elseif ($user->role === 'pegawai') {
                return redirect()->route('pegawai.dashboard')->with('success', 'Login berhasil sebagai Pegawai!');
            } elseif ($user->role === 'masyarakat') {
                return redirect()->route('home')->with('success', 'Login berhasil sebagai Masyarakat!');
            } else {
                return redirect()->route('home')->with('success', 'Login berhasil!');
            }
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
}

