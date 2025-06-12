<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Common\FileMimeType;

class MimeTypeSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $audio_mime_types = [
            '3gp' => 'audio/3gpp',
            'aac' => 'audio/aac',
            'adts' => 'audio/aac',
            'aif' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'au' => 'audio/basic',
            'caf' => 'audio/x-caf',
            'flac' => 'audio/flac',
            'kar' => 'audio/midi',
            'm3u' => 'audio/x-mpegurl',
            'm4a' => 'audio/x-m4a',
            'm4b' => 'audio/x-m4b',
            'm4p' => 'audio/x-m4p',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mp3' => 'audio/mpeg',
            'mp4a' => 'audio/mp4',
            'mpga' => 'audio/mpeg',
            'oga' => 'audio/ogg',
            'ogg' => 'audio/ogg',
            'opus' => 'audio/opus',
            's3m' => 'audio/s3m',
            'sil' => 'audio/silk',
            'snd' => 'audio/basic',
            'spx' => 'audio/ogg',
            'wav' => 'audio/wav',
            'weba' => 'audio/webm',
            'wma' => 'audio/x-ms-wma',
            'xm' => 'audio/xm',
            'mka' => 'audio/x-matroska',
            'cda' => 'application/x-cdf',
        ];

        foreach ($audio_mime_types as $type) {
            if (FileMimeType::where('type', $type)->doesntExist()) {
                FileMimeType::create([
                    'type' => $type,
                ]);
            }
        }
    }
}
