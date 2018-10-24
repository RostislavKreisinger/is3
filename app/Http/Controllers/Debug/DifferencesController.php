<?php

namespace App\Http\Controllers\Debug;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Monkey\ImportEshopDataObjects\Base\Differences;
use Monkey\ImportEshopDataObjects\Entity\Simple\Alias;
use Monkey\ImportEshopDataObjects\Entity\Simple\Condition;
use Monkey\ImportEshopDataObjects\Entity\Simple\Join;
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
     * @var DifferencesHelper $differencesHelper
     */
    private $differencesHelper;

    /**
     * @var int $resourceSettingId
     */
    private $resourceSettingId;

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function add(int $project_id, int $resource_id, Request $request) {
        $project = Project::find($project_id);
        $difference = null;
        $type = 0;

        switch ($request->input('type')) {
            case 'column':
                $difference = new Alias(
                    $request->input('column') ?? '',
                    $request->input('alias') ?? ''
                );
                $type = Differences::TYPE_COLUMNS;

                if (!empty($request->input('raw'))) {
                    $difference->setRaw($request->input('raw'));
                }
                break;
            case 'table':
                $difference = new Join(
                    $request->input('table') ?? '',
                    $request->input('alias') ?? ''
                );
                $difference->setType($request->input('join-type'));
                $type = Differences::TYPE_TABLE_ALIAS;

                break;
            case 'condition':
                $difference = new Condition(
                    empty($request->input('operator')) ? Condition::OPERATOR_WHERE : $request->input('operator'),
                    $request->input('first') ?? '',
                    $request->input('compare-operator') ?? '',
                    $request->input('second') ?? ''
                );
                $type = Differences::TYPE_CONDITION;

                if (!empty($request->input('raw'))) {
                    $difference->setRaw($request->input('raw'));
                }
                break;
            case 'join-condition':
                $differenceModel = ResourceSettingDifference::find($request->input('id'));
                $join = $differenceModel->getAttribute('difference');
                $condition = $join->addNewCondition(
                    empty($request->input('operator')) ? Condition::OPERATOR_WHERE : $request->input('operator'),
                    $request->input('first') ?? '',
                    $request->input('compare-operator') ?? '',
                    $request->input('second') ?? ''
                );

                if (!empty($request->input('raw'))) {
                    $condition->setRaw($request->input('raw'));
                }

                $differenceModel->setAttribute('difference', $join);
                $differenceModel->save();
                break;
        }

        if (!empty($difference) && $this->getDifferencesHelper($project, $resource_id)->tableExists()) {
            ResourceSettingDifference::updateOrCreate([
                'resource_setting_id' => $this->getResourceSettingId($project, $resource_id),
                'endpoint' => $request->input('endpoint'),
                'type' => $type,
                'active' => false,
                'difference' => $difference
            ]);
        }
    }

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function load(int $project_id, int $resource_id, Request $request) {
        $project = Project::find($project_id);

        if (!$this->getDifferencesHelper($project, $resource_id)->tableExists()) {
            return [];
        }

        $differencesQuery = ResourceSettingDifference::byResourceSettingId(
            $this->getResourceSettingId($project, $resource_id)
        )->byEndpoint($request->input('endpoint'));

        if ($request->input('deleted')) {
            $differencesQuery->withTrashed();
        }

        $differences = $differencesQuery->get(['id', 'type', 'active', 'difference', 'deleted_at'])->map(function ($item, $key) {
            $item->difference = $item->difference->__toString();
            return $item;
        });

        $data = [
            'operator' => [
                Condition::OPERATOR_WHERE,
                Condition::OPERATOR_AND,
                Condition::OPERATOR_OR,
                Condition::OPERATOR_XOR
            ],
            'secondOperator' => [
                Condition::COMPARE_OPERATOR_EQUALS,
                Condition::COMPARE_OPERATOR_BIGGER,
                Condition::COMPARE_OPERATOR_SMALLER,
                Condition::COMPARE_OPERATOR_BIGGER_EQUALS,
                Condition::COMPARE_OPERATOR_SMALLER_EQUALS,
                Condition::COMPARE_OPERATOR_NOT_EQUALS
            ],
            'join' => [
                Join::INNER,
                Join::LEFT
            ],
            'differences' => $differences
        ];
        return $data;
    }

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function activate(int $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->activate();
    }

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function deactivate(int $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->deactivate();
    }

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function restore(int $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::withTrashed()->find($request->input('id'))->restore();
    }

    /**
     * @param int $project_id
     * @param int $resource_id
     * @param Request $request
     * @throws Exception
     */
    public function delete(int $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->delete();
    }

    /**
     * @param Project $project
     * @param int $resourceId
     * @return DifferencesHelper
     */
    public function getDifferencesHelper(Project $project, int $resourceId): DifferencesHelper {
        if (is_null($this->differencesHelper)) {
            $this->differencesHelper = new DifferencesHelper($this->getResourceSettingId($project, $resourceId));
        }

        return $this->differencesHelper;
    }

    /**
     * @param Project $project
     * @param int $resourceId
     * @return int
     */
    private function getResourceSettingId(Project $project, int $resourceId): int {
        if (is_null($this->resourceSettingId)) {
            $this->resourceSettingId = $project->resourceSettings($resourceId)->first()->id;
        }

        return $this->resourceSettingId;
    }
}