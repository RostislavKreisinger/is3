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
        $resources = ResourceSetting::join('project', 'project_id', '=', 'project.id')
            ->whereNull('project.deleted_at')
            ->join('user', 'project.user_id', '=', 'user.id')
            ->whereNull('user.deleted_at')
            ->join('client', 'client.user_id', '=', 'user.id')
            ->whereNull('client.deleted_at')
            ->leftJoin('eshop_type', 'eshop_type_id', '=', 'eshop_type.id')
            ->with(['resourceName'])->where('resource_setting.active', '>', 2)
            ->orderBy('resource_setting.updated_at', 'desc')
            ->get(['resource_setting.active', 'user.id', 'eshop_type_id', 'project_id', 'resource_id', 'ttl', 'eshop_type.name']);

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