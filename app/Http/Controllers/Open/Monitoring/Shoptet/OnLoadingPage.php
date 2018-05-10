<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;


use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\View\View;

class OnLoadingPage extends BaseController {

    public function getIndex() {
        $projects = $this->getShoptetProjects();
        $projectIds = array_keys($projects);
//        foreach ($projects as $project){
//            $projectIds[] = $project->id;
//        }

        // vd($projectIds);
        $connection = MDImportFlowConnections::getImportFlowConnection();
        $query = $connection->table("if_history")
            ->whereIn("project_id", $projectIds)
            ;
        // vdQuery($query);
        $data = $query->get();
        //vd($data);

        $projectsOnLoadingPage = array();
        foreach ($data as $item){
            $project = $projects[$item->project_id];
            $dth = new DateTimeHelper($project->created_at);
            $project->timeOnLoadingPage = gmdate("H:i:s", $dth->diffInSeconds());
            $projectsOnLoadingPage[] = $project;
        }



        View::share("projectsCount", count($projectsOnLoadingPage));
        View::share("projects", $projectsOnLoadingPage);
    }
}