<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CRMCaretaker;

class CaretakerIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('user_profile')
            ->whereNotNull('email')
            ->get();

        foreach ($users as $user) {
            $caretaker = CRMCaretaker::where('pesel', '=', $user->pesel)->first();
        }
    }
}
