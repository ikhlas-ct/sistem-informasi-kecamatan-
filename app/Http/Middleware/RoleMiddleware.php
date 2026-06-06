<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Role yang didukung (bisa digabung bebas di route):
     *   Raw role   : camat | pegawai | masyarakat
     *   Granular   : staf_camat | wali_nagari | staf_nagari
     *              | admin_sekolah | siswa_sekolah
     *
     * Catatan: tidak ada lagi raw role 'sekolah' atau 'siswa'.
     * Admin & siswa sekolah keduanya ber-role 'masyarakat',
     * dibedakan lewat kolom `sekolah` ('admin' / 'siswa').
     * Gunakan label granularnya di route:
     *
     * Contoh pemakaian di routes:
     *   ->middleware('role:camat,staf_camat')
     *   ->middleware('role:wali_nagari,staf_nagari')
     *   ->middleware('role:masyarakat')
     *   ->middleware('role:admin_sekolah')
     *   ->middleware('role:siswa_sekolah')
     *   ->middleware('role:admin_sekolah,siswa_sekolah')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan sudah login
        if (! Auth::guard('web')->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('web')->user();

        \Log::info('CheckRole', [
            'user_id'    => $user->id,
            'status'     => $user->status,
            'role'       => $user->role,
            'role_label' => $user->getRoleLabel(),
            'requires'   => $roles,
        ]);

        // 2. Cek status akun
        if (strtolower($user->status) !== 'aktif') {
            Auth::guard('web')->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak aktif.');
        }

        // 3. Cek role — cocokkan raw role DAN role granular
        if (! $this->userHasAnyRole($user, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    /**
     * Cek apakah user cocok dengan salah satu role yang diminta.
     *
     * Urutan pengecekan:
     *   a) Cocokkan dengan raw role  ($user->role)
     *   b) Cocokkan dengan role label ($user->getRoleLabel())
     *      → mencakup: staf_camat, wali_nagari, staf_nagari,
     *                  admin_sekolah, siswa_sekolah, masyarakat
     */
    private function userHasAnyRole($user, array $roles): bool
    {
        if (empty($roles)) {
            return true; // tidak ada pembatasan
        }

        $rawRole   = strtolower(trim($user->role));
        $labelRole = strtolower(trim($user->getRoleLabel()));

        foreach ($roles as $role) {
            $role = strtolower(trim($role));

            if ($role === $rawRole || $role === $labelRole) {
                return true;
            }
        }

        return false;
    }
}
