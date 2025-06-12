<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BonusStatus;

class BonusStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            ['in_progress', 'w trakcie realizacja'],
            ['completed', 'zrealizowany'],
            ['canceled', 'anulowany'],
            ['to_approved', 'do zatwierdzenia'],
        ];

        foreach ($arr as $item) {
            $bonusStatus = BonusStatus::create([
                'name' => $item[0],
                'name_pl' => $item[1],
            ]);
        }
    }
}
