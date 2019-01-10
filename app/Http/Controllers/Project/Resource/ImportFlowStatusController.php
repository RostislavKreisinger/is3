<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use App\Model\ImportPools\IFControlPool;
use App\Model\ImportPools\IFDailyPool;
use App\Model\ImportPools\IFHistoryPool;
use Exception;
use Monkey\ImportSupport\Project;
use stdClass;

/**
 * Class ImportFlowStatusController
 * @package App\Http\Controllers\Project\Resource
 */
class ImportFlowStatusController extends Controller {

    /**
     *
     * @var Project
     */
    private $project;

    /**
     *
     * @var Resource
     */
    private $resource;

    /**
     * @param int $projectId
     * @param int $resourceId
     * @return array
     * @throws Exception
     */
    public function getIndex($projectId, $resourceId): array {
        $project = Project::find($projectId);
        $resource = $project->getResource($resourceId);

        $results = [];
        $dailyStatus = $resource->getStateDailyImportFlow();

        if (($daily = $resource->getResourceStats()->getImportFlowDaily()) instanceof IFDailyPool) {
            $results["daily"] = $daily;
        } else {
            $results["daily"] = new stdClass();
        }

        $results["daily"]->status = $dailyStatus;
        $historyStatus = $resource->getStateHistoryImportFlow();

        if (($history = $resource->getResourceStats()->getImportFlowHistory()) instanceof IFHistoryPool) {
            $results["history"] = $history;
        } else {
            $results["history"] = new stdClass();
        }

        $results["history"]->status = $historyStatus;
        return $results;
    }

    public function getResourceInfo($projectId, $resourceId) {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        $results["resource"] = $this->getImportFlowStatusForProject($projectId, $this->resource);
        return $results;
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @param string $unique
     * @return \Illuminate\Http\RedirectResponse
     */
    public function raiseDifficulty(int $projectId, int $resourceId, string $unique) {
        $controlPool = IFControlPool::whereUnique($unique)->first();
        $controlPool->raiseDifficulty();
        $message = 'Successfully raised difficulty of flow!';
        return back()->with('message', $message);
    }
}