<?php

namespace App\Model;

use Eloquent;

/**
 * App\Model\Timezone
 *
 * @property int $id
 * @property string $name
 * @property int $utc_offset
 * @property int|null $country_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Timezone whereUtcOffset($value)
 * @mixin \Eloquent
 */
class Timezone extends MasterModel {


    protected $connection = 'mysql-master-app';
    protected $table = 'timezone';
    
    protected $guarded = [];
    
}
