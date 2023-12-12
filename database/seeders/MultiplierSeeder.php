<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Multiplier;

class MultiplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multipliers = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4
        ];

        foreach ($multipliers as $index => $value) {
            $m = Multiplier::create([
                'level_id' => $index,
                'multiplier_value' => $value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
