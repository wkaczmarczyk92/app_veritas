<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Common\FileMimeType;

class FileMimeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            ['type' => 'audio/aac'],
            ['type' => 'audio/midi'],
            ['type' => 'audio/x-midi'],
            ['type' => 'audio/mpeg'],
            ['type' => 'audio/ogg'],
            ['type' => 'audio/opus'],
            ['type' => 'audio/wav'],
            ['type' => 'audio/webm'],
            ['type' => 'audio/3gpp'],
            ['type' => 'audio/3gpp2'],
            ['type' => 'video/x-msvideo'],
            ['type' => 'video/mp4'],
            ['type' => 'video/mpeg'],
            ['type' => 'video/ogg'],
            ['type' => 'video/webm'],
            ['type' => 'video/3gpp'],
            ['type' => 'video/3gpp2'],
            ['type' => 'video/x-flv'],
            ['type' => 'video/x-matroska'],
            ['type' => 'video/x-ms-wmv'],
            ['type' => 'image/bmp'],
            ['type' => 'image/gif'],
            ['type' => 'image/jpeg'],
            ['type' => 'image/png'],
            ['type' => 'image/svg+xml'],
            ['type' => 'image/tiff'],
            ['type' => 'image/webp'],
            ['type' => 'image/x-icon'],
            ['type' => 'image/heic'],
            ['type' => 'image/heif'],
        ];

        foreach ($arr as $item) {
            FileMimeType::create($item);
        }
    }
}
