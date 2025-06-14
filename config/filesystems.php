<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public_images' => [
            'driver' => 'local',
            'root' => public_path('user_profile_images'),
        ],

        'media_library' => [
            'driver' => 'local',
            'root' => public_path('media_library'),
        ],

        'lessons' => [
            'driver' => 'local',
            'root' => public_path('lessons'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/uploads',
            'visibility' => 'public',
            'throw' => false,
        ],

        // 'media_library' => [
        //     'driver' => 'local',
        //     'root' => storage_path('app/public'),
        //     'url' => env('APP_URL') . '/uploads/media_library',
        //     'visibility' => 'public',
        //     'throw' => false,
        // ],

        'course_files' => [
            'driver' => 'local',
            'root' => storage_path('app/public/course_files'),
            'url' => env('APP_URL') . '/course_files',
            'visibility' => 'public',
            'throw' => false,
        ],

        // 'lessons' => [
        //     'driver' => 'local',
        //     'root' => storage_path('app/public/lessons'),
        //     'url' => env('APP_URL') . '/lessons',
        //     'visibility' => 'public',
        //     'throw' => false,
        // ],

        'exports' => [
            'driver' => 'local',
            'root' => storage_path('app/public/exports'),
            'url' => env('APP_URL') . '/exports',
            'visibility' => 'private',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('uploads') => storage_path('app/public'),
        public_path('course_files') => storage_path('app/public/course_files'),
        // public_path('uploads/media_library') => storage_path('app/media_library'),
    ],

];
