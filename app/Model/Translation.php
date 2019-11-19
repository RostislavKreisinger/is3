<?php

namespace App\Model;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Model\Translation
 *
 * @property int $id
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property string $btf
 * @property string|null $attributes
 * @property string $remark
 * @property string|null $path
 * @property string|null $cs
 * @property string|null $en
 * @property string|null $nl
 * @property string|null $sk
 * @property string|null $de
 * @property string|null $pl
 * @property string|null $fr
 * @property string|null $ru
 * @property string|null $hu
 * @property string|null $es
 * @property string|null $fi
 * @property string|null $sv
 * @property string|null $no
 * @property string|null $da
 * @property bool $test
 * @property bool $from_develop
 * @property bool|null $usage
 * @property bool|null $visible
 * @property bool|null $archived
 * @method static Builder|Translation whereArchived($value)
 * @method static Builder|Translation whereAttributes($value)
 * @method static Builder|Translation whereBtf($value)
 * @method static Builder|Translation whereCs($value)
 * @method static Builder|Translation whereDa($value)
 * @method static Builder|Translation whereDateCreated($value)
 * @method static Builder|Translation whereDateUpdated($value)
 * @method static Builder|Translation whereDe($value)
 * @method static Builder|Translation whereEn($value)
 * @method static Builder|Translation whereEs($value)
 * @method static Builder|Translation whereFi($value)
 * @method static Builder|Translation whereFr($value)
 * @method static Builder|Translation whereFromDevelop($value)
 * @method static Builder|Translation whereHu($value)
 * @method static Builder|Translation whereId($value)
 * @method static Builder|Translation whereNl($value)
 * @method static Builder|Translation whereNo($value)
 * @method static Builder|Translation wherePath($value)
 * @method static Builder|Translation wherePl($value)
 * @method static Builder|Translation whereRemark($value)
 * @method static Builder|Translation whereRu($value)
 * @method static Builder|Translation whereSk($value)
 * @method static Builder|Translation whereSv($value)
 * @method static Builder|Translation whereTest($value)
 * @method static Builder|Translation whereUsage($value)
 * @method static Builder|Translation whereVisible($value)
 * @mixin Eloquent
 */
class Translation extends MasterModel {
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $table = 'translation';
    protected $guarded = [];
}
