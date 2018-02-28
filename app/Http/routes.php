<?php


Route::middleware('auth', App\Http\Middleware\Authenticate::class);
Route::middleware('admin', App\Http\Middleware\Admin::class);



Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function(){
        Route::auth();
    });
    
    Route::controller('test', App\Http\Controllers\Test\TestController::class);
    
    
    Route::group(['middleware' => 'auth'], function (){
        Route::group(['prefix' => 'api'], function(){
            Route::group(['prefix' => 'import-flow'], function(){
                Route::group(['prefix' => 'graphs'], function(){
                    Route::controller('/queues-status', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesStatusController::class);
                    Route::controller('/queues-times-status', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesTimesStatusController::class);
                    Route::controller('/queues-jobs-in-time', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesJobsInTimeController::class);
                    Route::controller('/queues-jobs-in-time-history', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesJobsInTimeHistoryController::class);
                });
            });
        });

        Route::group(['prefix' => 'button'], function(){
            Route::group(['prefix' => 'project'], function(){
                Route::group(['prefix' => 'autoreport'], function(){
                    Route::controller('/add-autoreport-record', App\Http\Controllers\Button\Project\Autoreport\AddAutoreportRecordButtonController::class);
                    Route::controller('/activate-autoreport-record', App\Http\Controllers\Button\Project\Autoreport\ActivateAutoreportRecordButtonController::class);
                });
            });
            Route::group(['prefix' => 'resource'], function(){
                Route::group(['prefix' => 'other'], function(){
                    Route::controller('/clear-stack', App\Http\Controllers\Button\Resource\Other\ClearStackButtonController::class);
                    Route::controller('/unconnect', App\Http\Controllers\Button\Resource\Other\UnconnectButtonController::class);
                    Route::controller('/shift-next-check-date', App\Http\Controllers\Button\Resource\Other\ShiftNextCheckDateButtonController::class);
                    Route::controller('/update-orders', App\Http\Controllers\Button\Resource\Other\UpdateOrdersButtonController::class);
                });
                Route::group(['prefix' => 'other'], function(){
                    Route::controller('/error-send', App\Http\Controllers\Button\Resource\Error\SendErrorTestButtonController::class);
                });
                
                Route::controller('/b1-reset-automat-test', App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::class);
                Route::controller('/b5-reset-history', App\Http\Controllers\Button\Resource\B5_ResetHistoryButtonController::class);
                Route::controller('/b5-reactive-history', App\Http\Controllers\Button\Resource\B5_ReactivateHistoryButtonController::class);
                Route::controller('/b6-reset-daily', App\Http\Controllers\Button\Resource\B6_ResetDailyButtonController::class);
            });
        });
        
        Route::group(['prefix' => 'currency'], function(){
            Route::controller('/{currency_id}', App\Http\Controllers\Currency\DetailController::class);
            Route::controller('/', App\Http\Controllers\Currency\IndexController::class);
        });
        
        Route::group(['prefix' => 'storno-order-status'], function(){
            Route::controller('/{status_id}', App\Http\Controllers\StornoOrderStatus\DetailController::class);
            Route::controller('/', App\Http\Controllers\StornoOrderStatus\IndexController::class);
        });
        
        Route::group(['prefix' => 'database'], function(){
            Route::controller('/database-selector/{project_id?}/{resource_id?}', App\Http\Controllers\Database\DatabaseSelectorController::class);
            Route::controller('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\ShowImportDataController::class);
        });
        
        Route::group(['prefix' => 'plugin'], function(){
            Route::group(['prefix' => 'import'], function(){
                Route::group(['prefix' => 'supervisor'], function(){
                    Route::controller('/{supervisor_id}/', App\Http\Controllers\Plugin\Import\Supervisor\DetailController::class);
                    Route::controller('/', App\Http\Controllers\Plugin\Import\Supervisor\IndexController::class);
                });
            });
        });
        
        Route::group(['prefix' => 'search'], function(){
            Route::controller('user', \App\Http\Controllers\Search\UserController::class);
            Route::controller('client', \App\Http\Controllers\Search\ClientController::class);
            Route::controller('project', \App\Http\Controllers\Search\ProjectController::class);
        });

        Route::group(['prefix' => 'project-list'], function(){
            Route::controller('/resources/{resource_id?}', \App\Http\Controllers\ProjectList\ResourcesController::class);
            Route::controller('/eshops/{eshop_type_id?}', \App\Http\Controllers\ProjectList\EshopsController::class);
        });
        
        Route::group(['prefix' => 'project'], function(){
            Route::group(['prefix' => '{project_id}'], function(){
                Route::group(['prefix' => 'resource'], function(){
                    Route::group(['prefix' => '{resource_id}'], function() {
                        Route::get('/daily-history', App\Http\Controllers\Project\Resource\ImportFlowStatusController::getMethodAction('getIndex'));
                        Route::get('/importflowstatus', App\Http\Controllers\Project\Resource\ImportFlowStatusController::getMethodAction('getResourceInfo'));

                        Route::group(['prefix' => 'pool'], function() {
                            Route::get("control",  \App\Http\Controllers\Project\Resource\ImportFlowPoolController::getMethodAction('getControlPool'));
                        });
                        Route::group(['prefix' =>'debug', 'namespace' => 'App\Http\Controllers\Debug'], function () {
                            Route::group(['prefix' =>'presta'], function () {
                                Route::get('debug-data', 'PrestaDebugController@debugData');
                                Route::get('select-endpoint', 'PrestaDebugController@selectEndpoint');
                                Route::get('/', 'PrestaDebugController@testCall');
                            });
                            Route::group(['prefix' => 'differences'], function () {
                                Route::get('add', 'DifferencesController@add');
                                Route::get('activate', 'DifferencesController@activate');
                                Route::get('deactivate', 'DifferencesController@deactivate');
                                Route::get('delete', 'DifferencesController@delete');
                                Route::get('/', 'DifferencesController@load');
                            });
                        });
                        Route::controller('/', App\Http\Controllers\Project\Resource\DetailController::class);
                    });

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
        
        Route::group(['prefix' => 'admin'], function(){
            Route::group(['middleware' => 'admin'], function(){
                Route::group(['prefix' => 'user'], function(){
                    Route::controller('/{user_id}', App\Http\Controllers\Admin\User\DetailController::class);
                    Route::controller('/', App\Http\Controllers\Admin\User\IndexController::class);
                });
            });
            Route::group(['prefix' => 'profile'], function(){
                Route::controller('/', App\Http\Controllers\Admin\Profile\IndexController::class);
            });
            Route::group(['prefix' => 'error'], function(){
                Route::controller('/create/{resource_id?}', App\Http\Controllers\Admin\Error\CreateController::class);
                Route::controller('/{error_id}', App\Http\Controllers\Admin\Error\DetailController::class);
                Route::controller('/', App\Http\Controllers\Admin\Error\IndexController::class);
            });
            Route::controller('/', App\Http\Controllers\Admin\IndexController::class);
        });

        Route::group(['prefix' => 'order-alert'], function(){
            Route::controller('/detail/{storeId}', \App\Http\Controllers\OrderAlert\DetailController::class);
            Route::controller('/', \App\Http\Controllers\OrderAlert\IndexController::class);
        });

        Route::group(['prefix' => 'homepage'], function(){
            Route::controller('importv2', \App\Http\Controllers\Homepage\Importv2Controller::class);
            Route::controller('import-flow', \App\Http\Controllers\Homepage\ImportFlowController::class);
            Route::controller('import-flow-stats', \App\Http\Controllers\Homepage\ImportFlowStatsController::class);
        });
        
        // Route::get('/', HomepageController::routeMethod('index'));
        Route::controller('/', \App\Http\Controllers\IndexController::class);
    });
    
    
});
