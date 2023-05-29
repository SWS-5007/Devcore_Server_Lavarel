<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\Test;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use Illuminate\Support\Facades\Route;

Route::get(config('images.dispatcher.url'), config('images.dispatcher.method'))->where(['parameters' => '.*'])->name(config('images.dispatcher.name'));
Route::post(config('files.uploader.url'), '\App\Lib\Http\Controllers\FileUploadController@upload')->where(['parameters' => '.*'])->name(config('files.uploader.name'));
Route::post(config('files.uploader-temp.url'), '\App\Lib\Http\Controllers\FileUploadController@uploadTemp')->name(config('files.uploader-temp.name'));
Route::get(config('resources.controller.url'), config('resources.controller.class'). '@show')->where(['parameters' => '.*'])->name(config('resources.controller.name') .'.show');
Route::get(config('resources.controller.private-url'), config('resources.controller.class'). '@showPrivate')->where(['parameters' => '.*'])->name(config('resources.controller.name') .'.showPrivate');

//Route::any('/test', '\App\Http\Controllers\TestController@index');

Route::get('/mobile/{any}', 'MobileController@index')->where('any', '.*');
Route::get('/{any}', 'FrontendController@index')->where('any', '.*');

