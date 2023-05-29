<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Routing\Route;


use Illuminate\Support\Facades\Log;

Route::post('/broadcasting/auth', function ($user) {
    return true;
});

Route::post('/broadcasting/auth', function ($user) {
    return true;
});

Route::post('/graphql/subscriptions/auth', function ($user) {
    return true;
});

Route::post('/broadcasting/auth', function ($user) {
    return true;
});

Route::post('/graphql/broadcasting/auth', function ($user) {
    return true;
});

//use Illuminate\Support\Facades\Broadcast;
//
//Broadcast::channel('App.User.{id}', function ($user, $id){
//    return (int) $user->id === (int) $id;
//});

