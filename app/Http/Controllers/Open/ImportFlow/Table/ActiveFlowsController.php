<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\ImportPools\IFStepPool;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Monkey\DateTime\DateTimeHelper;

/**
 * Class ActiveFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ActiveFlowsController extends AFlowsController {
    /**
     * @param Request $request
     * @var IFStepPool $stepPool
     * @return array
     */
    public function index(Request $request): array {
        $results = [];
        $actives = explode(',', $request->input('active'));
        $dateFrom = $request->input(
            'date_from',
            DateTimeHelper::getCloneSelf('NOW', 'UTC')->minusDays(1)->mysqlFormatDate()
        );
        $dateTo = $request->input(
            'date_to',
            DateTimeHelper::getCloneSelf('NOW', 'UTC')->mysqlFormatDate()
        );

        foreach (static::IF_STEP_POOLS as $stepPool) {
            $data = $this->prepareBuilder($stepPool::query(), $actives)
                ->with(['controlPool' => function (BelongsTo $query) {
                    $query->select(['id', 'unique', 'workload_difficulty']);
                }])
                ->where('created_at', '>=', $dateFrom)
                ->where('created_at', '<=', $dateTo)
                ->get();

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
        $currentTimestamp = DateTimeHelper::getCloneSelf()->getTimestamp();

        for ($i = 0; $i < count($results); $i++) {
            switch ($results[$i]->active) {
                case 0:
                    $results[$i]->runtime = DateTimeHelper::getCloneSelf($results[$i]->finish_at)->getTimestamp() - DateTimeHelper::getCloneSelf($results[$i]->start_at)->getTimestamp();
                    break;
                case 5:
                    $results[$i]->runtime = $currentTimestamp - DateTimeHelper::getCloneSelf($results[$i]->start_at)->getTimestamp();
                    break;
                default:
                    $results[$i]->runtime = $currentTimestamp - DateTimeHelper::getCloneSelf($results[$i]->updated_at)->getTimestamp();
                    break;
            }
        }

        return $results;
    }
}