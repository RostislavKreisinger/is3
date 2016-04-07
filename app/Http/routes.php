<?php



Route::middleware('auth', App\Http\Middleware\Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        
        
        Route::group(['prefix' => 'search'], function(){
            Route::controller('user', \App\Http\Controllers\Search\UserController::class);
            // Route::controller('client', \App\Http\Controllers\Search\ClientController::class);
            Route::controller('project', \App\Http\Controllers\Search\ProjectController::class);
        });
        
        Route::group(['prefix' => 'project'], function(){
            Route::controller('/{project_id}', App\Http\Controllers\Project\DetailController::class);
            Route::controller('/', \App\Http\Controllers\Project\ListController::class);
        });
        
        Route::group(['prefix' => 'user'], function(){
            Route::controller('/{user_id}', App\Http\Controllers\User\DetailController::class);
            Route::controller('/', \App\Http\Controllers\User\ListController::class);
        });
        
        
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', \App\Http\Controllers\HomepageController::class);
    });
    
    
});
