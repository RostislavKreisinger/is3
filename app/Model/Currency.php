<?php

namespace App\Model;

/**
 * Class Currency
 *
 * @package App\Model
 * @property int $id
 * @property string $code
 * @property string|null $btf_name
 * @property string|null $sign
 * @property bool|null $decimals
 * @property string|null $decimal_point
 * @property string|null $thousands_separator
 * @property bool|null $round
 * @property bool $sign_space
 * @property bool $sign_placement
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $public
 * @property bool|null $active
 * @property int|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereBtfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereDecimalPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereDecimals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereRound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereSignPlacement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereSignSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereThousandsSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Currency extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'currency_names';
    
    protected $guarded = [];
    
}