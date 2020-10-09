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
Route::post('/auth/password/forgot', 'Api\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/auth/password/reset', 'Api\Auth\ResetPasswordController@reset')->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/attendance/today', 'Api\AttendanceController@today');
    Route::get('/attendance/history', 'Api\AttendanceController@getHistory');
    Route::get('/attendance/history/list', 'Api\AttendanceController@getHistory');
    Route::post('/attendance/in', 'Api\AttendanceController@in');
    Route::post('/attendance/out', 'Api\AttendanceController@out');

    Route::put('/profile/update', 'Api\ProfileController@update');
});
