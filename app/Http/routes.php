<?php

Route::middleware('auth', App\Http\Middleware\Authenticate::class);
// Route::middleware('admin', App\Http\Middleware\Admin::class);

Route::get('route-list', function () {
    return Route::displayRoutes();
});

Route::group(['prefix' => 'open'], function () {
    Route::group(['prefix' => 'import-flow'], function () {
        Route::group(['prefix' => 'graph'], function () {
            Route::get('/queues-status', \App\Http\Controllers\Open\ImportFlow\Graph\QueuesStatusController::getMethodAction());
            Route::get('/queues-jobs-in-time', App\Http\Controllers\Open\ImportFlow\Graph\QueuesJobsInTimeController::getMethodAction());
            Route::get('/queues-jobs-in-time-history', App\Http\Controllers\Open\ImportFlow\Graph\QueuesJobsInTimeHistoryController::getMethodAction());
        });
        Route::group(['prefix' => 'table'], function () {
            Route::get('resources-in-error-state', 'App\Http\Controllers\Open\ImportFlow\Table\ResourcesInErrorStateController@index');
            Route::get('stuck-flows', 'App\Http\Controllers\Open\ImportFlow\Table\StuckFlowsController@index');
            Route::get('delayed-flows', 'App\Http\Controllers\Open\ImportFlow\Table\DelayedFlowsController@index');
            Route::get('active-flows', 'App\Http\Controllers\Open\ImportFlow\Table\ActiveFlowsController@index');
            Route::get('large-flows/{projectId?}/{resourceId?}', 'App\Http\Controllers\Open\ImportFlow\Table\LargeFlowsController@index');
            Route::get('resources', 'App\Http\Controllers\Open\ImportFlow\Table\ResourcesController@index');
            Route::get('eshop-types', 'App\Http\Controllers\Open\ImportFlow\Table\EshopTypesController@index');
            Route::get('broken-flows', 'App\Http\Controllers\Open\ImportFlow\Table\BrokenFlowsController@index');
            Route::get('tested-not-running-projects', 'App\Http\Controllers\Open\ImportFlow\Table\TestedNotRunningProjectsController@index');
        });
    });

    Route::group(['prefix' => 'monitoring'], function () {
        Route::group(['prefix' => 'onboarding'], function (){
            Route::group(['prefix' => '{platform}'], function (){
                Route::get('registration', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\RegistrationController::getMethodIndex());
                Route::get('registration/data', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\RegistrationController::getMethodData());
                Route::get('on-loading-page', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\OnLoadingPageController::getMethodIndex());
                Route::get('on-loading-page/data', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\OnLoadingPageController::getMethodData());

                Route::group(['prefix' => 'proccessed-order-count'], function (){
                    Route::get('data', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\ProccessedOrderCountController::getMethodAction("getData"));
                    Route::get('stats', \App\Http\Controllers\Open\Monitoring\Onboarding\Platform\ProccessedOrderCountController::getMethodAction("getStats"));
                });
            });
        });

        Route::group(['prefix' => 'pricing'], function (){
            Route::group(['prefix' => 'stream'], function (){
                Route::get('subscription-stream', \App\Http\Controllers\Open\Monitoring\Pricing\Stream\SubscriptionStreamController::getMethodAction());
            });
        });
    });
});

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'import-flow'], function () {
        Route::group(['prefix' => 'graphs'], function () {
            Route::get('/queues-status', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesStatusController::getMethodAction());
            Route::get('/queues-times-status', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesTimesStatusController::getMethodAction());
            Route::get('/queues-jobs-in-time', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesJobsInTimeController::getMethodAction());
            Route::get('/queues-jobs-in-time-history', App\Http\Controllers\Api\ImportFlow\Graphs\QueuesJobsInTimeHistoryController::getMethodAction());
        });
    });
});

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function () {
        Route::auth();
        Route::get('logout', 'Auth\LoginController@logout');
    });

    Route::group(['middleware' => 'auth'], function () {

        Route::group(['prefix' => 'api'], function () {
            Route::group(['prefix' => 'import-shutdown-log'], function () {
                Route::get('/', \App\Http\Controllers\Homepage\ImportShutdownLogController::getMethodAction());
                Route::get('{shutdownLogId}', \App\Http\Controllers\Homepage\ImportShutdownLogController::getMethodAction('destroy'));
            });
        });

        Route::group(['prefix' => 'button'], function () {
            Route::group(['prefix' => 'resource'], function () {
                Route::group(['prefix' => 'other'], function () {
                    Route::get('/clear-stack', App\Http\Controllers\Button\Resource\Other\ClearStackButtonController::getMethodAction());
                    Route::get('/unconnect', App\Http\Controllers\Button\Resource\Other\UnconnectButtonController::getMethodAction());
                    Route::get('/shift-next-check-date', App\Http\Controllers\Button\Resource\Other\ShiftNextCheckDateButtonController::getMethodAction());
                    Route::get('/update-orders', App\Http\Controllers\Button\Resource\Other\UpdateOrdersButtonController::getMethodAction());
                });
                Route::group(['prefix' => 'other'], function () {
                    Route::get('/error-send', App\Http\Controllers\Button\Resource\Error\SendErrorTestButtonController::getMethodAction());
                });

                Route::get('/b1-reset-automat-test', App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::getMethodAction());
                Route::get('/b5-reset-history', App\Http\Controllers\Button\Resource\B5_ResetHistoryButtonController::getMethodAction());
                Route::get('/b5-reactive-history', App\Http\Controllers\Button\Resource\B5_ReactivateHistoryButtonController::getMethodAction());
                Route::get('/b6-reset-daily', App\Http\Controllers\Button\Resource\B6_ResetDailyButtonController::getMethodAction());
            });
        });

        Route::group(['prefix' => 'currency'], function () {
            Route::get('/{currency_id}', App\Http\Controllers\Currency\DetailController::getMethodAction());
            Route::get('/', App\Http\Controllers\Currency\IndexController::getMethodAction());
            Route::post('/', App\Http\Controllers\Currency\IndexController::postMethodAction());
        });

        Route::get('error', \App\Http\Controllers\Error\ErrorController::getMethodAction());

        Route::group(['prefix' => 'storno-order-status'], function () {
            Route::get('/{status_id}', App\Http\Controllers\StornoOrderStatus\DetailController::getMethodAction());
            Route::get('/', App\Http\Controllers\StornoOrderStatus\IndexController::getMethodAction());
            Route::post('/', App\Http\Controllers\StornoOrderStatus\IndexController::postMethodAction());
        });

        Route::group(['prefix' => 'database'], function () {
            Route::get('/database-selector/{project_id?}/{resource_id?}', App\Http\Controllers\Database\DatabaseSelectorController::getMethodAction());
            Route::post('/database-selector/{project_id?}/{resource_id?}', App\Http\Controllers\Database\DatabaseSelectorController::postMethodAction());
            Route::get('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\ShowImportDataController::getMethodAction());
            Route::post('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\ShowImportDataController::postMethodAction());
        });

        Route::group(['prefix' => 'plugin'], function () {
            Route::group(['prefix' => 'import'], function () {
                Route::group(['prefix' => 'supervisor'], function () {
                    Route::get('/{supervisor_id}/', App\Http\Controllers\Plugin\Import\Supervisor\DetailController::getMethodAction());
                    Route::get('/', App\Http\Controllers\Plugin\Import\Supervisor\IndexController::getMethodAction());
                });
            });
        });

        Route::group(['prefix' => 'search'], function () {
            Route::get('user', \App\Http\Controllers\Search\UserController::getMethodAction());
            Route::get('client', \App\Http\Controllers\Search\ClientController::getMethodAction());
            Route::get('project', \App\Http\Controllers\Search\ProjectController::getMethodAction());
        });

        Route::group(['prefix' => 'project-list'], function () {
            Route::get('/resources/{resource_id?}', \App\Http\Controllers\ProjectList\ResourcesController::getMethodAction());
            Route::get('/eshops/{eshop_type_id?}', \App\Http\Controllers\ProjectList\EshopsController::getMethodAction());
        });

        Route::group(['prefix' => 'project'], function () {
            Route::group(['prefix' => '{project_id}'], function () {
                Route::group(['prefix' => 'resource'], function () {
                    Route::group(['prefix' => '{resource_id}'], function () {
                        Route::get('/daily-history', App\Http\Controllers\Project\Resource\ImportFlowStatusController::getMethodAction('getIndex'));
                        Route::group(['prefix' => 'importflowstatus'], function () {
                            Route::get('/', App\Http\Controllers\Project\Resource\ImportFlowStatusController::getMethodAction('getResourceInfo'));
                            Route::put('{unique}/raise_difficulty', 'App\Http\Controllers\Project\Resource\ImportFlowStatusController@raiseDifficulty');
                            Route::put('{unique}/reduce_difficulty', 'App\Http\Controllers\Project\Resource\ImportFlowStatusController@reduceDifficulty');
                        });
                        Route::group(['prefix' => 'pool'], function () {
                            Route::get("control", \App\Http\Controllers\Project\Resource\ImportFlowPoolController::getMethodAction('getControlPool'));
                            Route::post('generate-flows', '\App\Http\Controllers\Project\Resource\ImportFlowPoolController@generateFlows');
                        });
                        Route::group(['prefix' => 'debug', 'namespace' => 'App\Http\Controllers\Debug'], function () {
                            Route::group(['prefix' => 'presta'], function () {
                                Route::get('debug-data', 'PrestaDebugController@debugData');
                                Route::get('select-endpoint', 'PrestaDebugController@selectEndpoint');
                                Route::get('/', 'PrestaDebugController@testCall');
                            });
                            Route::group(['prefix' => 'differences'], function () {
                                Route::get('add', 'DifferencesController@add');
                                Route::get('activate', 'DifferencesController@activate');
                                Route::get('deactivate', 'DifferencesController@deactivate');
                                Route::get('restore', 'DifferencesController@restore');
                                Route::get('delete', 'DifferencesController@delete');
                                Route::get('/', 'DifferencesController@load');
                            });
                        });
                        Route::any('/', App\Http\Controllers\Project\Resource\DetailController::getMethodAction());
                    });

                    Route::get('/', \App\Http\Controllers\Project\IndexController::getMethodAction());
                });
                Route::get('/', App\Http\Controllers\Project\DetailController::getMethodAction());
            });
            Route::get('/', \App\Http\Controllers\Project\IndexController::getMethodAction());
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/{user_id}', App\Http\Controllers\User\DetailController::getMethodAction());
            Route::get('/', \App\Http\Controllers\User\IndexController::getMethodAction());
        });

        Route::group(['prefix' => 'admin'], function () {
            Route::group(['middleware' => 'admin'], function () {
                Route::group(['prefix' => 'user'], function () {
                    Route::get('/{user_id}', App\Http\Controllers\Admin\User\DetailController::getMethodAction());
                    Route::post('/{user_id}', App\Http\Controllers\Admin\User\DetailController::postMethodAction());
                    Route::get('/', App\Http\Controllers\Admin\User\IndexController::getMethodAction());
                });
            });
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', App\Http\Controllers\Admin\Profile\IndexController::getMethodAction());
                Route::post('/', App\Http\Controllers\Admin\Profile\IndexController::postMethodAction());
            });
            Route::group(['prefix' => 'error'], function () {
                Route::get('/create/{resource_id?}', App\Http\Controllers\Admin\Error\CreateController::getMethodAction());
                Route::post('/create/{resource_id?}', App\Http\Controllers\Admin\Error\CreateController::postMethodAction());
                Route::get('/{error_id}', App\Http\Controllers\Admin\Error\DetailController::getMethodAction());
                Route::post('/{error_id}', App\Http\Controllers\Admin\Error\DetailController::postMethodAction());
                Route::get('/', App\Http\Controllers\Admin\Error\IndexController::getMethodAction());
            });
            Route::get('/', App\Http\Controllers\Admin\IndexController::getMethodAction());
        });

        Route::group(['prefix' => 'order-alert'], function () {
            Route::get('/detail/{storeId}', \App\Http\Controllers\OrderAlert\DetailController::getMethodAction());
            Route::get('/', \App\Http\Controllers\OrderAlert\IndexController::getMethodAction());
        });

        Route::group(['prefix' => 'homepage'], function () {
            Route::get('importv2', \App\Http\Controllers\Homepage\Importv2Controller::getMethodAction());
            Route::get('import-flow', \App\Http\Controllers\Homepage\ImportFlowController::getMethodAction());
            Route::get('import-flow-stats', \App\Http\Controllers\Homepage\ImportFlowStatsController::getMethodAction());
            Route::get('if-control-pool', \App\Http\Controllers\Homepage\ImportFlowControlPoolController::getMethodAction());
            Route::get('resources', \App\Http\Controllers\Homepage\ResourcesController::getMethodAction());
            Route::get('large-flow', \App\Http\Controllers\Homepage\LargeFlowController::getMethodAction());
            Route::get('broken-flow', \App\Http\Controllers\Homepage\BrokenFlowController::getMethodAction());
            Route::get('tested-not-running-projects', \App\Http\Controllers\Homepage\TestedNotRunningProjectsController::getMethodAction());
        });

        // Route::get('/', HomepageController::routeMethod('index'));
        Route::get('/', \App\Http\Controllers\IndexController::getMethodAction());
    });


});


