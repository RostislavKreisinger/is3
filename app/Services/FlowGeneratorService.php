<?php

namespace App\Services;


use App\Model\ImportPools\IFControlPool;
use App\Model\ImportPools\IFHistoryReload;
use App\Model\ImportPools\IFImportPool;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Monkey\DateTime\DateTimeHelper;

/**
 * Class FlowGeneratorService
 * @package App\Services
 */
class FlowGeneratorService {
    /**
     * @var int $projectId
     */
    private $projectId;

    /**
     * @var int $resourceId
     */
    private $resourceId;

    /**
     * FlowGeneratorService constructor.
     * @param int $projectId
     * @param int $resourceId
     */
    public function __construct(int $projectId, int $resourceId) {
        $this->setProjectId($projectId);
        $this->setResourceId($resourceId);
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param int $split
     * @return bool
     * @throws \Exception
     */
    public function generate(string $dateFrom, string $dateTo, int $split): bool {
        if (!$this->canGenerate()) {
            return false;
        }

        $dateFromHelper = DateTimeHelper::getCloneSelf($dateFrom, 'UTC');
        $dateToHelper = DateTimeHelper::getCloneSelf($dateTo, 'UTC');
        $runTime = DateTimeHelper::getCloneSelf('NOW', 'UTC');

        foreach ($this->getDateRanges($dateFromHelper, $dateToHelper, $split) as $range) {
            $controlPool = $this->createNewControlPool($range['from'], $range['to'], $runTime->mysqlFormat());
            $controlPool->save();
            $this->createNewImportPool($range['from'], $range['to'], $controlPool->unique)->save();
            $this->createNewHistoryReloadRecord($controlPool->unique)->save();
            $runTime->plusMinutes(30);
        }

        return true;
    }

    /**
     * @param DateTimeHelper $dateFrom
     * @param DateTimeHelper $dateTo
     * @param int $split
     * @return array
     */
    private function getDateRanges(DateTimeHelper $dateFrom, DateTimeHelper $dateTo, int $split): array {
        $ranges = [];

        $currentDateTo = $dateTo->getCloneThis();

        do {
            $currentDateFrom = $currentDateTo->getCloneThis()->minusDays($split);

            if ($currentDateFrom->getTimestamp() < $dateFrom->getTimestamp()) {
                $currentDateFrom = $dateFrom->getCloneThis();
            }

            $ranges[] = ['from' => $currentDateFrom->mysqlFormatDate(), 'to' => $currentDateTo->mysqlFormatDate()];
            $currentDateTo = $currentDateFrom;
        } while ($dateFrom->getTimestamp() < $currentDateTo->getTimestamp());

        return $ranges;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $runTime
     * @return IFControlPool
     * @throws \Exception
     */
    private function createNewControlPool(string $dateFrom, string $dateTo, string $runTime): IFControlPool {
        $controlPool = new IFControlPool;
        $controlPool->project_id = $this->getProjectId();
        $controlPool->resource_id = $this->getResourceId();
        $controlPool->setHistory();
        $controlPool->setLowPriority();
        $controlPool->date_from = $dateFrom;
        $controlPool->date_to = $dateTo;
        $controlPool->run_time = $runTime;
        $controlPool->setInRepair();
        return $controlPool;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $unique
     * @return IFImportPool
     */
    private function createNewImportPool(string $dateFrom, string $dateTo, string $unique): IFImportPool {
        $importPool = new IFImportPool;
        $importPool->project_id = $this->getProjectId();
        $importPool->resource_id = $this->getResourceId();
        $importPool->unique = $unique;
        $importPool->date_from = $dateFrom;
        $importPool->date_to = $dateTo;
        return $importPool;
    }

    /**
     * @param string $unique
     * @return IFHistoryReload
     */
    private function createNewHistoryReloadRecord(string $unique): IFHistoryReload {
        $historyReload = new IFHistoryReload;
        $historyReload->project_id = $this->getProjectId();
        $historyReload->unique = $unique;
        return $historyReload;
    }

    private function canGenerate(): bool {
        $historyReload = IFHistoryReload::where('project_id', $this->getProjectId())->get();
        $pools = IFControlPool::with(['outputPool' => function (HasOne $query) {
            $query->withTrashed();
        }])->withTrashed()->whereIn('unique', array_column($historyReload->toArray(), 'unique'))->get();

        foreach ($pools as $pool) {
            if (!empty($pool->deleted_at) || (!empty($pool->outputPool) && (!empty($pool->outputPool->deleted_at) || !empty($pool->outputPool->finish_at)))) {
                foreach ($historyReload as $record) {
                    if ($record->unique === $pool->unique) {
                        $record->delete();
                    }
                }
            }
        }

        return $historyReload->count() === 0;
    }

    /**
     * @return int
     */
    public function getProjectId(): int {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     * @return FlowGeneratorService
     */
    public function setProjectId(int $projectId): FlowGeneratorService {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * @return int
     */
    public function getResourceId(): int {
        return $this->resourceId;
    }

    /**
     * @param int $resourceId
     * @return FlowGeneratorService
     */
    public function setResourceId(int $resourceId): FlowGeneratorService {
        $this->resourceId = $resourceId;
        return $this;
    }
}