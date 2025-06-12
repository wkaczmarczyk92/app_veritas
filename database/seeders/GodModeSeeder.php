<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class GodModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'god_mode',
            'name_pl' => 'god mode',
            'guard_name' => 'web',
            'show_course' => false
        ]);

        $user = User::find(1);
        $user->assignRole('god_mode');
    }
}
