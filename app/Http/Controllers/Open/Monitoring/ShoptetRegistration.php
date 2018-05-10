<?php

namespace App\Http\Controllers\Open\Monitoring;


use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\View;

class ShoptetRegistration extends BaseController {

    public function getIndex() {

        /*
         SELECT * FROM project as p
            JOIN resource_setting as rs ON rs.project_id = p.id AND rs.resource_id = 4
            JOIN resource_eshop as re ON rs.id = re.resource_setting_id AND re.eshop_type_id = 56
            WHERE p.created_at > "2018-05-10 00:00:00"
         */
        $query = MDDatabaseConnections::getMasterAppConnection()
            ->table("project as p")
            ->join("resource_setting as rs", function(JoinClause $join){
                $join->on("rs.project_id", '=', 'p.id')
                    ->where("rs.resource_id", '=', 4);
            })
            ->join("resource_eshop as re", function(JoinClause $join){
                $join->on("re.resource_setting_id", '=', 'rs.id')
                    ->where("re.eshop_type_id", '=', 56);
            })
            ->where("p.created_at", '>', '2018-05-10 00:00:00')
            ->orderBy("p.created_at", 'DESC')
            ->select(['p.created_at', 'p.id', 'p.user_id'])
        ;

        $data = $query->get();

        View::share("projectsCount", count($data));
        View::share("projects", $data);
    }
}