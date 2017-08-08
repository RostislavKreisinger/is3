<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportPools;

use Eloquent;

class IFControlPool extends Eloquent {

    public $timestamps = true;

    protected $connection = "md_import_flow";

    protected $table = 'if_control';

}
