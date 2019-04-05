<?php

namespace app\Http\Controllers\Open\ImportFlow\Table;


use App\Model\ResourceSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Routing\Controller;

/**
 * Class TestedNotRunningProjectsController
 * @package app\Http\Controllers\Open\ImportFlow\Table
 */
class TestedNotRunningProjectsController extends Controller {
    /**
     * @return ResourceSetting[]|Builder[]|Collection
     */
    public function index() {
        return ResourceSetting::join('resource_eshop', function (JoinClause $join) {
            $join->on('resource_setting.id', '=', 'resource_setting_id')
                ->whereNull('last_successful_import_at');
        })->whereActive(1)->whereResourceId(4)
            ->get([
                'project_id',
                'last_import_at',
                'resource_setting.created_at'
            ]);
    }
}
