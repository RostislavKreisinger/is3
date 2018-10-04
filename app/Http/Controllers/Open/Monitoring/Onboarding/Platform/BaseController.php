<?php

namespace App\Http\Controllers\Open\Monitoring\Onboarding\Platform;



use App\Helpers\Monitoring\Onboarding\Platform;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\Objects\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\View\View;

class BaseController extends \App\Http\Controllers\Open\Monitoring\Onboarding\BaseController {


    /**
     * @var Request
     */
    private $request;

    /**
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {
        parent::__construct();
        $this->setPageRefresh(5000);
        $this->setRequest($request);
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

    /**
     * @return string
     */
    final protected function getPlatformCode() {
        return $this->getRequest()->route("platform");
    }

    /**
     * @return int[]
     * @throws \Exception
     */
    private function getEshopTypes() {
        $platformCode = $this->getPlatformCode();
        return Platform::getEshopType($platformCode);
    }


    public function getIndex() {
        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);
        View::share("platformCode", $this->getPlatformCode());
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
    protected function getShoptetProjects($columns = array()) {

        $dateFrom = $this->getDateFrom();
        $dateTo = $this->getDateTo();


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
                    ->where("rs.resource_id", '=', 4)
                    ->whereIn("rs.active", [0, 1, 2, 3]);
            })
            ->join("resource_eshop as re", function(JoinClause $join){
                $join->on("re.resource_setting_id", '=', 'rs.id')
                    ->whereIn("re.eshop_type_id", $this->getEshopTypes());
            })
            ->where("rs.created_at", '>', "{$dateFrom} 00:00:00")
            ->where("rs.created_at", '<', "{$dateTo} 23:59:59")
            ->orderBy("p.created_at", 'DESC')
            ->select(array_merge(['rs.created_at', 'p.id', 'p.user_id', 'rs.active as rs_active', 're.eshop_type_id as eshop_type_id'], $columns))
        ;
//        vde([$query->toSql(), $query->getBindings()]);
//        vdQuery($query);
//        vde("exit");
        // vde([$query->toSql(), $query->getBindings()]);
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

    /**
     * @return string
     */
    final protected function getDateFrom() {
        return Input::get("date_from", '2018-05-10');
    }

    /**
     * @return string
     */
    final protected function getDateTo() {
        $dth = new DateTimeHelper();
        return Input::get("date_to", $dth->mysqlFormatDate());
    }

    /**
     * @return Request
     */
    protected function getRequest() {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return BaseController
     */
    private function setRequest(Request $request) {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Project[] $projects
     * @return array
     * @throws \Exception
     */
    protected function getProjectPlatformCounts($projects) {
        $projectPlatformCounts = [];
        if($this->getPlatformCode() == Platform::ALL){
            return ["Projects" => count($projects)];
        }

        foreach ($projects as $project){
            $platformCode = Platform::getPlatformCode($project->eshop_type_id);
            if(!array_key_exists($platformCode, $projectPlatformCounts)){
                $projectPlatformCounts[$platformCode] = 0;
            }
            $projectPlatformCounts[$platformCode]++;
        }
        return $projectPlatformCounts;
    }

}