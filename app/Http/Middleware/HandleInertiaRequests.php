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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => $request->user()?->getRoleNames()->first(),
            ],
            'flash' => [
                'success'     => fn () => $request->session()->get('success'),
                'transaction' => fn () => $request->session()->get('transaction'),
            ],
            'pageTitle'    => fn () => $request->session()->get('pageTitle', 'Dashboard'),
            'pageSubtitle' => fn () => $request->session()->get('pageSubtitle', ''),
        ];
    }
}