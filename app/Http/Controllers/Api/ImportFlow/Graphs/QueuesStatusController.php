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

class QueuesStatusController extends Controller {

    public function getIndex() {




        $result = MDImportFlowConnections::getImportFlowConnection()->select("SELECT count(u.id) as `count`, u.`type`,u.`active`, MAX(u.run_time), MIN(u.run_time), AVG(u.run_time)
FROM (
SELECT id, 'import' as `type`, active, TIMESTAMPDIFF(MINUTE,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_import
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'etl' as `type`, active, TIMESTAMPDIFF(MINUTE,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_etl
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'calc' as `type`, active, TIMESTAMPDIFF(MINUTE,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_calc
WHERE active not in (0,3)
AND ttl > 0
UNION
SELECT id, 'output' as `type`, active, TIMESTAMPDIFF(MINUTE,DATE_SUB(start_at,INTERVAL -2 HOUR),NOW()) as run_time
FROM if_output
WHERE active not in (0,3)
AND ttl > 0
) as u
GROUP BY u.`type`, u.`active`");


        $data = $this->getEmptyObject();
        foreach ($result as $row){
            $data[$row->type]->{'active_'.$row->active} = $row->count;
        }
        $this->getApiResponse()->success(array_values($data));
    }


    private function getEmptyObject(){
        $result = array();
        $names = array('import', 'etl', 'calc', 'output');
        $states = array(1, 2, 5);
        foreach ($names as $name){
            $result[$name] = (object) array(
                'category' => $name
            );
            foreach ($states as $state){
                $result[$name]->{'active_'.$state} = 0;
            }
        }
        return $result;

    }


}