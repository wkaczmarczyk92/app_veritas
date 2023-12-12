<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PostLabel;

class PostLabelSeeder extends Seeder
{
    private $arr = [
        'bonus',
        'premia',
        'konkurs'
    ];

    public function run(): void
    {
        foreach ($this->arr as $name) {
            PostLabel::create(['name' => $name]);
        }
    }
}
