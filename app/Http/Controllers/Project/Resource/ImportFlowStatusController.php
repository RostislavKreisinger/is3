<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Controller;
use App\Model\ImportPools\IFControlPool;
use App\Model\ImportPools\IFDailyPool;
use App\Model\ImportPools\IFHistoryPool;
use App\Model\ImportPools\IFPeriodPool;
use Exception;
use Illuminate\Http\RedirectResponse;
use Monkey\Constants\ImportFlow\Pools\Pools;
use Monkey\ImportSupport\Project;
use stdClass;

/**
 * Class ImportFlowStatusController
 * @package App\Http\Controllers\Project\Resource
 */
class ImportFlowStatusController extends Controller {
    /**
     * @param int $projectId
     * @param int $resourceId
     * @return array
     */
    public function getIndex($projectId, $resourceId): array {
        $results = [];
        $dailyPool = IFDailyPool::whereProjectId($projectId)->whereResourceId($resourceId)->first();
        $historyPool = IFHistoryPool::whereProjectId($projectId)->whereResourceId($resourceId)->first();

        if ($dailyPool instanceof IFDailyPool) {
            $results["daily"] = $dailyPool;
        } else {
            $results["daily"] = new stdClass();
        }

        if ($historyPool instanceof IFHistoryPool) {
            $results["history"] = $historyPool;
        } else {
            $results["history"] = new stdClass();
        }

        $results["daily"]->status = $this->getPeriodStatusName($dailyPool);
        $results["history"]->status = $this->getPeriodStatusName($historyPool);
        return $results;
    }

    /**
     * @param $projectId
     * @param $resourceId
     * @return array
     * @throws Exception
     */
    public function getResourceInfo($projectId, $resourceId): array {
        return [
            'resource' => $this->getImportFlowStatusForProject($projectId, Project::find($projectId)->getResource($resourceId))
        ];
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @return array
     */
    public function activateDaily(int $projectId, int $resourceId): array {
        $dailyPool = IFDailyPool::whereProjectId($projectId)->whereResourceId($resourceId)->first();
        return $this->activatePool($dailyPool);
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @return array
     */
    public function activateHistory(int $projectId, int $resourceId): array {
        $historyPool = IFHistoryPool::whereProjectId($projectId)->whereResourceId($resourceId)->first();
        return $this->activatePool($historyPool);
    }

    /**
     * @param IFPeriodPool $pool
     * @return array
     */
    private function activatePool(IFPeriodPool $pool): array {
        if ($pool->importPool === null || $pool->importPool->active === Pools::INACTIVE) {
            $pool->activate()->save();

            return [
                'status' => 'success',
                'message' => 'Pool was activated'
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => "Import step with unique {$pool->unique} was not finished yet"
            ];
        }
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @param string $unique
     * @return RedirectResponse
     */
    public function raiseDifficulty(int $projectId, int $resourceId, string $unique) {
        $controlPool = IFControlPool::whereUnique($unique)->first();
        $controlPool->raiseDifficulty();
        $message = 'Successfully raised difficulty of flow!';
        return back()->with('message', $message);
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @param string $unique
     * @return RedirectResponse
     */
    public function reduceDifficulty(int $projectId, int $resourceId, string $unique) {
        $controlPool = IFControlPool::whereUnique($unique)->first();
        $controlPool->reduceDifficulty();
        $message = 'Successfully decreased difficulty of flow!';
        return back()->with('message', $message);
    }

    /**
     * @param IFPeriodPool|null $pool
     * @return string
     */
    private function getPeriodStatusName($pool): string {
        if ($pool === null) {
            return 'missing';
        }

        switch ($pool->active) {
            case 0:
                return 'inactive';
            case 1:
                return 'active';
            case 2:
            case 5:
                return 'running';
            case 3:
            default:
                return 'error';
        }
    }
}
