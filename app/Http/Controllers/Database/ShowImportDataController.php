<?php

namespace App\Http\Controllers\Database;

use DB;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Resource\ResourceList;
use Monkey\Resource\Table;
use Monkey\Resource\TableConfig\Column;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monkey\Vardump\VardumpQuery;
use View;

class ShowImportDataController extends AController {

    private $client_id = null;

    /**
     *
     * @var ResourceList 
     */
    private $resourceList = null;

    public function __construct() {
        parent::__construct();
    }

    public function getIndex($projectId, $resourceId, $table_id = null, $count = 100) {
        $error = null;
        $resource = MDDatabaseConnections::getMasterAppConnection()
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

    public function postIndex(Request $request) {
        return $this->getIndex($request->input("projectId"), $request->input("resourceId"), $request->input("tableId"), $request->input("count"));
    }

    function getTableName($tableName, $clientId) {
        if ($tableName[strlen($tableName) - 1] == '_') {
            return $tableName . $clientId;
        }
        return $tableName;
    }

    public function postCountImportData(Request $request) {
        return redirect()->action('ShowImportDataController@getIndex', [$request->input("projectId"), $request->input("resourceId"), $request->input("tableId"), $request->input("count")]);
    }

    private function getClientId($projectId) {
        $project = MDDatabaseConnections::getMasterAppConnection()
                ->table("project")
                ->select(["client.id as client_id"])
                ->leftJoin("client", "client.user_id", "=", "project.user_id")
                ->where("project.id", $projectId)
                ->first();
        return $this->client_id = $project->client_id;
    }

    private function getTables($resource_id) {
        return $this->resourceList->getResource($resource_id)->getTables();
    }

    /**
     * 
     * @param type $resource_id
     * @return Table
     */
    private function getDefaultTable($resource_id) {
        return $this->resourceList->getResource($resource_id)->getDefaultTable();
    }

    /**
     * 
     * @param type $resource_id
     * @return Table
     */
    private function getTable($resource_id, $table_id) {
        return $this->resourceList->getResource($resource_id)->getTable($table_id);
    }

    private function initResourceList($projectId) {
        $client_id = $this->getClientId($projectId);
        $this->resourceList = new ResourceList($client_id);
    }

}
