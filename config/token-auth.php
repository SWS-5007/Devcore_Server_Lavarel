<?php

use Illuminate\Support\Str;

return [
    // token model
    'token' => \App\Lib\Auth\AuthToken::class,
    // the user class
    'user' => \App\Models\User::class,
    // the grace period after the token has been revoked (seconds)
    'grace_period' => 10,
    // ttl (seconds) 0 = never expires
    'ttl' => 3600,
    'extended-ttl' => 2592000, //1 month

    //refresh token ttl (minutes) 1 year default 0= never expires
    'refresh_token_ttl' => 527040,

    // the name of the input
    'input_key' => 'devcore_token',

    // the name of the storage token id
    'storage_key' => 'access_token',

    // the name of the input refresh
    'input_refresh' => 'refresh_token',

    //the name of the storage refresh
    'storage_refresh' => 'refresh_token',

    //should we use cookies
    'use_cookies' => env('TOKEN_AUTH_COOKIES', true),

    //cookie path
    'cookie_path' => env('TOKEN_AUTH_COOKIE_PATH', env('SESSION_PATH_COOKIE', false)),

    //cookie domain
    'cookie_domain' => env('TOKEN_AUTH_COOKIE_PATH', env('SESSION_DOMAIN', null)),

    //cookie secured
    'cookie_secure' => env('TOKEN_AUTH_COOKIE_SECURE', env('SESSION_SECURE_COOKIE', false)),

    //cookie http_only
    'cookie_http_only' => env('TOKEN_AUTH_COOKIE_HTTP_ONLY', env('SESSION_HTTP_ONLY_COOKIE', false)),

    //cookie same site
    'cookie_same_site' => null,

];
