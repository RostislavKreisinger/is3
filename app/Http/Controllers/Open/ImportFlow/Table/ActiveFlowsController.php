<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\ImportPools\IFStepPool;

/**
 * Class ActiveFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ActiveFlowsController extends AFlowsController {
    /**
     * @var IFStepPool $stepPool
     * @return array
     */
    public function index(): array {
        $results = [];

        foreach (static::IF_STEP_POOLS as $stepPool) {
            $data = $this->prepareBuilder($stepPool::query())->get();

            if ($data->count() > 0) {
                array_push($results, ...$data);
            }
        }

        $this->addUrls($results);
        $this->calculateRuntimes($results);
        return $results;
    }

    /**
     * @param array $results
     * @return array
     */
    private function calculateRuntimes(array $results): array {
        for ($i = 0; $i < count($results); $i++) {
            switch ($results[$i]->active) {
                case 5:
                    $results[$i]->runtime = time() - strtotime($results[$i]->start_at);
                    break;
                default:
                    $results[$i]->runtime = time() - strtotime($results[$i]->updated_at);
                    break;
            }
        }

        return $results;
    }
}