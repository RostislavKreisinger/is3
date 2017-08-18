<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\ImportSupport\Project;

class ImportFlowPoolController extends Controller {

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

    public function getControlPool($projectId, $resourceId) {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        $results = [];
        $IFControlPools = MDImportFlowConnections::getImportFlowConnection()
            ->table('if_control')
            ->select(
                [
                    \DB::raw("DISTINCT(`if_control`.`unique`) as 'unique', MAX(`if_control`.`created_at`) as created_at, `if_control`.`date_from`, `if_control`.`date_to`, `if_control`.`run_time`, `if_control`.`in_repair`, `if_control`.`is_history`, `if_control`.`updated_at`"),
                    'ifi.id as ifi_id', 'ifi.active as ifi_active', 'ifi.ttl as ifi_ttl', 'ifi.date_from as ifi_date_from', 'ifi.date_to as ifi_date_to', 'ifi.start_at as ifi_start_at', 'ifi.finish_at as ifi_finish_at',
                    'ife.id as ife_id', 'ife.active as ife_active', 'ife.ttl as ife_ttl', 'ife.start_at as ife_start_at', 'ife.finish_at as ife_finish_at',
                    'ifc.id as ifc_id', 'ifc.active as ifc_active', 'ifc.ttl as ifc_ttl', 'ifc.start_at as ifc_start_at', 'ifc.finish_at as ifc_finish_at',
                    'ifo.id as ifo_id', 'ifo.active as ifo_active', 'ifo.ttl as ifo_ttl', 'ifo.start_at as ifo_start_at', 'ifo.finish_at as ifo_finish_at',
                ]
            )
            ->leftJoin('if_import as ifi', 'ifi.unique', '=', 'if_control.unique')
            ->leftJoin('if_etl as ife', 'ife.unique', '=', 'if_control.unique')
            ->leftJoin('if_calc as ifc', 'ifc.unique', '=', 'if_control.unique')
            ->leftJoin('if_output as ifo', 'ifo.unique', '=', 'if_control.unique')
            ->groupBy('if_control.unique')
            ->where('if_control.project_id', '=', $this->project->id)
            ->where('if_control.resource_id', '=', $this->resource->id)
            ->whereNull('if_control.deleted_at')
            ->whereNull('ifi.deleted_at')
            ->whereNull('ife.deleted_at')
            ->whereNull('ifc.deleted_at')
            ->whereNull('ifo.deleted_at')
            ->get();

        if ($IFControlPools) {
            foreach ($IFControlPools as $key => $status) {

                $this->organizeIFTypes($status, 'ifi');
                $this->organizeIFTypes($status, 'ife');
                $this->organizeIFTypes($status, 'ifc');
                $this->organizeIFTypes($status, 'ifo');

                $status->show_data_link = \URL::to("/database/database-selector/{$project->id}/{$resource->id}?date_from={$status->date_from}&date_to={$status->date_to}");
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
                        $status->is_history_status = "Tester";
                        break;
                    case 1:
                        $status->is_history_status = "History";
                        break;
                    default:
                        $status->is_history_status = "Daily";
                }

                switch ($status->in_repair) {
                    case 1:
                        $status->in_repair_status = "In Repair";
                        break;
                    default:
                        $status->in_repair_status = "Success";
                }

                $results[] = $status;
            }
        }


        return $results;

    }

    function organizeIFTypes(&$status, $type) {
        $columns = new \stdClass();
        foreach ($status as $objectKey => $objectValue) {
            if (strpos($objectKey, $type) === 0) {
                $keyName = str_replace("{$type}_", '', $objectKey);
                $columns->{$keyName} = $objectValue;
                unset($status->{$objectKey});
            }
        }
        $status->{$type} = $columns;
    }
}