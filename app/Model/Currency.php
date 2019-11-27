<?php

namespace App\Model;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Model\Currency
 *
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $public
 * @property bool|null $active
 * @property int|null $order
 * @method static Builder|Currency whereActive($value)
 * @method static Builder|Currency whereBtfName($value)
 * @method static Builder|Currency whereCode($value)
 * @method static Builder|Currency whereCreatedAt($value)
 * @method static Builder|Currency whereDecimalPoint($value)
 * @method static Builder|Currency whereDecimals($value)
 * @method static Builder|Currency whereId($value)
 * @method static Builder|Currency whereOrder($value)
 * @method static Builder|Currency wherePublic($value)
 * @method static Builder|Currency whereRound($value)
 * @method static Builder|Currency whereSign($value)
 * @method static Builder|Currency whereSignPlacement($value)
 * @method static Builder|Currency whereSignSpace($value)
 * @method static Builder|Currency whereThousandsSeparator($value)
 * @method static Builder|Currency whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Currency extends MasterModel {
    protected $table = 'currency_names';
    protected $guarded = [];
}
