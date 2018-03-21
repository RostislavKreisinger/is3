<?php

namespace App\Http\Controllers\Search;


use App\Http\Controllers\User\DetailController;
use App\Model\Client;
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
class ClientController extends BaseController {
    public function getIndex() {
        $search = Input::get('search', null);

        if (is_numeric($search)) {
            $client = Client::find($search);

            if ($client) {
                return Redirect::action(DetailController::routeMethod('getIndex'), ['user_id' => $client->user_id]);
            }
        }

        View::share('clientSearch', $search);
        
        $clients = Client::where(function(Builder $where) use ($search) {
                    $where->orWhere('email', 'like', "%$search%");
                })
                ->get();
                
        $this->getView()->addParameter('clients', $clients);
    }
}