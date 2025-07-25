<?php

namespace App\Services\User;

use App\Helpers\Transaction;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserStoreService
{
    public function __invoke($data)
    {
        // dd($user);
        return Transaction::try(
            function () use ($data) {
                $user = User::create([
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'active' => true
                ]);

                $user->user_profiles()->save(new UserProfile([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name']
                ]));

                $user->assignRole($data['role_id']);
            },
            'Użytkownik został dodany.'
        );
    }
}
