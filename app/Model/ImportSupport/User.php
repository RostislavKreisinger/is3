<?php

namespace App\Model\ImportSupport;

use App\Model\ImportSupport\Acl as AclModel;
use App\Model\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Monkey\Acl\Acl;
use Monkey\Acl\AclManager;
use Monkey\Acl\IAclUser;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    IAclUser {

    use Authenticatable, Authorizable, CanResetPassword;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
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
//        $builder = $this->belongsToMany(AclModel::class, 'user_acl');
//        vdQuery($builder);
//        return array();
        
         return $this->belongsToMany(AclModel::class, 'user_acl')->get();
    }
    
    public function isAdmin() {
        return (bool) $this->is_admin;
    }
   
    
    public function can($ability, $arguments = array()) {
        return $this->getAclManager()->hasAcl($ability);
    }
    
    public function getAclManager() {
        if($this->aclManager == null){
            $this->aclManager = new AclManager($this, new Acl());
        }
        return $this->aclManager;
    }
    
            
            

}
