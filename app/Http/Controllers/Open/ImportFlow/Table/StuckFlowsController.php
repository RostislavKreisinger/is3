<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use Monkey\DateTime\DateTimeHelper;

/**
 * Class StuckFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class StuckFlowsController extends AFlowsController {
    /**
     * @return array
     */
    public function index(): array {
        $time = DateTimeHelper::getCloneSelf('NOW', 'UTC')
            ->minusHours(12)->mysqlFormat();
        $results = [];

        foreach (static::IF_STEP_POOLS as $stepPool) {
            $data = $this->prepareBuilder($stepPool::whereOlderThan($time))->get();

            if ($data->count() > 0) {
                array_push($results, ...$data);
            }
        }

        $this->addUrls($results);
        return $results;
    }
}