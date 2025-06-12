<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Common\FileType;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            ['type' => 'course_files_for_users'],
            ['type' => 'media_library'],
        ];

        foreach ($arr as $item) {
            FileType::create($item);
        }
    }
}
