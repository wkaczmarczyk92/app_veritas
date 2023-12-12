<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $l = [
            'brak znajomości' => 0,
            'podstawowy' => 1,
            'komunikatywny' => 2,
            'dobry' => 3,
            'bardzo dobry' => 4
        ];

        foreach ($l as $name => $cast_id) {
            Language::create([
                'name' => $name,
                'cast_id' => $cast_id
            ]); 
        }
    }
}
