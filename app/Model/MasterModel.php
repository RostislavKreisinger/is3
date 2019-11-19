<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class MasterModel
 * @package App\Model
 */
abstract class MasterModel extends Model {
    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDDatabaseConnections::getMasterAppConnection();
    }
}
