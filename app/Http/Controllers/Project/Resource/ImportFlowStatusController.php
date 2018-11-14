<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use Exception;
use Monkey\ImportSupport\Project;

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
        $results["daily"] = $resource->getResourceStats()->getImportFlowDaily();
        $results["daily"]->status = $dailyStatus;

        $historyStatus = $resource->getStateHistoryImportFlow();
        $results["history"] = $resource->getResourceStats()->getImportFlowHistory();
        $results["history"]->status = $historyStatus;

        return $results;
    }

    public function getResourceInfo($projectId, $resourceId) {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        $results["resource"] = $this->getImportFlowStatusForProject($projectId, $this->resource);
        return $results;
    }
}