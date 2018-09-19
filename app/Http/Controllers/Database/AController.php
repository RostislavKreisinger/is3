<?php

namespace App\Http\Controllers\Database;


use App\Http\Controllers\BaseViewController;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;

class AController extends BaseViewController {


    /**
     * @param null $project_id
     * @param null $resource_id
     * @return \Illuminate\Database\Connection|\Monkey\Connections\Extension\LaravelMySqlConnection
     * @throws \Monkey\Connections\Exception\ConnectionsException
     */
    final protected function getConnection($project_id = null, $resource_id = null) {
        if($project_id !== null && $resource_id !== null) {
            $eshopDetail = MDDatabaseConnections::getMasterAppConnection()->table("resource_setting as rs")
                ->where("rs.project_id", '=', $project_id)
                ->where('rs.resource_id', '=', $resource_id)
                ->join("resource_eshop as re", 're.resource_setting_id', '=', 'rs.id')
                ->join("eshop_type as et", 'et.id', '=', 're.eshop_type_id')
                ->first(['et.*']);
            if(!empty($eshopDetail)){
                $dataStorageConnection = $eshopDetail->data_storage_connection;
                return MDDataStorageConnections::createDataStoreConnection($dataStorageConnection);
            }
        }
        return \DB::connection('mysql-select-import');
    }


}