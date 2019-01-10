<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class IFControlPool
 * @package App\Model\ImportPools
 */
class IFControlPool extends IFPool {
    /**
     * @var string $table
     */
    protected $table = 'if_control';

    /**
     * Raises workload_difficulty by 1 and updates model in DB
     *
     * @return IFControlPool
     */
    public function raiseDifficulty(): self {
        $this->workload_difficulty++;
        $this->save();
        return $this;
    }

    /**
     * @return HasOne
     */
    public function importPool(): HasOne {
        return $this->hasOne(IFImportPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function etlPool(): HasOne {
        return $this->hasOne(IFEtlPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function calcPool(): HasOne {
        return $this->hasOne(IFCalcPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function outputPool(): HasOne {
        return $this->hasOne(IFOutputPool::class, 'unique', 'unique');
    }
}