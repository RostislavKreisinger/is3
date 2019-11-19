<?php

namespace App\Model\ImportSupport;


use Illuminate\Database\Eloquent\Model;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ISModel
 * @package App\Model\ImportSupport
 */
abstract class ISModel extends Model {
    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDDatabaseConnections::getImportSupportConnection();
    }
}
