<?php

namespace App\Model;

use Eloquent;

/**
 * App\Model\Model
 *
 * @mixin \Eloquent
 */
class Model extends Eloquent {

   
    public function findById($id) {
        return $this->find($id, array('id'));
    }

}
