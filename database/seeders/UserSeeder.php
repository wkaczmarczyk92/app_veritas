<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\UserProfile;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Faker\Provider\en_US\Person;


class UserSeeder extends Seeder
{
    // use HasRoles;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'pesel' => null,
            'email' => 'w.kaczmarczyk@grupa-veritas.pl',
            'password' => Hash::make('haslo'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $user->assignRole('admin');

        $user_profile = new UserProfile([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'level' => null,
        ]);

        $user->user_profile()->save($user_profile);

        // $faker = Faker::create();
        // $faker->addProvider(new Person($faker));

        // for ($i = 0; $i < 10; $i++) {
        //     $user = null;
        //     $user = User::create([
        //         'pesel' => $faker->unique()->numberBetween(11111111111, 99999999999),
        //         'password' => Hash::make('haslo'),
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ]);

        //     $user->assignRole('user');

        //     $user_profile = new UserProfile([
        //         'first_name' => $faker->firstName,
        //         'last_name' => $faker->lastName,
        //         'level' => 1,
        //         'email' => $faker->email,
        //         'phone_number' => $faker->phoneNumber,
        //         'recruiter_first_name' => $faker->firstName,
        //         'recruiter_last_name' => $faker->lastName
        //     ]);

        //     $user->user_profile()->save($user_profile);
        // }
    }
}
