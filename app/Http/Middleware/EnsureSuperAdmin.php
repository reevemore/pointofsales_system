<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Redirect superadmin jika mencoba akses halaman operasional.
     * Superadmin hanya boleh akses route dengan prefix /stores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isSuperAdmin()) {
            // Izinkan akses ke /stores/* dan /profile/* dan /logout
            $allowedPrefixes = ['stores', 'profile', 'logout'];

            $isAllowed = collect($allowedPrefixes)->contains(function ($prefix) use ($request) {
                return $request->is($prefix) || $request->is($prefix . '/*');
            });

            if (!$isAllowed) {
                return redirect()->route('stores.index')
                    ->with('warning', 'Superadmin hanya dapat mengakses halaman Manajemen Toko.');
            }
        }

        return $next($request);
    }
}