<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 14. 3. 2019
 * Time: 12:46
 */

namespace App\Helpers\Monitoring\ImportFlow\Connection;


use Monkey\CacheV2\Interfaces\IStorage;
use Monkey\Connections\MDCacheConnections;

class MonitoringCacheConnection extends MDCacheConnections
{
    /**
     *
     * @return IStorage
     */
    public static function getMonitoringCacheConnection() {
        return self::createConnection(new MonitoringTwemproxyServer());
    }
}