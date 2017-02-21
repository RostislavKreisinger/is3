<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Search;

use App\Http\Controllers\User\DetailController;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Redirect;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class UserController extends BaseController {
    
    public function getIndex() {
        $search = $originSearch =  Input::get('search', null);
        
        $alpha2id = alpha2id($search);
        if(intValue($alpha2id) && $alpha2id > 0){
            $search = $alpha2id;
        }
        if(intValue($search)){
            $user = User::find($search);
            if(!empty($user)){
                return Redirect::action(DetailController::routeMethod('getIndex'), ['user_id' => $user->id]);
            }
        }
        
        $users = User::where(function(Builder $where) use ($originSearch) {
                    $where->orWhere('email', 'like', "%$originSearch%");
                })
                ->get();
                
        $this->getView()->addParameter('users', $users);
        
    }
    
}
