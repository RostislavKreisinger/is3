<?php
/**
 * Created by PhpStorm.
 * User: tomw
 * Date: 6/22/17
 * Time: 12:22 PM
 */

namespace App\Http\Controllers\Api\ImportFlow\Graphs;


use App\Http\Controllers\Api\Controller;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDImportFlowConnections;

class QueuesTimesStatusController extends Controller {

    public function getIndex() {

        $result = MDImportFlowConnections::getImportFlowConnection()->select("SELECT count(u.id) as `count`, u.`type`,u.`active`, MAX(u.run_time) as `max`, MIN(u.run_time) as `min`, AVG(u.run_time) as `avg`
FROM (
SELECT id, 'import' as `type`, active, TIMESTAMPDIFF(SECOND,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_import
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'etl' as `type`, active, TIMESTAMPDIFF(SECOND,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_etl
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'calc' as `type`, active, TIMESTAMPDIFF(SECOND,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_calc
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'output' as `type`, active, TIMESTAMPDIFF(SECOND,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_output
WHERE active not in (0,3)
AND ttl > 0
) as u
GROUP BY u.`type`, u.`active`");


        $data = $this->getEmptyObject();

        foreach ($result as $row){
            $serieName = $this->getKeyName($row->type, $row->active);
            $data[$serieName]->min = round($row->min);
            $data[$serieName]->avg = round($row->avg);
            $data[$serieName]->max = round($row->max);
        }
        $this->getApiResponse()->success(array_values($data));
    }


    private function getEmptyObject(){
        $result = array();
        $names = array('import', 'etl', 'calc', 'output');
        $states = array(1, 2, 5);
        $times = array("min", "avg", "max");
        foreach ($names as $name){
            foreach ($states as $state){
                $serieName = $this->getKeyName($name, $state);//"{$name}_{$state}";
                $result[$serieName] = (object) array(
                    'category' => $serieName
                );
                foreach ($times as $time){
                    $result[$serieName]->{$time} = 0;
                }
            }
        }
        return $result;
    }

    private function getKeyName($type, $active) {
        return "{$type} - {$active}";
    }


}