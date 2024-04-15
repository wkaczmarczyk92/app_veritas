<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Test\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            ['open', 'otwarte'],
            ['closed', 'zamknięte - jednokrotny wybór'],
            ['closed_multiple', 'zamknięte - wielokrotny wybór'],
            ['match', 'dopasuj odpowiedzi']
        ];

        foreach ($arr as $item) {
            QuestionType::create([
                'type' => $item[0],
                'name_pl' => $item[1]
            ]);
        }
    }
}
