<?php
/**
 * Created by PhpStorm.
 * User: tomw
 * Date: 6/22/17
 * Time: 12:22 PM
 */

namespace App\Http\Controllers\Api\ImportFlow\Graphs;


use App\Http\Controllers\Api\Controller;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Environment\Environment;

class QueuesJobsInTimeController extends Controller {

    public function getIndex() {

        $result = MDImportFlowConnections::getGearmanConnection()
            ->table('gearman_queue_size_stats')
            ->take(120)
            ->orderBy('created_at', 'desc')
            ->get();
        /* Musí se to řadit znovu, když už je to seřazené v SQL?
        usort($result, function ($a, $b){
            return strcmp($a->created_at, $b->created_at);
        });
        */

        $data = array();

        foreach ($result as $row) {
            $obj = $this->getEmptyObject($row->created_at);
            $obj->value = $row->jobs_count;
            $data[] = $obj;
        }

//        if(Environment::isLocalhost()) {
//            $fake = $this->getEmptyObject(DateTimeHelper::getCloneSelf("2017-06-28 07:13:17")->changeSeconds(5)->mysqlFormat());
//            $fake->value = rand(0, 40);
//            $data[] = $fake;
//        }

        $this->getApiResponse()->success(array_values($data));
    }


    private function getEmptyObject($category) {
        return (object) array(
            'category' => $category,
            'value' => 0
        );
    }

    private function getKeyName($type, $active) {
        return "{$type} - {$active}";
    }


}