<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 14. 3. 2019
 * Time: 7:23
 */

namespace App\Helpers\Monitoring\ImportFlow\Connection;


use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDImportFlowConnections;

class MyConnections extends MDImportFlowConnections
{
    /**
     *
     * @return LaravelMySqlConnection
     */
    public static function getMyTestConnection() {
        return self::createConnection(new MyTestServer());
    }
}