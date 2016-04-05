<?php



Route::middleware('auth', App\Http\Middleware\Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        
        
        Route::group(['prefix' => 'search'], function(){
            Route::controller('user', \App\Http\Controllers\Search\UserController::class);
            Route::controller('client', \App\Http\Controllers\Search\ClientController::class);
            Route::controller('project', \App\Http\Controllers\Search\ProjectController::class);
        });
        
        //Route::group(['prefix' => 'project'], function(){
            Route::resource('project', \App\Http\Controllers\ProjectController::class);
        //});
        
        
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', \App\Http\Controllers\HomepageController::class);
    });
    
    
});
