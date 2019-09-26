<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 18. 3. 2019
 * Time: 8:03
 */

namespace App\Migration;


use Illuminate\Database\Connection;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

class ImportSupportMigration extends AMigration
{
    /**
     * @return Connection|LaravelMySqlConnection
     */
    protected function getSchemaConnection() {
        return MDDatabaseConnections::getImportSupportConnection();
    }
}
