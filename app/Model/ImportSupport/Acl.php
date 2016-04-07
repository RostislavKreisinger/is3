<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportSupport;



/**
 * Description of Acl
 *
 * @author Tomas
 * @property integer $id
 * @property string $key
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Acl whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Acl whereKey($value)
 * @mixin \Eloquent
 */
class Acl extends \Eloquent {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    protected $table = 'acl';
    
    
}
