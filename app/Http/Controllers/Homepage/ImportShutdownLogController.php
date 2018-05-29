<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ImportShutdownLogController
 * @package App\Http\Controllers\Homepage
 */
class ImportShutdownLogController extends Controller {
    const SHUTDOWN_LOG_TABLE = 'if_shutdown_log';

    /**
     * @var LaravelMySqlConnection $logsConnection
     */
    private $logsConnection;

    /**
     * @return array|static[]
     */
    public function getIndex() {
        if ($this->getLogsConnection()->getSchemaBuilder()->hasTable(self::SHUTDOWN_LOG_TABLE)) {
            $logs = $this->getLogsConnection()->table(self::SHUTDOWN_LOG_TABLE)
                ->orderBy('datetime', 'desc')
                ->get();

            for ($i = 0; $i < count($logs); $i++) {
                $logs[$i]->project_url = action(DetailController::routeMethod('getIndex'), [
                    'project_id' => $logs[$i]->project_id
                ]);
                $logs[$i]->resource_url = action(ResourceDetailController::routeMethod('getIndex'), [
                    'project_id' => $logs[$i]->project_id,
                    'resource_id' => $logs[$i]->resource_id
                ]);
            }

            return $logs;
        }
    }

    /**
     * @return LaravelMySqlConnection
     */
    public function getLogsConnection(): LaravelMySqlConnection {
        if (is_null($this->logsConnection)) {
            $this->logsConnection = MDDatabaseConnections::getLogsConnection();
        }

        return $this->logsConnection;
    }
}