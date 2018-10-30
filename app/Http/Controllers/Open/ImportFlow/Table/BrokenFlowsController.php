<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use App\Model\ImportPools\IFDailyPool;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Routing\Controller;

/**
 * Class BrokenFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class BrokenFlowsController extends Controller {
    /**
     * @return Collection
     */
    public function index(): Collection {
        $results = IFDailyPool::query()->join('if_import', function (JoinClause $join) {
            $join->on('if_import.id', '=', 'if_daily.if_import_id')
                ->where('if_import.project_id', '!=', 'if_daily.project_id');
        })->get();

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->project_url = '';
            $results[$i]->resource_url = '';

            $results[$i]->project_url = action(DetailController::routeMethod('getIndex'), [
                'project_id' => $results[$i]->project_id
            ]);
            $results[$i]->resource_url = action(ResourceDetailController::routeMethod('getIndex'), [
                'project_id' => $results[$i]->project_id,
                'resource_id' => $results[$i]->resource_id
            ]);
        }

        return $results;
    }
}