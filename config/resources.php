<?php


return [
    'controller' => [
        'url' => 'resources/{parameters}',
        'private-url' => 'resources/private/{parameters}',
        'class' => '\\App\\Lib\\Http\\Controllers\\ResourcesController',
    ],
    'default' => [
        /*'disk' => 'uploads',
        'cache-disk' => 'uploads_cache',
        'folder' => '{section}/{folder}',
        'cache-folder' => '{section}/{folder}',
        'temp-folder' => '{section}/temp/{folder}',*/
        'file-config'=>'{section}',
        'owner-type' => \App\Lib\Models\Resources\ResourceCollection::class,
        'type' => \App\Lib\Models\Resources\Resource::class,
        'display-type' => 'file',
        'visibility' => 'public',
    ],
    'ideas' => [
        'owner-type' => \App\Models\Idea::class,
        'display-type' => 'file',
        'visibility' => 'public',
    ],
    // 'ideas' => [
    //     'folder' => 'offers',
    //     'display-type' => 'image'
    // ]
];
