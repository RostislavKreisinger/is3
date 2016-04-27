<?php

namespace App\Providers;

use DB;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(isset($_GET['dbdebug'])){
            DB::listen(function (QueryExecuted $query) {
                global $queryLog;
                $queryLog[] = $query;
                // $query->sql
                // $query->bindings
                // $query->time
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
