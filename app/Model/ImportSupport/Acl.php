<?php

namespace App\Model\ImportSupport;


use Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Description of Acl
 *
 * @author Tomas
 * @property integer $id
 * @property string $key
 * @method static Builder|Acl whereId($value)
 * @method static Builder|Acl whereKey($value)
 * @mixin Eloquent
 */
class Acl extends ISModel {
    protected $table = 'acl';

    public function getPath() {
        $path = explode('.', $this->key);
        unset($path[count($path)-1]);
        return implode('.', $path);
    }
    
    public function getEnd() {
        $path = explode('.', $this->key);
        return $path[count($path)-1];
    }
}
