<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 14. 3. 2019
 * Time: 7:24
 */

namespace App\Helpers\Monitoring\ImportFlow\Connection;


use Monkey\Connections\Server\Database\AMySQLServer;

class MyTestServer extends AMySQLServer
{
    protected function fetchPassword() {
        return "";
    }

    protected function fetchSchemaName() {
        return "md_import_flow_test2";
    }

    protected function fetchUsername() {
        return "root";
    }
}