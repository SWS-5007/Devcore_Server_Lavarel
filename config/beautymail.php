<?php

return [

    // These CSS rules will be applied after the regular template CSS


        'css' => [
            '@font-face {
    font-family: FuturaBQ;
    src: url(./fonts/FuturaBQ.woff) format("woff")}',


            '.button-content .button { background: red }',
            '.title {    font-weight: 700;
    font-size:18px;
    color: #212529;
    font-family: "FuturaBQ", "Times New Roman";}'
        ],


    'colors' => [

        'highlight' => '#004ca3',
        'button'    => '#004cad',

    ],

    'view' => [
        'senderName'  => null,
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => '%PUBLIC%/favicon.ico',
            'width'  => '',
            'height' => '',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
