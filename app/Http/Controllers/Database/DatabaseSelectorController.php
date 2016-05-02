<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\BaseViewController;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;
use Monkey\Resource\ResourceList;
use Monkey\Vardump\VardumpQuery;
use Monkey\View\View;

class DatabaseSelectorController extends BaseViewController {
    
    /**
     *
     * @var ResourceList 
     */
    private $resourceList;

    public function __construct() {
        parent::__construct();
    }

    public function getIndex($project_id = null, $resource_id = null) {
        $view = $this->getView();

        $snippets = array(
            new Snippet('`monkeydata`', 'monkeydata'),
            new Snippet('`monkeydata_import`', 'monkeydata_import'),
            new Snippet('`monkeydata_import_dw`', 'monkeydata_import_dw'),
            new Snippet('`monkeydata_import_anal`', 'monkeydata_import_anal'),
            new Snippet('`monkeydata_pools`', 'monkeydata_pools')
        );

        // $view = View::make("layout.default.database.database_selector.index");
        $query = Input::get('query');
        $vd = new VardumpQuery();
        $vd->setShowBacktrace(false);
        $vd->setEndOfLine('&#13;&#10;');
        $view->addParameter('query', $vd->formatSqlQuery($query));

        if ($project_id !== null) {
            $queryBuilder = DB::connection('mysql-select')->table('monkeydata.project as p')
                    ->where('p.id', '=', $project_id)
                    ->join('monkeydata.client as c', 'c.user_id', '=', 'p.user_id')
                    ->select(array('p.user_id as user_id', 'p.id as project_id', 'c.id as client_id'))
            ;
            $info = $queryBuilder->first();
            $view->addParameter('info', $info);

            if ($resource_id !== null) {
                $resourceList = new ResourceList($this->getClientId($project_id));
                $tables = $resourceList->getResource($resource_id)->getTables();
                foreach ($tables as $table) {
                    $snippets[] = new Snippet("`{$table->getQueryName()}`", $table->getDbTableName());
                }
                $view->addParameter('tables', $tables);
            }
        }
        if (count($snippets)) {
            // $snippetsString = "'".implode("', '", $snippets)."'";
            $view->addParameter('snippets', json_encode($snippets));
        }/**/
    }

    public function postIndex() {
        $view = new View("default.database.output");
        try {
            $query = Input::get('query', false);
            if (empty($query)) {
                return $view->render();
            }
            $data = DB::connection('mysql-select')->select(DB::raw($query));
            $view->with('data', $data);
        } catch (Exception $e) {
            $view->with('error', $e);
        }
        return $view;
    }

    private function initResourceList($projectId) {
        $client_id = $this->getClientId($projectId);
        $this->resourceList = new ResourceList($client_id);
    }

    private function getClientId($projectId) {
        $project = DB::connection("mysql-select")
                ->table("project")
                ->select(["client.id as client_id"])
                ->leftJoin("client", "client.user_id", "=", "project.user_id")
                ->where("project.id", $projectId)
                ->first();

        return $project->client_id;
    }
    
    /**
     * 
     * @param type $resource_id
     * @return Table
     */
    private function getDefaultTable($resource_id) {
        return $this->resourceList->getResource($resource_id)->getDefaultTable();
    }

    private function getResourceTables() {
        $resource = DB::connection("mysql-select")
                ->table("resource")
                ->where("id", $resourceId)
                ->first();


        $this->initResourceList($projectId);
        $table = $this->getDefaultTable($resourceId);
        if (!empty($table_id)) {
            $table = $this->getTable($resourceId, $table_id);
        }
        if ($table == null) {
            vde("No tables for resource {$resourceId}");
        }



        try {
            $builder = DB::connection("mysql-select")
                    ->table($table->getQueryName());

            $table->getTableConfig()->addDefaultColumn((new Column())->setName('date_id')->setOrderBy('desc'));
            $table->getTableConfig()->addDefaultColumn((new Column())->setName('project_id')->setWhere(" = {$projectId} "));
            $table->getTableConfig()->addDefaultColumn((new Column())->setName('row_status')->setWhere(" < 100"));


            foreach ($table->getTableConfig()->getColumns() as $column) {
                if ($table->hasDbColumn($column->getName())) {
                    $column->updateQueryBuilder($builder);
                }
            }



            $builder->limit($count);
            $query = (new VardumpQuery())->getFinalQuery($builder);

            $queryRaw = $builder->toSql();
            foreach ($builder->getBindings() as $value) {
                if (is_string($value)) {
                    $value = "'{$value}'";
                }
                $queryRaw = preg_replace('/\?/', $value, $query, 1);
            }

            $importData = $builder->get();
        } catch (QueryException $e) {
            $error = $e;
            $importData = NULL;
        }


        $importData = [
            "importData" => $importData,
            "tables" => $this->getTables($resourceId),
            "project_id" => $projectId,
            "resource_id" => $resourceId,
            "table_id" => $table_id,
            "count" => $count,
            "error" => $error,
            "query" => $query,
            "queryRaw" => $queryRaw
        ];

        foreach ($importData as $name => $value) {
            $this->getView()->addParameter($name, $value);
        }

        vd($this->getView());
    }

}
