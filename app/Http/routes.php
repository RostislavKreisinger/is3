<?php

use App\Http\Controllers\Error\ErrorController;
use App\Http\Controllers\Homepage\BrokenFlowController;
use App\Http\Controllers\Homepage\ImportFlowController;
use App\Http\Controllers\Homepage\ImportFlowControlPoolController;
use App\Http\Controllers\Homepage\ImportFlowStatsController;
use App\Http\Controllers\Homepage\ImportShutdownLogController;
use App\Http\Controllers\Homepage\LargeFlowController;
use App\Http\Controllers\Homepage\RepairLogsController;
use App\Http\Controllers\Homepage\ResourcesController;
use App\Http\Controllers\Homepage\TestedNotRunningProjectsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Open\ImportFlow\Graph\QueuesStatusController;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\OnLoadingPageController;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\ProccessedOrderCountController;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\RegistrationController;
use App\Http\Controllers\Open\Monitoring\OrderAlert\Webhook\WebhookQueueStatsController;
use App\Http\Controllers\Open\Monitoring\Pricing\Stream\SubscriptionStreamController;
use App\Http\Controllers\OrderAlert\DetailController;
use App\Http\Controllers\Project\Resource\ImportFlowPoolController;
use App\Http\Controllers\ProjectList\EshopsController;
use App\Http\Controllers\Search\ClientController;
use App\Http\Controllers\Search\ProjectController;
use App\Http\Controllers\Search\UserController;

Route::middleware('auth', App\Http\Middleware\Authenticate::class);
// Route::middleware('admin', App\Http\Middleware\Admin::class);

Route::get('route-list', function () {
    return Route::displayRoutes();
});

Route::group(['prefix' => 'open'], function () {
    Route::group(['prefix' => 'import-flow'], function () {
        Route::group(['prefix' => 'graph'], function () {
            Route::get('/queues-status', QueuesStatusController::getMethodAction());
            Route::get('/queues-jobs-in-time', App\Http\Controllers\Open\ImportFlow\Graph\QueuesJobsInTimeController::getMethodAction());
            Route::get('/queues-jobs-in-time-history', App\Http\Controllers\Open\ImportFlow\Graph\QueuesJobsInTimeHistoryController::getMethodAction());
        });
        Route::group(['namespace' => 'App\Http\Controllers\Open\ImportFlow\Table', 'prefix' => 'table'], function () {
            Route::get('resources-in-error-state', 'ResourcesInErrorStateController@index');
            Route::get('stuck-flows', 'StuckFlowsController@index');
            Route::get('delayed-flows', 'DelayedFlowsController@index');
            Route::get('active-flows', 'ActiveFlowsController@index');
            Route::get('large-flows/{projectId?}/{resourceId?}', 'LargeFlowsController@index');
            Route::get('resources', 'ResourcesController@index');
            Route::get('eshop-types', 'EshopTypesController@index');
            Route::get('broken-flows', 'BrokenFlowsController@index');
            Route::get('tested-not-running-projects', 'TestedNotRunningProjectsController@index');
            Route::get('repair-logs', 'RepairLogsController@index');
        });
    });

    Route::group(['prefix' => 'monitoring'], function () {
        Route::group(['prefix' => 'onboarding'], function (){
            Route::group(['prefix' => '{platform}'], function (){
                Route::get('registration', RegistrationController::getMethodIndex());
                Route::get('registration/data', RegistrationController::getMethodData());
                Route::get('on-loading-page', OnLoadingPageController::getMethodIndex());
                Route::get('on-loading-page/data', OnLoadingPageController::getMethodData());

                Route::group(['prefix' => 'proccessed-order-count'], function (){
                    Route::get('data', ProccessedOrderCountController::getMethodAction("getData"));
                    Route::get('stats', ProccessedOrderCountController::getMethodAction("getStats"));
                });
            });
        });

        Route::group(['prefix' => 'pricing'], function (){
            Route::group(['prefix' => 'stream'], function (){
                Route::get('subscription-stream', SubscriptionStreamController::getMethodAction());
            });
        });

        Route::group(['prefix' => 'order-alert'], function(){
            Route::group(['prefix' => 'webhook'], function(){
                Route::get('webhook-queue-stats', WebhookQueueStatsController::getMethodAction());
                Route::get('webhook-queue-stats/data', WebhookQueueStatsController::getMethodData());
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
                Route::get('/', ImportShutdownLogController::getMethodAction());
                Route::get('{shutdownLogId}', ImportShutdownLogController::getMethodAction('destroy'));
            });
        });

        Route::group(['prefix' => 'button'], function () {
            Route::group(['prefix' => 'resource'], function () {
                Route::group(['prefix' => 'other'], function () {
                    Route::get('/unconnect', App\Http\Controllers\Button\Resource\Other\UnconnectButtonController::getMethodAction());
                    Route::get('/shift-next-check-date', App\Http\Controllers\Button\Resource\Other\ShiftNextCheckDateButtonController::getMethodAction());
                    Route::get('/update-orders', App\Http\Controllers\Button\Resource\Other\UpdateOrdersButtonController::getMethodAction());
                });
                Route::group(['prefix' => 'other'], function () {
                    Route::get('/error-send', App\Http\Controllers\Button\Resource\Error\SendErrorTestButtonController::getMethodAction());
                });

                Route::get('/b1-reset-automat-test', App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::getMethodAction());
                Route::get('/b6-reset-daily', App\Http\Controllers\Button\Resource\B6_ResetDailyButtonController::getMethodAction());
            });
        });

        Route::group(['prefix' => 'currency'], function () {
            Route::get('/{currency_id}', App\Http\Controllers\Currency\DetailController::getMethodAction());
            Route::get('/', App\Http\Controllers\Currency\IndexController::getMethodAction());
            Route::post('/', App\Http\Controllers\Currency\IndexController::postMethodAction());
        });

        Route::get('error', ErrorController::getMethodAction());

        Route::group(['prefix' => 'storno-order-status'], function () {
            Route::get('/{status_id}', App\Http\Controllers\StornoOrderStatus\DetailController::getMethodAction());
            Route::get('/', App\Http\Controllers\StornoOrderStatus\IndexController::getMethodAction());
            Route::post('/', App\Http\Controllers\StornoOrderStatus\IndexController::postMethodAction());
        });

        Route::group(['prefix' => 'database'], function () {
            Route::group(['prefix' => 'universal'], function () {
                Route::get('/database-selector/{database?}', App\Http\Controllers\Database\Universal\DatabaseSelectorController::getMethodAction());
                Route::post('/database-selector/{database?}', App\Http\Controllers\Database\Universal\DatabaseSelectorController::postMethodAction());
//                Route::get('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\Universal\ShowImportDataController::getMethodAction());
//                Route::post('/show-import-data/{project_id}/{resource_id}/{table_id?}/{count?}', App\Http\Controllers\Database\Universal\ShowImportDataController::postMethodAction());
            });
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
            Route::get('user', UserController::getMethodAction());
            Route::get('client', ClientController::getMethodAction());
            Route::get('project', ProjectController::getMethodAction());
        });

        Route::group(['prefix' => 'project-list'], function () {
            Route::get('/resources/{resource_id?}', \App\Http\Controllers\ProjectList\ResourcesController::getMethodAction());
            Route::get('/eshops/{eshop_type_id?}', EshopsController::getMethodAction());
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
                            Route::get("control", ImportFlowPoolController::getMethodAction('getControlPool'));
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
            Route::get('/detail/{storeId}', DetailController::getMethodAction());
            Route::get('/', \App\Http\Controllers\OrderAlert\IndexController::getMethodAction());
        });

        Route::group(['prefix' => 'homepage'], function () {
            Route::get('import-flow', ImportFlowController::getMethodAction());
            Route::get('import-flow-stats', ImportFlowStatsController::getMethodAction());
            Route::get('if-control-pool', ImportFlowControlPoolController::getMethodAction());
            Route::get('resources', ResourcesController::getMethodAction());
            Route::get('large-flow', LargeFlowController::getMethodAction());
            Route::get('broken-flow', BrokenFlowController::getMethodAction());
            Route::get('tested-not-running-projects', TestedNotRunningProjectsController::getMethodAction());
            Route::get('repair-logs', RepairLogsController::getMethodAction());
        });

        // Route::get('/', HomepageController::routeMethod('index'));
        Route::get('/', IndexController::getMethodAction());
    });


});


