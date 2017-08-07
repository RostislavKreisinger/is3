<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use App\Model\ImportPools\IFControlPool;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\ImportSupport\Project;

class ImportFlowPoolController extends Controller
{

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

    public function getControlPool($projectId, $resourceId)
    {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        $results = [];
        $IFControlPools = MDImportFlowConnections::getImportFlowConnection()
            ->table('if_control')
            ->limit(2)
            ->get();

        if ($IFControlPools) {
            foreach ($IFControlPools as $key => $status) {
                switch ($status->is_history) {
                    case 0:
                        $status->is_history_status = "daily";
                        break;
                    case 1:
                        $status->is_history_status = "history";
                        break;
                    case 2:
                        $status->is_history_status = "tester";
                        break;
                    default:
                        $status->is_history_status = NULL;
                }

                switch ($status->is_history) {
                    case 2:
                        $status->is_history_status= "Tester";
                        break;
                    case 1:
                        $status->is_history_status= "History";
                        break;
                    default:
                        $status->is_history_status = "Daily";
                }

                switch ($status->in_repair) {
                    case 1:
                        $status->in_repair_status= "In Repair";
                        break;
                    default:
                        $status->in_repair_status = "Success";
                }

                $results[] = $status;
            }
        }


        return $results;

    }
}