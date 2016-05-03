<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Pool;

/**
 * Description of Pools
 *
 * @author Tomas
 */
class PoolList {
    
    /**
     *
     * @var Pool 
     */
    protected $historyPool;
    
    /**
     *
     * @var Pool 
     */
    protected $dailyPool;
    
    /**
     *
     * @var Pool 
     */
    protected $testerPool;
    
    public function __construct() {
//        $this->setDailyPool(new Pool());
//        $this->setHistoryPool(new Pool());
//        $this->setStartPool(new Pool());
    }
    
    public function getHistoryPool() {
        return $this->historyPool;
    }

    public function getDailyPool() {
        return $this->dailyPool;
    }

    public function getTesterPool() {
        return $this->testerPool;
    }

    public function setHistoryPool(Pool $historyPool) {
        $this->historyPool = $historyPool;
    }

    public function setDailyPool(Pool $dailyPool) {
        $this->dailyPool = $dailyPool;
    }

    public function setTesterPool(Pool $testerPool) {
        $this->testerPool = $testerPool;
    }


    
}
