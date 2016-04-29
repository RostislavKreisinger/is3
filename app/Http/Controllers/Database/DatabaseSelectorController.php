<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\BaseViewController;
use DB;
use Exception;
use Helpers\Resource\ResourceList;
use Input;
use Monkey\Vardump\VardumpQuery;
use View;

class DatabaseSelectorController extends BaseViewController {

    public function __construct() {
        parent::__construct();
    }

    public function getIndex($project_id = null, $resource_id = null) {
        /*
        $snippets = array(
            new Snippet('`monkeydata`', 'monkeydata'),
            new Snippet('`monkeydata_import`', 'monkeydata_import'),
            new Snippet('`monkeydata_import_dw`', 'monkeydata_import_dw'),
            new Snippet('`monkeydata_import_anal`', 'monkeydata_import_anal'),
            new Snippet('`monkeydata_pools`', 'monkeydata_pools')
            );
        $view = View::make("layout.default.database.database_selector.index");
        $query = Input::get('query');
        $vd = new VardumpQuery();
        $vd->setShowBacktrace(false);
        $vd->setEndOfLine('&#13;&#10;');
        $view->with('query', $vd->formatSqlQuery($query));
        if ($project_id !== null) {
            $queryBuilder = DB::connection('data_import_selector')->table('monkeydata.project as p')
                    ->where('p.id', '=', $project_id)
                    ->join('monkeydata.client as c', 'c.user_id', '=', 'p.user_id')
                    ->select(array('p.user_id as user_id', 'p.id as project_id', 'c.id as client_id'))
            ;
            $info = $queryBuilder->first();
            $view->with('info', $info);
            
            if($resource_id !== null){
                $resourceList = new ResourceList($this->getClientId($project_id));
                $tables = $resourceList->getResource($resource_id)->getTables();
                foreach ($tables as $table){
                    $snippets[] = new Snippet("`{$table->getQueryName()}`", $table->getDbTableName());
                }
                $view->with('tables', $tables);
            }
        }
        if(count($snippets)){
            // $snippetsString = "'".implode("', '", $snippets)."'";
            $view->with('snippets', json_encode($snippets));
        }
        return $view;
         * 
         */
    }

    public function postIndex() {
        $view = View::make("layout.default.database.database_selector.output");

        try {
            $query = Input::get('query', false);
            if(empty($query)){
                return $view->render();
            }
            $data = DB::connection('data_import_selector')->select(DB::raw($query));
            $view->with('data', $data);
        } catch (Exception $e) {
            $view->with('error', $e);
        }

        return $view->render();
    }

    private function initResourceList($projectId) {
        $client_id = $this->getClientId($projectId);
        $this->resourceList = new ResourceList($client_id);
    }
    
     private function getClientId($projectId) {
        $project = DB::connection("data")
                ->table("project")
                ->select(["client.id as client_id"])
                ->leftJoin("client", "client.user_id", "=", "project.user_id")
                ->where("project.id", $projectId)
                ->first();
        
        return  $project->client_id;
    }
}


