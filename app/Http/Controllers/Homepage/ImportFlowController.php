<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;
use Request;

/**
 * Class ImportFlowController
 * @package App\Http\Controllers\Homepage
 * @author Tomas
 */
class ImportFlowController extends BaseController {
    const LIMIT = 10;
    const SHUTDOWN_LOG_TABLE = 'if_shutdown_log';

    /**
     * @var LaravelMySqlConnection $logsConnection
     */
    private $logsConnection;

    public function getIndex() {
        $currentPage = Request::get('page') ?? 1;

        if (!is_int($currentPage)) {
            $currentPage = intval($currentPage);
        }

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $logs = $this->getLogsConnection()->table(self::SHUTDOWN_LOG_TABLE)
            ->orderBy('datetime', 'desc')
            ->offset(self::LIMIT * ($currentPage - 1))
            ->limit(self::LIMIT)
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

        $this->getView()->addParameter('logs', $logs);

        $count = $this->getLogsConnection()->table(self::SHUTDOWN_LOG_TABLE)->count();
        $pages = 5;//ceil($count / self::LIMIT);
        $this->getView()->addParameter('pages', $pages);
        $this->getView()->addParameter('currentPage', $currentPage);
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