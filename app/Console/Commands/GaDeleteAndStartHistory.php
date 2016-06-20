<?php

namespace App\Console\Commands;

use DB;
use Exception;
use Illuminate\Console\Command;
use Monkey\DateTime\DateTimeHelper;

class GaDeleteAndStartHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md:GaDeleteAndStartHistory';

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
        $value = $this->ask("What type of start \n"
                            . "1 - platici \n"
                            . "2 - free \n"
                            . "3 - other", 1);
        
        $query = DB::connection('mysql-master-app')
                ->table('resource_setting_v2 as rs')
                ->select('c.id as client_id', 'rs.project_id as project_id', 'p.user_id as user_id' )
                ->join('project as p', 'rs.project_id', '=', 'p.id')
                ->join('client as c', 'c.user_id', '=', 'p.user_id')
                ->where('rs.resource_id', '=', 9)
                ->where('rs.active', '=', 1)
                ->whereNotNull('tariff_id');
        
        switch ($value) {
            case 1: 
                $query->where('tariff_id', '>', 1001);
                break;
            case 2: 
                $query->where('tariff_id', '=', 1001);
                break;
            case 3: 
                $query->where('tariff_id', '=', 1000);
                break;
            default :
                $this->error('Wrong start type!');
                break;
        }
        $clients = array();
        foreach($query->get() as $row){
            if(!isset($clients[$row->client_id])){
                $clients[$row->client_id] = array();
            }
            $clients[$row->client_id][] = $row;
        }
        
        $tables = DB::connection('mysql-master-app')->select('SELECT * FROM resource_tables as rt WHERE rt.resource_id = 9 AND rt.actual = (SELECT MAX(rt1.actual) FROM resource_tables as rt1 WHERE rt1.resource_id = rt.resource_id)');
        
        $date_to = DateTimeHelper::getCloneSelf()->mysqlFormatDate();
        $date_from = DateTimeHelper::getCloneSelf()->changeYears(-2)->mysqlFormatDate();
        
        $count = count($clients);
        $current = 0;
        foreach($clients as $client_id => $client){
            $current++;
            $this->info("client $client_id : {$current} / {$count}");
            $clientTables = array();
            foreach ($tables as $table){
                $clientTables[] = str_replace('[[client_id]]', $client_id, $table->table_name);
            }            
            foreach($clientTables as $table){
                try{
                    DB::connection('mysql-import-dw')->table($table)->delete();
                }catch(Exception $e){}
            }
            foreach($client as $project){
                DB::connection('mysql-import-pools')
                        ->table('import_prepare_start')
                        ->where('resource_id', '=', 9)
                        ->where('project_id', '=', $project->project_id)
                        ->update(['active' => 1, 'date_from' => $date_from, 'date_to' => $date_to, 'ttl' => 5]);
            }
        }
    }
}
