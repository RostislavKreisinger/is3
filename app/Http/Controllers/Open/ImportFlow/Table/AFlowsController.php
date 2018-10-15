<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use App\Http\Controllers\User\DetailController as UserController;
use App\Model\ImportPools\IFCalcPool;
use App\Model\ImportPools\IFEtlPool;
use App\Model\ImportPools\IFImportPool;
use App\Model\ImportPools\IFOutputPool;
use App\Model\ImportPools\IFStepPool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Routing\Controller;

/**
 * Class AFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
abstract class AFlowsController extends Controller {
    /**
     * @var IFStepPool[]
     */
    const IF_STEP_POOLS = [
        IFImportPool::class,
        IFEtlPool::class,
        IFCalcPool::class,
        IFOutputPool::class
    ];

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function prepareBuilder(Builder $builder): Builder {
        return $builder->whereActiveIn([1, 2, 5])->with([
            'project' => function (BelongsTo $query) {
                $query->with('eshopTypeName')->select(['id', 'user_id', 'eshop_type_id']);
            },
            'resource' => function (BelongsTo $query) {
                $query->select(['id', 'name']);
            }
        ])->select([
            'active',
            'created_at',
            'delay_count',
            'hostname',
            'project_id',
            'resource_id',
            'start_at',
            'finish_at',
            'ttl',
            'unique',
            'updated_at'
        ]);
    }

    /**
     * @param array $results
     * @return array
     */
    protected function addUrls(array $results): array {
        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->project_url = action(DetailController::routeMethod('getIndex'), [
                'project_id' => $results[$i]->project_id
            ]);
            $results[$i]->resource_url = action(ResourceDetailController::routeMethod('getIndex'), [
                'project_id' => $results[$i]->project_id,
                'resource_id' => $results[$i]->resource_id
            ]);

            if (property_exists($results[$i], 'project')) {
                $results[$i]->user_url = action(UserController::routeMethod('getIndex'), [
                    'project_id' => $results[$i]->project->user_id
                ]);
            }
        }

        return $results;
    }
}