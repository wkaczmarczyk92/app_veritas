<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Bonus;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bonus::create([
            'name' => 'family_recommendation',
            'bonus_value' => 100
        ]);

        Bonus::create([
            'name' => 'caretaker_recommendation',
            'bonus_value' => 50
        ]);
    }
}
