<?php

namespace App\Model\ImportSupport;


use App\Model\ImportSupport\Acl as AclModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Monkey\Acl\Acl;
use Monkey\Acl\AclManager;
use Monkey\Acl\IAclUser;

/**
 * Class User
 *
 * @package App\Model\ImportSupport
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property bool|null $is_admin
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $last_visit
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ImportSupport\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereLastVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportSupport\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ImportSupport\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ImportSupport\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends ISModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    IAclUser {

    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;
    
    protected $aclManager;

    protected $table = 'user';

    protected $guarded = [];

        
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAcl() {
         return $this->belongsToMany(AclModel::class, 'user_acl')->get();
    }
    
    public function isAdmin() {
        return (bool) $this->is_admin;
    }
   
    
    public function can($ability, $arguments = array()) {
        return $this->getAclManager()->hasAcl($ability);
    }
    
    public function getAclManager() {
        if ($this->aclManager == null) {
            $this->aclManager = new AclManager($this, new Acl());
        }

        return $this->aclManager;
    }
}
