<?php

$controller = 'WebAppId\User\Controllers';

Route::name('api.')->prefix('api')->group(function () use ($controller) {
    Route::post('/login', $controller . '\AuthController@login')->name('login');
    Route::get('/route-auto-collect', $controller . '\Routes\RouteAutoCollectController')->name('auto.collect');
    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () use ($controller) {
        Route::get('/profile', function(Request $request) {
            return auth()->user();
        })->name('profile');
        Route::post('/logout', $controller . '\AuthController@logout')->name('logout');
    });
});
