<?php

namespace App\Model;

use Eloquent;

class Translation extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'translation';
    
    protected $guarded = [];
    
   
    public function findBy($column, $text) {
        return $this->where($column, '=', $text)->first();
    }

    public function findByBtf($btf) {
        return $this->findBy('btf', $btf);
    }
    
    


}
