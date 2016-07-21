<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource;

use Monkey\ImportSupport\Resource;

/**
 * Description of ResourceV1
 *
 * @author Tomas
 */
class ResourceWithoutHistoryV1 extends ResourceV1 {

    public function getStateHistory() {
        return Resource::STATUS_DONE;
    }

}
