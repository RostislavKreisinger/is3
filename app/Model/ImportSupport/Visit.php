<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportSupport;

use App\Model\Model;



/**
 * App\Model\ImportSupport\Visit
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $visited_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereVisitedAt($value)
 * @mixin \Eloquent
 */
class Visit extends Model {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    protected $table = 'visit';
    
    public $timestamps = false;
    
    protected $guarded = [];
    
    
    
    
}
