<?php

return [
    'dispatcher' => [
        'name' => 'image-dispatcher',
        'method' => '\App\Lib\Http\Controllers\ImageDispatcherController@dispatchImage',
        //'url' => 'images/{section}/{folder}/{thumb}/{filename}',
        'url' => 'images/{parameters}',
    ],
    'default' => [
        'defaults-folder' => 'defaults',
        'not-found-image' => 'default.png',
        'generate-defaults' => true,
        'allowed-types' => ['#image/jpeg#', '#image/gif#', '#image/png#', '#image/bmp#', '#image/svg+xml#'],
        'valid-thumbs' => [
            '16x16',
            '25x25',
            '30x30',
            '90x90',
            '200x200',
            '250x250',
            '200-155',
            '200-0',
            '200-200',
            '200x155',
            '250x100',
            '100x100',
            '150x150',
            '150-150',
            '250x250',
            '500x500',
            '980x200',
            '300-200',
            '300x300',
            '980x400',
            '120x120',
            '200x150',
            '450x150',
            '0x150',
            '150x100',
            '150-100',
            '200-100',
            '70x70',
            '50x50',
            '0x50',
            '0x0',
        ],
    ],
    'defaults' => [

    ],
    'temp' => [],
    'users' => [
        'not-found-image' => 'user.jpg',
    ],
    'companies' => [
        'not-found-image' => 'company.jpg',
    ],
    'company-roles' => [
    ],
    'company-tools' => [
    ],
];
