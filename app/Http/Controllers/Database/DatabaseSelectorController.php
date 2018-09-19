<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\BaseViewController;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Constants\MonkeyData\Resource\Resource;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Resource\ResourceList;
use Monkey\Resource\Table;
use Monkey\Resource\TableConfig\Column;
use Monkey\Vardump\VardumpQuery;
use Monkey\View\View;

class DatabaseSelectorController extends AController {

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


        $defaultTable = null;
        if ($project_id !== null) {
            $queryBuilder = MDDatabaseConnections::getMasterAppConnection()->table('monkeydata.project as p')
                    ->where('p.id', '=', $project_id)
                    ->join('monkeydata.client as c', 'c.user_id', '=', 'p.user_id')
                    ->select(array('p.user_id as user_id', 'p.id as project_id', 'c.id as client_id'))
            ;
            $info = $queryBuilder->first();
            $view->addParameter('info', $info);


            if ($resource_id !== null) {
                $resourceList = new ResourceList($this->getClientId($project_id));
                $tableSelect = array();
                $tables = $resourceList->getResource($resource_id)->getTables();
                foreach ($tables as $table) {
                    $snippets[] = new Snippet("`{$table->getQueryName()}`", $table->getDbTableName());

                    $dateFrom = Input::get('date_from', null);
                    $dateTo = Input::get('date_to', null);
                    $table->query = $this->getSelect($table, $project_id, $resource_id, 100, $dateFrom, $dateTo);
                    $tableSelect[] = $table;
                    if ($defaultTable === null) {
                        $defaultTable = $table;
                    }
                }
//                vd($tables);

                $this->addCustomTableSelects($project_id, $resource_id, $info, $tableSelect);
                //vde($tableSelect);
                $view->addParameter('tables', $tables);
                $view->addParameter('tableSelect', $tableSelect);
            }
        }
        if (count($snippets)) {
            // $snippetsString = "'".implode("', '", $snippets)."'";
            $view->addParameter('snippets', json_encode($snippets));
        }


        $query = Input::get('query');

        if ($query === null && $defaultTable !== null) {
            $query = $defaultTable->query[1];
        }

        $vd = new VardumpQuery();
        $vd->setShowBacktrace(false);
        $vd->setEndOfLine("\n");
        $view->addParameter('query', $vd->formatSqlQuery($query));
    }

    private function addCustomTableSelects($project_id, $resource_id, $info, &$tableSelect){
        switch ($resource_id){
            case 4:

                /*
                select DATE_FORMAT(`date_id`,'%Y %M'), count(id)
                    from `monkeydata_import_dw`.`f_eshop_order_3494`
                    where row_status  < 100
                        and date_id >= 20160501
                        group by DATE_FORMAT(`date_id`,'%Y %M')
                    order by `date_id`

                */
                $table = new Table("f_eshop_order_[[client_id]]", $info->client_id);
                $table->setDatabase("monkeydata_import_dw");

                $dateFrom = DateTimeHelper::getInstance()->changeYears(-1)->getMySqlId();
                $builder = $this->getConnection($project_id, $resource_id)
                    ->table($table->getQueryName())
                    ->selectRaw("DATE_FORMAT(`date_id`,\'%Y %M\') as `MONTH`, count(id) as `Order_Count`")
                    ->whereRaw("row_status < 100")
                    ->whereRaw("date_id >= {$dateFrom}")
                    ->groupBy(DB::raw("DATE_FORMAT(`date_id`,\'%Y %M\')"))
                    ->orderBy("date_id");
                $table->setQuery($this->getQueryFromBuilder($builder));
                $table->setName("User orders");
                $tableSelect[] = $table;
                break;
        }
    }




    public function postIndex($project_id = null, $resource_id = null) {
        $connection = $this->getConnection($project_id, $resource_id);
        $view = new View("default.database.output");
        try {
            $query = Input::get('query', false);
            if (empty($query)) {
                return $view;
            }
            $data = $connection->select(DB::raw($query));
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
        $project = DB::connection("mysql-select-app")
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

    private function getTable($resource_id, $table_id) {
        return $this->resourceList->getResource($resource_id)->getTable($table_id);
    }

    private function getTables($resource_id) {
        return $this->resourceList->getResource($resource_id)->getTables();
    }

    private function getSelect($table, $projectId, $resource_id = null, $count = 100, $dateFrom = null, $dateTo = null) {
        $connection = $connection = $this->getConnection($projectId, $resource_id);

        $builder = $connection->table($table->getQueryName());

        $table->getTableConfig()->addDefaultColumn((new Column())->setName('date_id')->setOrderBy('desc'));
        $table->getTableConfig()->addDefaultColumn((new Column())->setName('project_id')->setWhere(" = {$projectId} "));
        $table->getTableConfig()->addDefaultColumn((new Column())->setName('row_status')->setWhere(" < 100"));

        if($dateFrom) {
            $table->getTableConfig()->addColumn((new Column())->setName('date_id')->setRawName("DATE_FORMAT(date_id,'%Y-%m-%d')")->setWhere(" >= DATE_FORMAT('{$dateFrom}', '%Y-%m-%d')"));

            if($dateTo) {
                $table->getTableConfig()->addColumn((new Column())->setName('date_id')->setRawName("DATE_FORMAT(date_id,'%Y-%m-%d')")->setWhere(" <= DATE_FORMAT('{$dateTo}', '%Y-%m-%d')"));
            }
        }

        foreach ($table->getTableConfig()->getColumns() as $column) {
            if ($table->hasDbColumn($column->getName(), $connection)) {
                $column->updateQueryBuilder($builder);
            }
        }

        $builder->limit($count);

        return $this->getQueryFromBuilder($builder);
    }

    private function getQueryFromBuilder($builder){
        $query = (new VardumpQuery())->getFinalQuery($builder);

        $queryRaw = $builder->toSql();
        foreach ($builder->getBindings() as $value) {
            if (is_string($value)) {
                $value = "'{$value}'";
            }
            $queryRaw = preg_replace('/\?/', $value, $query, 1);
        }
        $query = str_replace('<br>', '\n', $query);
        return [$query, $queryRaw];
    }

    private function getResourceTables($projectId, $resourceId, $table_id = 1, $count = 100) {
        $resource = DB::connection("mysql-select-app")
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



        $connection = $this->getConnection($projectId, $resourceId);
        $builder = $connection->table($table->getQueryName());

        $table->getTableConfig()->addDefaultColumn((new Column())->setName('date_id')->setOrderBy('desc'));
        $table->getTableConfig()->addDefaultColumn((new Column())->setName('project_id')->setWhere(" = {$projectId} "));
        $table->getTableConfig()->addDefaultColumn((new Column())->setName('row_status')->setWhere(" < 100"));


        foreach ($table->getTableConfig()->getColumns() as $column) {
            if ($table->hasDbColumn($column->getName(), $connection)) {
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



        $importData = [
            "tables" => $this->getTables($resourceId),
            "project_id" => $projectId,
            "resource_id" => $resourceId,
            "table_id" => $table_id,
            "count" => $count,
            "query" => $query,
            "queryRaw" => $queryRaw
        ];


        return $importData;
    }

}
