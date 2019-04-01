<?php

namespace App\Model;

use Eloquent;

/**
 * App\Model\Tariff
 *
 * @property int $id
 * @property string|null $btf_name
 * @property string|null $code
 * @property string|null $group_code
 * @property bool $for_agency
 * @property string|null $price DEPRECATED
 * @property int|null $count_of_order DEPRECATED
 * @property float|null $price_per_order DEPRECATED
 * @property int|null $count_projects DEPRECATED
 * @property int|null $count_share DEPRECATED
 * @property int|null $type DEPRECATED
 * @property float|null $valid_for NULL - unlimited - dny.hodiny, kde 3 dny a 5 hodin je 3.05
 * @property int|null $order_limit NULL - unlimited, 0-999999 pocet objednavek
 * @property int|null $project_limit NULL - unlimited, 0-999999 pocet projektu
 * @property int|null $resource_limit NULL - unlimited, 0-999999 pocet zdroju
 * @property int|null $share_limit NULL - unlimited, 0-999999 pocet sdilniku
 * @property int|null $visible DEPRECATED
 * @property int|null $order tariff sorting in lists
 * @property bool|null $public boolean - public for all customers
 * @property bool|null $custom boolean - custom tariff (need record in user_custom_tariff)
 * @property bool|null $active boolean
 * @property bool|null $level more expensive tariff has higher level (custom = 255)
 * @property string|null $icon_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereBtfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereCountOfOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereCountProjects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereCountShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereForAgency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereGroupCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereOrderLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff wherePricePerOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereProjectLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereResourceLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereShareLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereValidFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Tariff whereVisible($value)
 * @mixin \Eloquent
 */
class Tariff extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff';
    
    protected $guarded = [];
 
}
