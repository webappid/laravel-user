<?php

$controller = 'WebAppId\User\Controllers';

Route::name('api.')->prefix('api')->group(function () use ($controller) {
    Route::post('/login', $controller . '\AuthController@login')->name('login');
    Route::get('/route-auto-collect', $controller . '\Routes\RouteAutoCollectController')->name('login');
    Route::group(['middleware' => ['auth:sanctum', 'role:admin|member|partner']], function () use ($controller) {
        Route::get('/profile', function (Request $request) {
            return auth()->user();
        })->name('profile');
        Route::post('/logout', $controller . '\AuthController@logout')->name('logout');
    });
    Route::name('otp.')->prefix('otp')->group(function () use ($controller) {
        Route::post('/request', WebAppId\User\Controllers\Otp\RequestController::class)->name('request');
        Route::post('/login', WebAppId\User\Controllers\Otp\LoginController::class)->name('otpLogin');
    });
});
