<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;



use App\Http\Controllers\Open\Monitoring\Shoptet\Objects\Project;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\View;

class BaseController extends \App\Http\Controllers\Open\Monitoring\BaseController {

    public function __construct() {
        parent::__construct();
        $this->setPageRefresh(5000);

    }

    protected function cleanRoute($route) {
        $pathArray = parent::cleanRoute($route);
        if(strpos($route, "@getData") !== false) {
            $tmp = $pathArray[count($pathArray) - 1];
            $pathArray[count($pathArray) - 1] = "data";
            $pathArray[] = $tmp;
        }
        return $pathArray;
    }

    public function getIndex() {
        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);
    }

    /**
     * @return mixed
     */
    protected function isDebug() {
        return Input::exists("debug");
    }

    /**
     * @return Project[] projects array
     */
    protected function getShoptetProjects() {

        $dateFrom = Input::get("date_from", '2018-05-10 00:00:00');
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
            ->where("rs.created_at", '>', $dateFrom)
            ->orderBy("p.created_at", 'DESC')
            ->select(['rs.created_at', 'p.id', 'p.user_id', 'rs.active as rs_active'])
        ;
        // vdQuery($query);

        $data = $query->get();
        $projects = array();
        foreach ($data as $project){
            $projects[$project->id] = new Project($project);
        }

        return $projects;
    }

    /**
     * @param int $pageRefresh
     */
    protected function setPageRefresh($pageRefresh) {
        View::share("pageRefresh", $pageRefresh);
    }

}