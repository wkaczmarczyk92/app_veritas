<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class BOKSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'zmiana danych osobowych lub adresowych',
            'nabycie praw emeryta lub rencisty',
            'zmiana numeru konta',
            'zaświadczenie',
            'zgłoszenie członka rodziny do ubezpieczenia'
        ];

        foreach ($subjects as $subject) {
            DB::table('bok_subjects')->insert([
                'subject' => $subject,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
