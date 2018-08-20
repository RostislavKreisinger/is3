<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use App\Model\ImportPools\IFCalcPool;
use App\Model\ImportPools\IFEtlPool;
use App\Model\ImportPools\IFImportPool;
use App\Model\ImportPools\IFOutputPool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Routing\Controller;
use Monkey\DateTime\DateTimeHelper;

/**
 * Class StuckFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class StuckFlowsController extends Controller {
    /**
     * @return array
     */
    public function index(): array {
        $time = DateTimeHelper::getCloneSelf('NOW', 'UTC')
            ->minusHours(12)->mysqlFormat();
        $results = [];

        array_push($results, ...$this->prepareBuilder(IFImportPool::whereOlderThan($time))->get());
        array_push($results, ...$this->prepareBuilder(IFEtlPool::whereOlderThan($time))->get());
        array_push($results, ...$this->prepareBuilder(IFCalcPool::whereOlderThan($time))->get());
        array_push($results, ...$this->prepareBuilder(IFOutputPool::whereOlderThan($time))->get());

        for ($i = 0; $i < count($results); $i++) {
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

    /**
     * @param Builder $builder
     * @return Builder
     */
    private function prepareBuilder(Builder $builder): Builder {
        return $builder->whereActiveIn([1, 2, 5])->with([
            'project' => function (BelongsTo $query) {
                $query->with('eshopTypeName')->select(['id', 'user_id', 'eshop_type_id']);
            },
            'resource' => function (BelongsTo $query) {
                $query->select(['id', 'name']);
            }
        ])->select(['active', 'ttl', 'project_id', 'resource_id', 'unique']);
    }
}