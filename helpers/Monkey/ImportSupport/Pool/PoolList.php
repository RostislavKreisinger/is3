<?php

namespace Monkey\ImportSupport\Pool;

/**
 * Description of Pools
 *
 * @author Tomas
 */
class PoolList {
    /**
     * @var Pool
     */
    protected $testerPool;

    /**
     * @return Pool
     */
    public function getTesterPool() {
        return $this->testerPool;
    }

    /**
     * @param Pool $testerPool
     */
    public function setTesterPool(Pool $testerPool) {
        $this->testerPool = $testerPool;
    }
}
