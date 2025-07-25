<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = auth()->user();
        $test_user = auth()->check() ? $user->pesel == 12312312322 : false;

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'test_user' => $test_user,
            'god_mode' => $user ? $user->hasRole('god_mode') : false,
            'is_super_admin' => $user ? $user->hasRole('super-admin') : false,
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'maintenance_mode_status' => app()->isDownForMaintenance()
        ]);
    }
}
