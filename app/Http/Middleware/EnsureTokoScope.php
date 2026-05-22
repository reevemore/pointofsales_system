<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokoScope
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Superadmin tidak butuh toko_id — lewatkan saja
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // User biasa (admin/kasir) wajib punya toko_id
        if ($user && !$user->toko_id) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda belum terhubung ke toko manapun. Hubungi superadmin.']);
        }

        return $next($request);
    }
}