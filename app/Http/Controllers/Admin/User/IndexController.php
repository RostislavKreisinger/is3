<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\User;
use DB;
use Monkey\DateTime\DateTimeHelper;
use Monkey\View\View;
use stdClass;

class IndexController extends Controller {
    
    private $emptyGraphObject = null;

    public function getIndex() {
        $users = User::all();
        $this->getView()->addParameter('users', $users);
        
        $visits = DB::connection()->table('user')
                ->join('visit as v', 'v.user_id', '=', 'user.id')
                ->select([DB::raw('DATE(v.visited_at) as date_id'), 'user.id', 'user.email', DB::raw('COUNT(v.id) as count')])
                ->groupBy('user.id')
                ->groupBy(DB::raw('DATE(v.visited_at)'))
                ;
        $visitResult = $this->getDateRange($users);
        foreach ($visits->get() as $row) {
            if(!isset($visitResult[$row->date_id])){
                continue;
            }
            $visitResult[$row->date_id]->{"u_{$row->id}"} = $row->count;
        }
        $visitResult = array_values($visitResult);
        $userList = array();
        foreach($users as $user){
            $userList[$user->id] = $user->email;
        }
        View::share('visitResult', json_encode($visitResult));
        View::share('userList', $userList);
        
    }
    
    
    private function getDateRange($userList) {
        $list = array();
        $dthEnd = new DateTimeHelper();
        $dthStart = (new DateTimeHelper())->changeDays(-7);
        while($dthStart <= $dthEnd){
            $obj = $this->getEmptyGraphObject($userList);
            $obj->date_id = $dthStart->mysqlFormatDate();
            $list[$dthStart->mysqlFormatDate()] = $obj;
            $dthStart->changeDays(+1);
        }
        return $list;
    }


    private function getEmptyGraphObject($userList) {
        if($this->emptyGraphObject === null){
            $tmp = new stdClass();
            foreach ($userList as $user){
                $tmp->{"u_{$user->id}"} = 0;
            }
            $this->emptyGraphObject = $tmp;
        }
        return clone $this->emptyGraphObject;
    }


}
