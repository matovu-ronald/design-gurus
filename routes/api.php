<?php

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

// Public routes
Route::get('me', 'User\MeController@getMe');

// Design Routes
Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/{id}', 'Designs\DesignController@findDesign');

// Users Routes
Route::get('users', 'User\UserController@index');

// Guest routes
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

// Authenticated routes
Route::group(['middleware' => ['auth:api']], function () {
    // User settings
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');

    // Upload Designs
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{design}', 'Designs\DesignController@destroy');

    // Comments
    Route::post('designs/{designId}/comments', 'Designs\CommentController@store');
    Route::put('comments/{id}', 'Designs\CommentController@update');
    Route::delete('comments/{id}', 'Designs\CommentController@destroy');

    // Likes and Unlikes
    Route::post('designs/{id}/like', 'Designs\DesignController@like');
    Route::post('designs/{designId}/liked', 'Designs\DesignController@checkIfUserHasLiked');
});
