<?php

namespace App\Http\Controllers\Debug;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Monkey\ImportEshopDataObjects\Base\Differences;
use Monkey\ImportEshopDataObjects\Entity\Simple\Alias;
use Monkey\ImportEshopDataObjects\Entity\Simple\Condition;
use Monkey\ImportEshopDataObjects\Entity\Simple\Join;
use Monkey\ImportSupport\Project;
use Monkey\ImportSupport\ResourceSettingDifference;

/**
 * Class DifferencesController
 * @package App\Http\Controllers\Debug
 * @author Lukáš Kielar
 */
class DifferencesController extends Controller {
    /**
     * @var int $resourceSettingId
     */
    private $resourceSettingId;

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function add(Project $project_id, int $resource_id, Request $request) {
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

        if (!empty($difference)) {
            ResourceSettingDifference::updateOrCreate([
                'resource_setting_id' => $this->getResourceSettingId($project_id, $resource_id),
                'endpoint' => $request->input('endpoint'),
                'type' => $type,
                'active' => false,
                'difference' => $difference
            ]);
        }

        return $this->load($project_id, $resource_id, $request);
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function load(Project $project_id, int $resource_id, Request $request) {
        $differencesQuery = ResourceSettingDifference::byResourceSettingId(
            $this->getResourceSettingId($project_id, $resource_id)
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
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function activate(Project $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->activate();
        return $this->load($project_id, $resource_id, $request);
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function deactivate(Project $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->deactivate();
        return $this->load($project_id, $resource_id, $request);
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function restore(Project $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::withTrashed()->find($request->input('id'))->restore();
        return $this->load($project_id, $resource_id, $request);
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function delete(Project $project_id, int $resource_id, Request $request) {
        ResourceSettingDifference::find($request->input('id'))->delete();
        return $this->load($project_id, $resource_id, $request);
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @return int
     */
    private function getResourceSettingId(Project $project_id, int $resource_id): int {
        if (is_null($this->resourceSettingId)) {
            $this->resourceSettingId = $project_id->getResourceSettings($resource_id)->first()->id;
        }

        return $this->resourceSettingId;
    }
}