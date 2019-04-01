<?php

namespace App\Model;

use Eloquent;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereBtf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereCs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereDa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereDateUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereFi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereFromDevelop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereHu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereNl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation wherePl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereSv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Translation whereVisible($value)
 * @mixin \Eloquent
 */
class Translation extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'translation';
    
    protected $guarded = [];
    
   
    public function findBy($column, $text) {
        return $this->where($column, '=', $text)->first();
    }

    public function findByBtf($btf) {
        return $this->findBy('btf', $btf);
    }
    
    


}
