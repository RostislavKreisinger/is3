<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\ApiClient;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {
    public function getIndex() {
        if (!Auth::user()->can('user.list')) {
            return redirect('/');
        }

        $api = new ApiClient;
        $results = $api->index('base/users');

        $users = collect(array_map(function (array $userArray) {
            $user = new User;
            $user->id = $userArray["id"];
            return $user->fill($userArray["attributes"]);
        }, $results['data']));

        $this->getView()->addParameter('users', $users);
    }
}