<?php



Route::middleware('auth', App\Http\Middleware\Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        Route::group(['prefix' => 'button'], function(){
            Route::group(['prefix' => 'resource'], function(){
                Route::group(['prefix' => 'other'], function(){
                    Route::controller('/unconnect', App\Http\Controllers\Button\Resource\Other\UnconnectButtonController::class);
                    Route::controller('/shift-next-check-date', App\Http\Controllers\Button\Resource\Other\ShiftNextCheckDateButtonController::class);
                });
                
                Route::controller('/b1-reset-automat-test', App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::class);
                Route::controller('/b5-reset-history', App\Http\Controllers\Button\Resource\B5_ResetHistoryButtonController::class);
                Route::controller('/b6-reset-daily', App\Http\Controllers\Button\Resource\B6_ResetDailyButtonController::class);
            });
        });
        
        Route::group(['prefix' => 'database'], function(){
            Route::controller('/database-selector/{project_id?}/{resource_id?}', App\Http\Controllers\Database\DatabaseSelectorController::class);
            Route::controller('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\ShowImportDataController::class);
        });
        
        Route::group(['prefix' => 'search'], function(){
            Route::controller('user', \App\Http\Controllers\Search\UserController::class);
            Route::controller('project', \App\Http\Controllers\Search\ProjectController::class);
        });
        
        Route::group(['prefix' => 'project'], function(){
            Route::group(['prefix' => '{project_id}'], function(){
                Route::group(['prefix' => 'resource'], function(){
                    Route::controller('/{resource_id}', App\Http\Controllers\Project\Resource\DetailController::class);
                    Route::controller('/', \App\Http\Controllers\Project\IndexController::class);
                });
                Route::controller('/', App\Http\Controllers\Project\DetailController::class);
            });
            Route::controller('/', \App\Http\Controllers\Project\IndexController::class);
        });
        
        Route::group(['prefix' => 'user'], function(){
            Route::controller('/{user_id}', App\Http\Controllers\User\DetailController::class);
            Route::controller('/', \App\Http\Controllers\User\IndexController::class);
        });
        
        
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', \App\Http\Controllers\IndexController::class);
    });
    
    
});
