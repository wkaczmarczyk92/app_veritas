<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Courses\CompendiumType;

class CompendiumTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'compendium',
            'vademecum'
        ];

        array_walk($types, function ($type) {
            CompendiumType::create([
                'type' => $type
            ]);
        });
    }
}
