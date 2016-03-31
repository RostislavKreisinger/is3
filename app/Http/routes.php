<?php

use App\Http\Controllers\HomepageController;
use App\Http\Middleware\Authenticate;

Route::middleware('auth', Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', HomepageController::class);
    });
    
    
});
