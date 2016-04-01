<?php

namespace App\Model;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Model\Acl as AclModel;
use Monkey\Acl\Acl;
use Monkey\Acl\AclManager;

class User extends Eloquent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract {

    use Authenticatable, Authorizable, CanResetPassword;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
        protected $aclManager;


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
    
    public function can($ability, $arguments = array()) {
        return $this->getAclManager()->hasAcl($ability);
    }
    
    protected function getAclManager() {
        if($this->aclManager == null){
            $this->aclManager = new AclManager($this, new Acl());
        }
        return $this->aclManager;
    }
    
            
            

}
