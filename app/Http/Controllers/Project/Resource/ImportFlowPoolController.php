<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use App\Model\ImportPools\IFControlPool;
use App\Services\FlowGeneratorService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Monkey\DateTime\DateTimeHelper;
use URL;

/**
 * Class ImportFlowPoolController
 * @package App\Http\Controllers\Project\Resource
 */
class ImportFlowPoolController extends Controller {
    /**
     * @param $projectId
     * @param $resourceId
     * @return array
     * @throws Exception
     */
    public function getControlPool($projectId, $resourceId) {
        $controlPools = IFControlPool::with(['importPool', 'etlPool', 'calcPool', 'outputPool'])
            ->where('project_id', $projectId)
            ->where('resource_id', $resourceId)
            ->get();

        $controlPools->map(function (IFControlPool $controlPool) {
            $controlPool->date_from = DateTimeHelper::getCloneSelf($controlPool->date_from)->mysqlFormatDate();
            $controlPool->date_to = DateTimeHelper::getCloneSelf($controlPool->date_to)->mysqlFormatDate();
            $controlPool->show_data_link = URL::to(sprintf(
                '/database/database-selector/%s/%s?date_from=%s&date_to=%s',
                $controlPool->project_id,
                $controlPool->resource_id,
                $controlPool->date_from,
                $controlPool->date_to
            ));

            switch ($controlPool->is_history) {
                case 2:
                    $controlPool->is_history_status = "Tester";
                    break;
                case 1:
                    $controlPool->is_history_status = "History";
                    break;
                default:
                    $controlPool->is_history_status = "Daily";
            }
        });

        return $controlPools;
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @return RedirectResponse
     * @throws Exception
     */
    public function generateFlows(int $projectId, int $resourceId) {
        if (!$this->getUser()->can('project.resource.button.repair.generate')) {
            return json_encode([
                'type' => 'danger',
                'message' => "You don't have permissions to generate new flows!"
            ]);
        }

        $flowGenerator = new FlowGeneratorService($projectId, $resourceId);

        if ($flowGenerator->generate(request('date-from'), request('date-to'), request('split'))) {
            $response = [
                'type' => 'success',
                'message' => 'History reload flows generated successfully!'
            ];
        } else {
            $response = [
                'type' => 'warning',
                'message' => 'Cannot generate new History flows before all previously generated are finished!'
            ];
        }

        return json_encode($response);
    }
}
