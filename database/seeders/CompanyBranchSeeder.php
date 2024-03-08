<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Common\CompanyBranch;

class CompanyBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            'centrala',
            'regiony'
        ];

        array_walk($branches, function ($branch) {
            CompanyBranch::create([
                'name' => $branch,
                'created_by' => 1,
            ]);
        });
    }
}
