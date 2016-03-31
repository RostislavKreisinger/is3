<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Search\ClientController;
use App\Http\Controllers\Search\ProjectController;
use App\Http\Controllers\Search\UserController;
use App\Http\Middleware\Authenticate;

Route::middleware('auth', Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        
        
        Route::group(['prefix' => 'search'], function(){
            Route::controller('user', UserController::class);
            Route::controller('client', ClientController::class);
            Route::controller('project', ProjectController::class);
        });
        
        
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', HomepageController::class);
    });
    
    
});
