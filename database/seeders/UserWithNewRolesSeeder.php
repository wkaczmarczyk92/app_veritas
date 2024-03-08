<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\UserProfile;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

class UserWithNewRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Hubert',
                'last_name' => 'Smoliński',
                'role' => 'course_moderator',
                'email' => 'h.smolinski2@grupa-veritas.pl'
            ],
            [
                'first_name' => 'Krzysztof',
                'last_name' => 'Kowalski',
                'role' => 'recruiter',
                'email' => 'k.kowalski@grupa-veritas.pl'
            ]
        ];

        foreach ($users as $user) {
            $new_user = User::create([
                'email' => $user['email'],
                'password' => Hash::make('haslo')
            ]);

            $user_profile = new UserProfile([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name']
            ]);

            $new_user->user_profiles()->save($user_profile);
            $new_user->assignRole($user['role']);
        }
    }
}
