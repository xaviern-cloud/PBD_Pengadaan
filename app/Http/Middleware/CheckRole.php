<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan key session sama dengan AuthController ('id_user')
        if (!session()->has('id_user')) {
            return redirect()->route('login')->withErrors([
                'login_error' => 'Silakan login terlebih dahulu.',
            ]);
        }

        $userRole = strtolower(trim(session('role', '')));

        // 1. Manager: boleh akses semua halaman & create/edit, tapi DILARANG DELETE
        if (str_contains($userRole, 'manager')) {
            if ($request->isMethod('delete')) {
                return back()->with('error', 'Akses Ditolak: Manager tidak memiliki izin untuk menghapus data.');
            }
            return $next($request);
        }

        // 2. Admin: akses penuh ke semua fitur termasuk DELETE
        if (str_contains($userRole, 'admin')) {
            return $next($request);
        }

        // 3. Role lainnya (misal: Kasir): cek apakah salah satu role yang diizinkan cocok
        foreach ($roles as $role) {
            if (str_contains($userRole, strtolower(trim($role)))) {
                return $next($request);
            }
        }

        abort(403, 'Akses Ditolak: Role "' . session('role') . '" tidak memiliki otoritas untuk halaman ini.');
    }
}