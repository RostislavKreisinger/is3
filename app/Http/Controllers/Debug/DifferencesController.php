<?php

namespace App\Http\Controllers\Debug;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Monkey\ImportEshopDataObjects\Base\Differences;
use Monkey\ImportEshopDataObjects\Entity\Simple\Alias;
use Monkey\ImportEshopDataObjects\Entity\Simple\Condition;
use Monkey\ImportEshopSdk\Helper\DifferencesHelper;
use Monkey\ImportSupport\Project;
use Monkey\ImportSupport\ResourceSettingDifference;

/**
 * Class DifferencesController
 * @package App\Http\Controllers\Debug
 * @author Lukáš Kielar
 */
class DifferencesController extends Controller {
    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function add(Project $project_id, int $resource_id, Request $request) {
        $resourceSettingId = $this->getResourceSettingId($project_id, $resource_id);
        $difference = null;
        $type = 0;

        switch ($request->input('type')) {
            case 'column':
                $difference = new Alias($request->input('column') ?? '', $request->input('alias') ?? '');
                $type = Differences::TYPE_COLUMNS;

                if (!empty($request->input('raw'))) {
                    $difference->setRaw($request->input('raw'));
                }
                break;
            case 'table':
                $difference = new Alias($request->input('table') ?? '', $request->input('alias') ?? '');
                $type = Differences::TYPE_TABLE_ALIAS;

                if (!empty($request->input('raw'))) {
                    $difference->setRaw($request->input('raw'));
                }
                break;
            case 'condition':
                //$difference = new Condition();
                $type = Differences::TYPE_CONDITION;

                if (!empty($request->input('raw'))) {
                    $difference->setRaw($request->input('raw'));
                }
                break;
        }

        if (!empty($difference)) {
            ResourceSettingDifference::create([
                'resource_setting_id' => $resourceSettingId,
                'endpoint' => $request->input('endpoint'),
                'type' => $type,
                'active' => false,
                'difference' => $difference
            ]);
        }

        return ResourceSettingDifference::byResourceSettingId($resourceSettingId)->get();
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @return ResourceSettingDifference[]
     */
    public function load(Project $project_id, int $resource_id) {
        $resourceSettingId = $this->getResourceSettingId($project_id, $resource_id);
        return ResourceSettingDifference::byResourceSettingId($resourceSettingId)->get();
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return ResourceSettingDifference[]
     */
    public function delete(Project $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->delete();
        $resourceSettingId = $this->getResourceSettingId($project_id, $resource_id);
        return ResourceSettingDifference::byResourceSettingId($resourceSettingId)->get();
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @return int
     */
    private function getResourceSettingId(Project $project_id, int $resource_id): int {
        return $project_id->getResourceSettings($resource_id)->first()->id;
    }
}