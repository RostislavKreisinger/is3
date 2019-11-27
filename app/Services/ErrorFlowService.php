<?php

namespace App\Services;


use App\Model\ImportPools\IFDailyPool;
use App\Model\ImportPools\IFHistoryPool;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ErrorFlowService
 * @package App\Services
 */
class ErrorFlowService {
    /**
     * @return IFDailyPool[]|Collection
     */
    public function getDaily(): Collection {
        return IFDailyPool::whereActive(3)->get();
    }

    /**
     * @return IFHistoryPool[]|Collection
     */
    public function getHistory(): Collection {
        return IFHistoryPool::whereActive(3)->get();
    }

    /**
     * @return IFDailyPool[]|IFHistoryPool[]|Collection
     */
    public function getDailyAndHistory(): Collection {
        return $this->getDaily()->merge($this->getHistory());
    }
}
