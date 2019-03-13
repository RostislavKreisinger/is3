<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 13.3.2019
 * Time: 11:49
 */

namespace App\Helpers\Monitoring\ImportFlow;


use Monkey\Connections\MDImportFlowConnections;

class StepPoolDataMiner
{
    const steps = ["i"=>"import","e"=>"etl","c"=>"calc","o"=>"output"];

    public function getBaseGraphData(){
        $query = $this->buildQuery();
        return $this->getActualData($query);
    }

    private function getActualData(string $query){
        $connection = MDImportFlowConnections::getImportFlowConnection();
        $data = $connection->select($query);
        return $data;
    }

    /**
     * @return string
     */
    private function buildQuery(){
        $selectPart = $this->getSelectPart();
        $uniquePart = $this->getAllUniqueQueryPart();
        $joinPart = $this->getJoinPart();


        $query = "
        SELECT $selectPart 
        FROM ($uniquePart) as u 
        {$joinPart}
        ORDER BY flow_runtime DESC
        ";

        return $query;
    }

    private function getJoinPart(){
        $join = "";
        foreach(self::steps as $alias => $stepName){
            $additionalSelect = [];
            if($stepName == "import"){
                $additionalSelect["created_at"] = "init_flow";
            }
            $join .= "
            LEFT JOIN (".$this->getInterSelect($alias, $stepName, $additionalSelect).") as {$stepName} on (u.`unique` = {$stepName}.`unique`)
            ";
        }
        return $join;
    }

    /**
     * @param string $alias
     * @param string $stepName
     * @param array $additionalSelect
     * @return string
     */
    private function getInterSelect( string $alias, string $stepName, array $additionalSelect = []){
        $additional = "";
        foreach($additionalSelect as $name => $selectAlias){
            $additional .= ", {$alias}.{$name} as {$selectAlias}";
        }

        $select = "
            SELECT {$alias}.`unique`,{$alias}.created_at as `start`, {$alias}.active, {$alias}.delay_count, '{$stepName}' as step {$additional},
            TIME_TO_SEC(TIMEDIFF(COALESCE({$alias}.start_at, NOW()), {$alias}.created_at)) as time_to_start,
            TIME_TO_SEC(TIMEDIFF( COALESCE({$alias}.finish_at, NOW()), COALESCE({$alias}.start_at, NOW()))) as runtime
            FROM if_{$stepName} as {$alias}
        ";
        return $select;
    }

    /**
     * @return string
     */
    private function getAllUniqueQueryPart(){

        $subQueries = [];
        foreach(self::steps as $alias => $name){
            $subQueries[] = "
                SELECT {$alias}.`unique`
                FROM if_{$name} as {$alias}
                WHERE {$alias}.active != 0
            ";
        }

        return implode(" UNION ", $subQueries);
    }

    /**
     * @return string
     */
    private function getSelectPart(){

        $select[] = "u.`unique` as `unique`";
        foreach(self::steps as $alias => $name) {
            $select[] = "{$name}.time_to_start as {$alias}_time_to_start";
            $select[] = "{$name}.runtime as {$alias}_runtime";
            $select[] = "{$name}.active as {$alias}_active";
            $select[] = "{$name}.delay_count as {$alias}_delay_count";
        }
        $select[] = "TIME_TO_SEC(TIMEDIFF(NOW(), import.init_flow)) as flow_runtime";

        return implode(", \n", $select);
    }
}


/**
 * SELECT
u.`unique` as `unique`,
COALESCE(imp.step,etl.step,calc.step,outp.step) as `step`,
imp.time_to_start as import_time_to_start, imp.runtime as import_runtime,
etl.time_to_start as etl_time_to_start, etl.runtime as etl_runtime,
calc.time_to_start as calc_time_to_start, calc.runtime as calc_runtime,
outp.time_to_start as output_time_to_start, outp.runtime as output_runtime,
TIME_TO_SEC(TIMEDIFF(NOW(), imp.init_flow)) as flow_runtime,
"|" as `|`,
imp.active as iActive, imp.delay_count as iDelayC,
el.active as eActive, etl.delay_count as eDelayC,
calc.active as cActive, calc.delay_count as cDelayC,
outp.active as oActive, outp.delay_count as oDelayC,
"|" as `||`
FROM (
SELECT i.`unique`
FROM if_import as i
WHERE i.active != 0
UNION
SELECT e.`unique`
FROM if_etl as e
WHERE e.active != 0if_importif_import
UNION
SELECT c.`unique`
FROM if_calc as c
WHERE c.active != 0
UNION
SELECT o.`unique`
FROM if_output as o
WHERE o.active != 0
) as u
LEFT JOIN (
SELECT i.`unique`,i.created_at as `start`, i.active, i.delay_count, "import" as step, i.created_at as init_flow,
TIME_TO_SEC(TIMEDIFF(COALESCE(i.start_at, NOW()), i.created_at)) as time_to_start,
TIME_TO_SEC(TIMEDIFF( COALESCE(i.finish_at, NOW()), COALESCE(i.start_at, NOW()))) as runtime
FROM if_import as i
) as imp ON (imp.`unique` = u.`unique`)
LEFT JOIN (
SELECT e.`unique`,e.created_at as `start`, e.active, e.delay_count, "etl" as step,
TIME_TO_SEC(TIMEDIFF(COALESCE(e.start_at, NOW()), e.created_at)) as time_to_start,
TIME_TO_SEC(TIMEDIFF( COALESCE(e.finish_at, NOW()), COALESCE(e.start_at, NOW()))) as runtime
FROM if_etl as e
) as etl ON (u.`unique` = etl.`unique`)
LEFT JOIN(
SELECT  c.`unique`,c.created_at as `start`, c.active, c.delay_count, "calc" as step,
TIME_TO_SEC(TIMEDIFF(COALESCE(c.start_at, NOW()), c.created_at)) as time_to_start,
TIME_TO_SEC(TIMEDIFF( COALESCE(c.finish_at, NOW()), COALESCE(c.start_at, NOW()))) as runtime
FROM if_calc as c
) as calc ON (u.`unique` = calc.`unique`)
LEFT JOIN(
SELECT o.`unique`, o.created_at as `start`, o.active, o.delay_count, "output" as step,
TIME_TO_SEC(TIMEDIFF(COALESCE(o.start_at, NOW()), o.created_at)) as time_to_start,
TIME_TO_SEC(TIMEDIFF( COALESCE(o.finish_at, NOW()), COALESCE(o.start_at, NOW()))) as runtime
FROM if_output as o
) as outp on (u.`unique` = outp.`unique`)

ORDER BY flow_runtime DESC
 */