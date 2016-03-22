<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     
    protected $fillable = [
        'name', 'email', 'password',
    ];
*/
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAcl() {
        $this->belongsToMany(Acl::class);
    }

}
