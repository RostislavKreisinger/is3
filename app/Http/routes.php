<?php



Route::middleware('auth', App\Http\Middleware\Authenticate::class);

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function(){
        Route::auth();
    });
    
    Route::group(['middleware' => 'auth'], function (){
        
        Route::group(['prefix' => 'button'], function(){
//            Route::get('/{arg0?}/{arg1?}/{arg2?}/{arg3?}/{arg4?}/{arg5?}/{arg6?}', function(){
//                $class = App\Http\Controllers\Button\Controller::getButtonClass(func_get_args());
//                return $class->getIndex();
//            });
            
            Route::group(['prefix' => 'resource'], function(){
                Route::controller('/b1-reset-automat-test', App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::class);
            });
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
