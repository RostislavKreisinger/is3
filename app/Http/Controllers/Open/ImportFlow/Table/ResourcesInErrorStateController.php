<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\Project\DetailController;
use App\Http\Controllers\Project\Resource\DetailController as ResourceDetailController;
use App\Model\ResourceSetting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Routing\Controller;
use Monkey\Constants\ImportFlow\Resource\ResourceSetting as ResourceSettingsConstants;

/**
 * Class ResourcesInErrorState
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ResourcesInErrorStateController extends Controller {
    /**
     * @return Collection
     */
    public function index(): Collection {
        $resources = ResourceSetting::with(['project' => function (BelongsTo $query) {
            $query->with('eshopTypeName')->select(['id', 'user_id', 'eshop_type_id']);
        }, 'resourceName'])->whereActive(ResourceSettingsConstants::ERROR)
            ->orderBy('updated_at', 'desc')
            ->get(['project_id', 'resource_id', 'ttl']);

        for ($i = 0; $i < count($resources); $i++) {
            $resources[$i]->project_url = action(DetailController::routeMethod('getIndex'), [
                'project_id' => $resources[$i]->project_id
            ]);
            $resources[$i]->resource_url = action(ResourceDetailController::routeMethod('getIndex'), [
                'project_id' => $resources[$i]->project_id,
                'resource_id' => $resources[$i]->resource_id
            ]);
        }

        return $resources;
    }
}