<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Model{
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
	class Acl extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property boolean $is_admin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {
        
        /**
         * @return User
         */
        public static function find($id, $columns = array()) {
            return parent::find($id, $columns);
        }
    }
}


