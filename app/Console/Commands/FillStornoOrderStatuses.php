<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillStornoOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md:fillStornoOrderStatuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = \DB::connection('mysql-select-import')->select("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'monkeydata_import_dw' AND TABLE_NAME LIKE 'd_eshop_catalog_%'");
        $this->info('Tables: '.count($result));
        $added_statuses = 0;
        foreach($result as $table){
            $statuses = \DB::connection('mysql-import-dw')->table($table->TABLE_NAME)->where('catalog_type', '=', 'SO')->whereRaw('category_level & 1 = 1')->get();
            foreach($statuses as $status){
                $storno = \DB::connection('mysql-import-pools')->table('storno_order_status')->where('name', '=', $status->value)->count();
                if($storno == 0){
                    \DB::connection('mysql-import-pools')->table('storno_order_status')->insert(array('name' => $status->value));
                    $added_statuses++;
                }
            }
        }
        $this->info('added statuses: '.$added_statuses);
        
        
    }
}
