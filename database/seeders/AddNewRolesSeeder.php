<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class AddNewRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            [
                'super-visor',
                'kierownik'
            ],
            [
                'team-leader',
                'team leader'
            ],
            [
                'recruiter-assistant',
                'asystent rekrutera'
            ]
        ];

        foreach ($arr as $a) {
            Role::create([
                'name' => $a[0],
                'name_pl' => $a[1],
                'guard_name' => 'web',
            ]);
        }
    }
}
