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

Route::get('me', 'App\Http\Controllers\User\MeController@getMe');




Route::group(['middleware' => ['auth:api']], function(){
    Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout');
});


Route::group(['middleware' => ['guest:api']], function(){
    Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.reset');
    Route::post('register','App\Http\Controllers\Auth\RegisterController@register');
    Route::post('verification/verify','App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','App\Http\Controllers\Auth\VerificationController@resend');
    Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
    Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

});
