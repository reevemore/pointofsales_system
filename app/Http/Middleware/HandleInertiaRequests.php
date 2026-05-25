<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user'     => $user,
                'role'     => $user?->getRoleNames()->first(),
                'toko_id'  => $user?->toko_id,
                'toko'     => $user?->store ? [
                    'id'   => $user->store->id,
                    'nama' => $user->store->nama,
                ] : null,
            ],
            'flash' => [
                'success'     => fn () => $request->session()->get('success'),
                'error'       => fn () => $request->session()->get('error'),
                'warning'     => fn () => $request->session()->get('warning'),
                'transaction' => fn () => $request->session()->get('transaction'),
            ],
            'pageTitle'    => fn () => $request->session()->pull('pageTitle', ''),
            'pageSubtitle' => fn () => $request->session()->pull('pageSubtitle', ''),
        ];
    }
}