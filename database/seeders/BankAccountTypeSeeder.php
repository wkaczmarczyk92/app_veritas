<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Banking\AccountType;

class BankAccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            ['foreign currency account', 'konto walutowe'],
            ['pln account', 'konto złotówkowe']
        ];

        foreach ($arr as $item) {
            AccountType::create([
                'type' => $item[0],
                'type_pl' => $item[1]
            ]);
        }
    }
}
