<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Model;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class PoolModel
 * @package App\Model\ImportPools
 */
abstract class PoolModel extends Model {
    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDDatabaseConnections::getPoolsConnection();
    }
}
