<?php

namespace App\Http\Controllers\Open\Monitoring\Onboarding\Platform;



use App\Helpers\Monitoring\Onboarding\Platform;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\Objects\Project;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Arrays;
use Monkey\View\View;
use \Exception;

class BaseController extends \App\Http\Controllers\Open\Monitoring\Onboarding\BaseController {


    /**
     * @var bool
     */
    private $isDateToNow = false;



    /**
     * @return string
     */
    public static function getMethodIndex() {
        return static::getMethodAction();
    }

    /**
     * @return string
     */
    public static function getMethodData() {
        return static::getMethodAction("getData");
    }

    /**
     * @param $route
     * @return array|mixed
     */
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
        $this->setPageRefresh(5000);

        $query = $this->getRequest()->input();
        $dataQuery = [];

        $dataQuery["platformCode"] = $this->getPlatformCode();
        View::share("platformCode", $this->getPlatformCode());

        $dataQuery["date_from"] = $this->getDateFrom()->mysqlFormatDate();
        View::share("date_from", $this->getDateFrom()->mysqlFormatDate());

        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);

        $dateTo = "NOW";
        if(!$this->isDateToNow()){
            $dateTo = $this->getDateTo()->mysqlFormatDate();
        }
        View::share("date_to", $dateTo);

        View::share("query", $query);
        View::share("dataQuery", array_merge($dataQuery, $query));
        View::share("limited_view", $this->getRequest()->exists("limited_view"));
    }


    /**
     * @return Project[] projects array
     */
    protected function getShoptetProjects($columns = array()) {

        $dateFrom = $this->getDateFrom()->mysqlFormatDate();
        $dateTo = $this->getDateTo()->mysqlFormatDate();
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
                    ->whereIn("rs.active", [0, 1, 2, 3])
                    ->whereNull("rs.deleted_at")
                ;
            })
            ->join("resource_eshop as re", function(JoinClause $join){
                $join->on("re.resource_setting_id", '=', 'rs.id')
                    ->whereIn("re.eshop_type_id", $this->getEshopTypes())
                    ->whereNull("re.deleted_at")
                ;
            })
            ->join("user as u", function(JoinClause $join){
                $join->on("p.user_id", '=', 'u.id')
                    ->whereNull("u.deleted_at")
                ;
            })
            ->where("rs.created_at", '>', "{$dateFrom} 00:00:00")
            ->where("rs.created_at", '<', "{$dateTo} 23:59:59")
            ->where("p.project_type_id", "=", 7)
            ->whereNull("p.deleted_at")
            ->orderBy("p.created_at", 'DESC')
            ->select(array_merge(['rs.created_at', 'p.id', 'p.user_id', 'rs.active as rs_active', 're.eshop_type_id as eshop_type_id'], $columns))
        ;

        $data = $query->get();
        $projects = array();
        foreach ($data as $project){
            $projects[$project->id] = new Project($project);
        }

        return $projects;
    }


    /**
     * @return DateTimeHelper
     */
    final protected function getDateFrom() {
        $date = Input::get("date_from", '2018-05-10');
        return new DateTimeHelper($date);
    }

    /**
     * @return DateTimeHelper
     */
    final protected function getDateTo() {
        $date = Input::get("date_to", "NOW");
        $this->resolveDateToNow($date);
        return new DateTimeHelper($date);
    }


    /**
     * @return bool
     */
    final protected function isDateToNow() {
        $this->getDateTo();
        return $this->isDateToNow;
    }

    /**
     * @param string $date
     */
    final protected function resolveDateToNow($date) {
        $this->isDateToNow = (strtolower($date) == "now");
    }


    /**
     * @param Project[] $projects
     * @return array
     * @throws Exception
     */
    protected function getProjectPlatformCounts($projects) {
        $projectPlatformCounts = [];
//        if($this->getPlatformCode() == Platform::ALL){
//            return ["Projects" => count($projects)];
//        }

        foreach ($projects as $project){
            $platformCode = Platform::getPlatformCode($project->eshop_type_id);
            if(!array_key_exists($platformCode, $projectPlatformCounts)){
                $projectPlatformCounts[$platformCode] = 0;
            }
            $projectPlatformCounts[$platformCode]++;
        }
        return $projectPlatformCounts;
    }

    /**
     * @param array|Project[] $projects
     * @param int $limit
     * @return array|Project[]
     */
    final protected function filterProjectsCount(array $projects, $limit = 50) {
        if($this->getRequest()->exists("limited_view")){
            return Arrays::limit($projects, $limit);
        }
        return $projects;
    }

}
