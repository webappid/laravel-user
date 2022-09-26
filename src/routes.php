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
        Route::get('/request/{email}', WebAppId\User\Controllers\Otp\RequestController::class)->name('request');
        Route::get('/login/{token}', WebAppId\User\Controllers\Otp\LoginController::class)->name('request');
    });
});
