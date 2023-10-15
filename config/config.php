<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Time
    |--------------------------------------------------------------------------
    |
    | Cache time for get data translation
    |
    | - set zero for remove cache
    | - set null for forever
    |
    | - unit: minutes
    */

    "cache_time" => env("MEDIA_CACHE_TIME", 0),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Name of filesystem disks laravel
    |
    */

    'disks' => [

        'local' => [
            'capacity' => env('MEDIA_DISK_CAPACITY_LOCAL', 1024 * 1024 * 1000), // 1000mb
        ],

        'public' => [
            'capacity' => env('MEDIA_DISK_CAPACITY_PUBLIC', 1024 * 1024 * 1000), // 1000mb
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Collections
    |--------------------------------------------------------------------------
    |
    | Collection type
    */

    'collections' => [
        'public' => [
            // Select disk to storage data
            'disk'              => env("MEDIA_COLLECTION_PUBLIC_DISK", env('FILESYSTEM_DISK', 'local')),

            // If you want to avoid uploading duplicate files, disable this option
            "duplicate_content" => env("MEDIA_COLLECTION_PUBLIC_DUPLICATE_CONTENT", false),
        ],
        'avatar' => [
            // Select disk to storage data
            'disk'              => env("MEDIA_COLLECTION_AVATAR_DISK", env('FILESYSTEM_DISK', 'local')),

            // If you want to avoid uploading duplicate files, disable this option
            "duplicate_content" => env("MEDIA_COLLECTION_AVATAR_DUPLICATE_CONTENT", true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File max size
    |--------------------------------------------------------------------------
    |
    | Maximum file size
    |
    | default: 10 megabyte
    | sample: 1024 * 1024 * megabyte number
    */

    'file_max_size' => env("MEDIA_FILE_MAX_SIZE", (1024*1024*10)),   // 10M

    /*
    |--------------------------------------------------------------------------
    | Mime Type
    |--------------------------------------------------------------------------
    |
    | Mime type accept
    |
    | https://www.iana.org/assignments/media-types/media-types.xhtml
    */

    'mime_type' => [
        'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mime Type
    |--------------------------------------------------------------------------
    |
    | Mime type accept
    |
    | https://www.iana.org/assignments/media-types/media-types.xhtml
    */

    'mime_type_responsive' => [
        'image/png',
    ],

];
