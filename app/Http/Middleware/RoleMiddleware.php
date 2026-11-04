<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (!in_array($user->role, $roles)) {
            // Jika siswa mencoba akses admin, arahkan ke dashboard siswa
            if ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman Admin.');
            }

            // Jika admin mencoba akses dashboard siswa, arahkan ke dashboard admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses dialihkan ke dashboard Admin.');
            }

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
