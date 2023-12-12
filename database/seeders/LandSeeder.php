<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Land;

class LandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lands = [
            [
                'name' => 'Baden-Württemberg',
                'name_pl' => 'Badenia-Wirtembergia'
            ],
            [
                'name' => 'Bayern',
                'name_pl' => 'Bawaria'
            ],
            [
                'name' => 'Berlin',
                'name_pl' => 'Berlin'
            ],
            [
                'name' => 'Brandenburg',
                'name_pl' => 'Brandenburgia'
            ],
            [
                'name' => 'Bremen',
                'name_pl' => 'Brema'
            ],
            [
                'name' => 'Niedersachsen',
                'name_pl' => 'Dolna Saksonia'
            ],
            [
                'name' => 'Hamburg',
                'name_pl' => 'Hamburg'
            ],
            [
                'name' => 'Hessen',
                'name_pl' => 'Hesja'
            ],
            [
                'name' => 'Mecklenburg-Vorpommern',
                'name_pl' => 'Meklemburgia-Pomorze Przednie'
            ],
            [
                'name' => 'Nordrhein-Westfalen',
                'name_pl' => 'Nadrenia Północna-Westfalia'
            ],
            [
                'name' => 'Rheinland-Pfalz',
                'name_pl' => 'Nadrenia-Palatynat'
            ],
            [
                'name' => 'Saarland',
                'name_pl' => 'Saara'
            ],
            [
                'name' => 'Sachsen',
                'name_pl' => 'Saksonia'
            ],
            [
                'name' => 'Sachsen-Anhalt',
                'name_pl' => 'Saksonia-Anhalt'
            ],
            [
                'name' => 'Schleswig-Holstein',
                'name_pl' => 'Szlezwik-Holsztyn'
            ],
            [
                'name' => 'Thüringen',
                'name_pl' => 'Turyngia'
            ],
        ];

        foreach ($lands as $land) {
            Land::create([
                'name' => $land['name'],
                'name_pl' =>$land['name_pl']
            ]);
        }
    }
}
