<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\LargeImportLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class LargeFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class LargeFlowsController extends Controller {
    /**
     * @param Request $request
     * @return LargeImportLog[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request) {
        return LargeImportLog::query()->getQuery()
            ->join('if_control AS C', 'if_control_id', '=', 'C.id')
            ->groupBy(['project_id', 'resource_id'])
            ->selectRaw('COUNT(*) AS count, MIN(size) AS min_size, MAX(size) AS max_size, AVG(size) AS avg_size, C.project_id, C.resource_id')
            ->get();
    }
}