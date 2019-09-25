<?php

namespace App\Model\Logs;


use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class Log
 * @package App\Model\Logs
 */
abstract class Log extends Model {
    /**
     * Get the database connection for the model.
     *
     * @return Connection|LaravelMySqlConnection
     */
    public function getConnection() {
        return MDDatabaseConnections::getLogsConnection();
    }
}
