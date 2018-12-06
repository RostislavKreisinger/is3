<?php

namespace App\Migration;


use Illuminate\Database\Connection;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class LogsMigration
 * @package App\Migration
 */
class LogsMigration extends AMigration {
    /**
     * @return Connection|LaravelMySqlConnection
     */
    protected function getSchemaConnection() {
        return MDDatabaseConnections::getLogsConnection();
    }
}