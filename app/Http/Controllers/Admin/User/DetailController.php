<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\Acl;
use App\Model\ImportSupport\User;
use Illuminate\Support\Facades\Input;

class DetailController extends Controller {

    public function getIndex($userId) {
        $user = User::find($userId);
        $this->getView()->addParameter('user', $user);
        
        $acls = Acl::orderBy('key')->get();
        $this->getView()->addParameter('aclList', $acls);
        
        $userAcl = array();
        foreach( $user->getAcl() as $acl){
            $userAcl[] = $acl->key;  
        }
        
        $this->getView()->addParameter('userAcl',$userAcl );
    }

    
    public function postIndex($user_id) {
        $userAcl = array();
        $user = User::find($user_id);
        foreach( $user->getAcl() as $acl){
            $userAcl[$acl->id] = $acl->id;  
        }
        
        
        $acls = Input::get('acl', array());
        foreach ($acls as $key => $acl){
            if(in_array($acl, $userAcl)){
                unset($acls[$key]);
                unset($userAcl[$acl]);
            }
        }
        
        if(count($userAcl)){
            \DB::table('user_acl')
                    ->where('user_id', '=', $user_id)
                    ->whereIn('acl_id', $userAcl)
                    ->delete()
                ;
        }
        
        if(count($acls)){
            $insert = array();
            foreach($acls as $acl){
                $insert[] = array('acl_id' => $acl, 'user_id' => $user_id);
            }
            \DB::table('user_acl')->insert($insert);
        }
        return redirect()->back();
    }

}
