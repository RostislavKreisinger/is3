<?php

namespace App\Model\ImportSupport;



/**
 * App\Model\ImportSupport\ResourceError
 *
 * @property int $id
 * @property int|null $resource_id
 * @property string|null $error
 * @property string|null $solution
 * @property string|null $btf_for_user
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereBtfForUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereSolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\ResourceError whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResourceError extends Model {

    protected $table = 'resource_error';

    protected $guarded = [];

        
    public function getResource() {
        return \App\Model\Resource::find($this->resource_id); // ->first();
    }
    

}
