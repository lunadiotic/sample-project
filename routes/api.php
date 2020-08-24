<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', 'Api\Auth\RegisterController');
Route::post('/auth/login', 'Api\Auth\LoginController@login');
Route::post('/auth/logout', 'Api\Auth\LoginController@logout')
    ->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/attendace/today', 'Api\AttendanceController@today');
    Route::post('/attendace/in', 'Api\AttendanceController@in');
    Route::post('/attendace/out', 'Api\AttendanceController@out');
});
