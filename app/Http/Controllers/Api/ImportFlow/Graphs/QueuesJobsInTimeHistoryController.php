<?php
/**
 * Created by PhpStorm.
 * User: tomw
 * Date: 6/22/17
 * Time: 12:22 PM
 */

namespace App\Http\Controllers\Api\ImportFlow\Graphs;


use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDImportFlowConnections;

class QueuesJobsInTimeHistoryController extends Controller {

    public function getIndex() {
        $dayCount = Input::get("day_count", 2);
        $dayCount = min($dayCount, 30);

        $builder = MDImportFlowConnections::getGearmanConnection()
            ->table('gearman_queue_size_stats')
            ->take($dayCount*24)
            ->orderBy('created_at', 'desc')
            ->groupBy(MDImportFlowConnections::getGearmanConnection()->raw('HOUR(`created_at`), DATE(`created_at`)'))
            ->select([
                MDImportFlowConnections::getGearmanConnection()->raw('MIN(created_at) as created_at'),
                MDImportFlowConnections::getGearmanConnection()->raw('AVG(`jobs_count`) as avg'),
                MDImportFlowConnections::getGearmanConnection()->raw('MIN(`jobs_count`) as min'),
                MDImportFlowConnections::getGearmanConnection()->raw('MAX(`jobs_count`) as max'),
            ]);
        $result = $builder->get();
        /* Musí se to řadit znovu, když už je to seřazené v SQL?
        usort($result, function ($a, $b){
            return strcmp($a->created_at, $b->created_at);
        });
        */

        $data = array();

        foreach ($result as $row) {
            $obj = $this->getEmptyObject($row->created_at);
            $obj->value = roundFirstDecNumber($row->avg, 0);
            $obj->min = $row->min;
            $obj->max = $row->max;
            $data[] = $obj;
        }
        $this->getApiResponse()->success(array_values($data));
    }


    private function getEmptyObject($category) {
        return (object) array(
            'category' => $category,
            'value' => 0,
            'min' => 0,
            'max' => 0
        );
    }

    private function getKeyName($type, $active) {
        return "{$type} - {$active}";
    }


}