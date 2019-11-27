<?php

namespace App\Model\ImportPools;

/**
 * Class StornoOrderStatus
 *
 * @package App\Model\ImportPools
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
class StornoOrderStatus extends PoolModel {
    public $timestamps = false;

    protected $table = 'storno_order_status';
    
    public static function getAllUnsolvedStornoOrderStatuses() {
        return self::where('active', '=', 0);
    }
}
