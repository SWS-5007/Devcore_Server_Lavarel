<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['prefix' => 'token', 'namespace' => '\\App\\Http\\Controllers\\Api'], function () {
//     Route::post('auth-attempt', 'AuthController@tokenAuthAttempt');
//     Route::post('auth-once', 'AuthController@tokenAuthOnce');
//     Route::post('auth-login-using-id', 'AuthController@tokenAuthLoginUsingId');
//     Route::post('auth-validate', 'AuthController@tokenAuthValidate');

//     Route::group(['middleware' => 'auth:token'], function () {
//         Route::post('auth-check', 'AuthController@tokenAuthCheck');
//         Route::post('auth-user', 'AuthController@tokenAuthUser');
//         Route::post('auth-id', 'AuthController@tokenAuthId');
//         Route::post('auth-login', 'AuthController@tokenAuthLogin');
//         Route::post('auth-logout', 'AuthController@tokenAuthLogout');
//     });
// });

// Route::group(['namespace' => '\\App\\Http\\Controllers\\Api'], function () {
//     Route::post('auth-attempt', 'AuthController@apiAuthAttempt');
//     Route::post('auth-once', 'AuthController@apiAuthOnce');
//     Route::post('auth-login-using-id', 'AuthController@apiAuthLoginUsingId');
//     Route::post('auth-validate', 'AuthController@apiAuthValidate');

//     Route::group(['middleware' => 'auth:api'], function () {
//         Route::post('auth-check', 'AuthController@apiAuthCheck');
//         Route::post('auth-user', 'AuthController@apiAuthUser');
//         Route::post('auth-id', 'AuthController@apiAuthId');
//         Route::post('auth-login', 'AuthController@apiAuthLogin');
//         Route::post('auth-logout', 'AuthController@apiAuthLogout');
//     });
// });
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     //auth('api')->logout();
//     //auth('api')->refreshToken();
//     return auth('api')->token();
// });


// Route::post('login', '\\App\\Http\\Controllers\\Api\\AuthController@login');
// Route::post('refresh', '\\App\\Http\\Controllers\\Api\\AuthController@refresh');
// Route::post('extend', '\\App\\Http\\Controllers\\Api\\AuthController@extend');
