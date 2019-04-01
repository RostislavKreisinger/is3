<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportPools;

use App\Model\ImportPools\Model;
use Illuminate\Database\Eloquent\Builder;



/**
 * App\Model\ImportPools\StornoOrderStatus
 *
 * @property int $id
 * @property string $name
 * @property int|null $clean_order_status_id id order status form some ELT clean table of order statuses
 * @property bool $active 0 - nerozhodnuto; 1- platny storno order status; 2 - neplatny storno order status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\StornoOrderStatus whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\StornoOrderStatus whereCleanOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\StornoOrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\StornoOrderStatus whereName($value)
 * @mixin \Eloquent
 */
class StornoOrderStatus extends Model {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    public $timestamps = false;

    protected $table = 'storno_order_status';
    
    public static function getAllUnsolvedStornoOrderStatuses (){
        return self::where('active', '=', 0);
    }
    
    
    
    
}
