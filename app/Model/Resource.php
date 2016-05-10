<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;

class Resource extends Model {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'resource';
    protected $guarded = [];
    

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('id', function(Builder $builder) {
            $builder->whereNotIn('resource.id', [1, 19]);
        });
    }

    public function getResourceErrors() {
        return $this->builderResourceErrors()->get();
    }

    public function builderResourceErrors() {
        return $this->hasMany(ImportSupport\ResourceError::class, 'resource_id');
    }

}
