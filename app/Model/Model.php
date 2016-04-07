<?php

namespace App\Model;

use Eloquent;

class Model extends Eloquent {

   
    public function findById($id) {
        return $this->find($id, array('id'));
    }

}
