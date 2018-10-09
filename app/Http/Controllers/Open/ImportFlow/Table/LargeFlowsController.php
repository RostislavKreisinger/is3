<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\LargeImportLog;
use Illuminate\Http\Request;
use Monkey\DateTime\DateTimeHelper;

/**
 * Class LargeFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class LargeFlowsController extends AFlowsController {
    /**
     * @param Request $request
     * @param int|null $projectId
     * @param int|null $resourceId
     * @return array|\Illuminate\Database\Query\Builder[]
     */
    public function index(Request $request, int $projectId = null, int $resourceId = null) {
        $dateFrom = $request->input(
            'date_from',
            DateTimeHelper::getCloneSelf('NOW', 'UTC')->minusDays(30)->mysqlFormatDate()
        );
        $dateTo = $request->input(
            'date_to',
            DateTimeHelper::getCloneSelf('NOW', 'UTC')->mysqlFormatDate()
        );

        if (isset($projectId)) {
            $query = LargeImportLog::query()->getQuery()
                ->join('if_control AS C', 'if_control_id', '=', 'C.id')
                ->where('project_id', $projectId)
                ->where('C.created_at', '>=', $dateFrom)
                ->where('C.created_at', '<=', $dateTo);

            if (isset($resourceId)) {
                $query->where('resource_id', $resourceId);
            }

            return $query->get();
        }

        return $this->addUrls(LargeImportLog::query()->getQuery()
            ->join('if_control AS C', 'if_control_id', '=', 'C.id')
            ->where('C.created_at', '>=', $dateFrom)
            ->where('C.created_at', '<=', $dateTo)
            ->groupBy(['project_id', 'resource_id'])
            ->selectRaw('COUNT(*) AS count, MIN(size) AS min_size, MAX(size) AS max_size, AVG(size) AS avg_size, C.project_id, C.resource_id')
            ->get());
    }
}