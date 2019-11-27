<?php

namespace App\Model\ImportSupport;

/**
 * Class Visit
 *
 * @package App\Model\ImportSupport
 * @property int $id
 * @property int|null $user_id
 * @property string|null $visited_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\Visit whereVisitedAt($value)
 * @mixin \Eloquent
 */
class Visit extends ISModel {
    protected $table = 'visit';
    
    public $timestamps = false;
    
    protected $guarded = [];
}
