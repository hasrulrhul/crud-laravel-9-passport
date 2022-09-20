<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//AUTH
use App\Http\Controllers\API\AuthController as Auths;
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

Route::post('/auth/register', [Auths::class, 'register']);
Route::post('/auth/login', [Auths::class, 'login']);

Route::group(['prefix' => '', 'namespace' => 'App\Http\Controllers\API',  'middleware' => 'auth:api'], function () {

    Route::get('/auth/logout', [Auths::class, 'logout']);

    Route::group(['prefix' => '/users'], function () {
        Route::get('/', 'UserController@index')->name('api.users.index');
        Route::post('/', 'UserController@store')->name('api.users.store');
        Route::get('/{id}', 'UserController@show')->name('api.users.show');
        Route::put('/{id}', 'UserController@update')->name('api.users.update');
        Route::delete('/{id}', 'UserController@destroy')->name('api.users.delete');
    });
});
