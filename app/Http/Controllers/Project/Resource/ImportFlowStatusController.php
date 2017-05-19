<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use Monkey\ImportSupport\Project;

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

    public function getIndex($projectId, $resourceId) {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        $results = [];
        $results["resource"] = $this->getImportFlowStatusForProject($projectId, $this->resource);

        if($this->resource->getResourceStats()->getImportFlowDaily()) {
            $results["daily"] = $this->resource->getResourceStats()->getImportFlowDaily();
            $results["daily"]->status = $resource->getStateDailyImportFlow();
        } else {
            $results["daily"] = [];
        }

        if($this->resource->getResourceStats()->getImportFlowHistory()) {
            $results["history"] = $this->resource->getResourceStats()->getImportFlowHistory();
            $results["history"]->status = $resource->getStateHistoryImportFlow();
        } else {
            $results["history"] = [];
        }
        return $results;

    }
}