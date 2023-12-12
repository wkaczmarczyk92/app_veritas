<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            'biały',
            'brązowy',
            'srebrny',
            'złoty'
        ];

        foreach ($levels as $level) {
            DB::table('levels')->insert([
                'name' => $level
            ]);
        }
    }
}
