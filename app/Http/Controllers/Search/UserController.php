<?php

namespace App\Http\Controllers\Search;


use App\Http\Controllers\User\DetailController;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Monkey\Helpers\Strings;
use Monkey\View\View;
use Redirect;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class UserController extends BaseController {
    
    public function getIndex() {
        $search = $originSearch = Input::get('search', null);

        if (!is_numeric($search)) {
            $search = Strings::alpha2id($search);
        }

        /*
        $alpha2id = alpha2id($search);
        if(intValue($alpha2id) && $alpha2id > 0){
            $search = $alpha2id;
        }
        */

        if (is_numeric($search)) {
            $user = User::find($search);

            if (!empty($user)) {
                return Redirect::action(DetailController::routeMethod('getIndex'), ['user_id' => $user->id]);
            }
        }

        $this->getView()->addParameter('search', $search);
        View::share('userSearch', $originSearch);
        
        $users = User::where(function(Builder $where) use ($originSearch) {
                    $where->orWhere('email', 'like', "%$originSearch%");
                })
                ->get();
                
        $this->getView()->addParameter('users', $users);
    }
}