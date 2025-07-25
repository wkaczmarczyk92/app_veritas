<?php

namespace App\Services\User\Admin;

use App\Models\User;

class UsersSearchService
{
    public function index($request)
    {
        $auth_user = auth()->user();

        if ($request->has('filters')) {
            $users = User::with([
                'user_profiles',
                'user_profile_image',
                'roles'
            ]);

            if (!$auth_user->hasRole('god_mode')) {
                $users->whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                });
            }

            $users->whereHas('user_profiles', function ($query) use ($request) {
                $query->where('first_name', 'like', "%{$request->filters['search']}%")
                    ->orWhere('last_name', 'like', "%{$request->filters['search']}%")
                    ->orWhere('email', 'like', "%{$request->filters['search']}%")
                    ->orWhere('phone_number', 'like', "%{$request->filters['search']}%")
                    ->orWhere('recruiter_first_name', 'like', "%{$request->filters['search']}%")
                    ->orWhere('recruiter_last_name', 'like', "%{$request->filters['search']}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE '%{$request->filters['search']}%'")
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE '%{$request->filters['search']}%'")
                    ->orWhereRaw("CONCAT(recruiter_first_name, ' ', recruiter_last_name) LIKE '%{$request->filters['search']}%'")
                    ->orWhereRaw("CONCAT(recruiter_last_name, ' ', recruiter_first_name) LIKE '%{$request->filters['search']}%'");
            });

            $users->orWhere('pesel', 'like', "%{$request->filters['search']}%");

            // $users->whereHas('user_profiles', function ($query) use ($request) {
            //     $query->where('current_points', '>=', $request->current_points);
            // });
            $users = $users->get();
            return response()->json([
                'users' => $users,
                'success' => true
            ], 200);

        } else {
            return response()->json([
                'msg' => 'Brak wybranych filtrów.'
            ], 400);
        }
    }
}
