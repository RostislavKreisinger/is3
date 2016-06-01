<?php

namespace App\Model\ImportSupport;



class ResourceError extends Model {

    protected $table = 'resource_error';

    protected $guarded = [];

        
    public function getResource() {
        return \App\Model\Resource::find($this->resource_id); // ->first();
    }
    

}
