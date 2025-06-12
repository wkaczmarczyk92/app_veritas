<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'superadmin@grupa-veritas.pl',
            'active' => true,
            'password' => Hash::make('superadmin'),
        ]);

        $user->assignRole('super-admin');
    }
}
