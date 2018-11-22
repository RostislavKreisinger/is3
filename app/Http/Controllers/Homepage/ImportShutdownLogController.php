<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use App\Model\ShutdownLog;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ImportShutdownLogController
 * @package App\Http\Controllers\Homepage
 */
class ImportShutdownLogController extends Controller {
    /**
     * @return Collection|ShutdownLog[]
     */
    public function getIndex() {
        $logs = ShutdownLog::query()
            ->orderBy('datetime', 'desc')
            ->get();

        $logs->map(function (ShutdownLog $log) {
            $log->project_url = action(DetailController::routeMethod('getIndex'), [
                'project_id' => $log->project_id
            ]);
            $log->resource_url = action(ResourceDetailController::routeMethod('getIndex'), [
                'project_id' => $log->project_id,
                'resource_id' => $log->resource_id
            ]);
        });

        return $logs;
    }
}