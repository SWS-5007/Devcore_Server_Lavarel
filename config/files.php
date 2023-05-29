<?php

return [
    'uploader' => [
        'name' => 'file-uploader',
        'url' => 'files/{parameters}',
    ],
    'uploader-temp' => [
        'name' => 'file-temp-uploader',
        'url' => 'upload-temp',
    ],
    'default' => [
        'disk' => env('FILESYSTEM_DISK', 'uploads'),
        'cache-disk' => 'cache',
        'temp-disk' => 'temp',
        'folder' => '{section}/{folder}',
        'cache-folder' => '{section}/{folder}',
        'temp-folder' => '{section}/temp/{folder}'
    ],
    'temp'=>[
        'disk' => 'temp',
        'folder'=>'',
    ]
];
