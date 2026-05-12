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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error','Login dulu ya.');
        }
        $user = Auth::guard('web')->user();

        \Log::info("CheckRole: user_id={$user->id}, status={$user->status}");

        // cek status
        if (strtolower($user->status) !== 'aktif') {
            Auth::guard('web')->logout();
            return redirect()->route('login')
                             ->with('error','Akun Anda tidak aktif.');
        }

        // cek role
        if (! $user->hasRole($roles)) {
            abort(403);
        }

        return $next($request);
    }


}
