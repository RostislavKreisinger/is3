<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\ImportPools\IFStepPool;

/**
 * Class DelayedFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class DelayedFlowsController extends AFlowsController {
    /**
     * @var IFStepPool $stepPool
     * @return array
     */
    public function index(): array {
        $results = [];

        foreach (static::IF_STEP_POOLS as $stepPool) {
            $data = $this->prepareBuilder($stepPool::delayed())->get();

            if ($data->count() > 0) {
                array_push($results, ...$data);
            }
        }

        $this->addUrls($results);
        return $results;
    }
}