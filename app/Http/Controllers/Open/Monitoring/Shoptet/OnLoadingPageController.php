<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;


use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Arrays;
use Monkey\View\View;
use PhpParser\Node\Expr\Array_;

class OnLoadingPageController extends BaseController {



    public function getData() {

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

        foreach ($projects as $project){
            $project->timeOnLoadingPage = null;
            $project->timeOnLoadingPageSec = null;
            $project->historyDownloadPercent = null;
            $project->historyDownloadSkipped = false;
        }

       //  $projectsOnLoadingPage = $projects;
        foreach ($data as $item){
            $project = &$projects[$item->project_id];
            $dth = new DateTimeHelper($project->created_at, 'UTC');
            $project->timeOnLoadingPageSec = $dth->diffInSeconds();
            $project->timeOnLoadingPage = gmdate("H:i:s", $dth->diffInSeconds());

            list($percent, $skipped) = $this->historyDownload($item);
            $project->historyDownloadPercent = $percent;
            $project->historyDownloadSkipped = $skipped;
            // $projectsOnLoadingPage[] = $project;
        }


        $projectsOnLoadingPage = Arrays::sortArrayOfObjects($projects, function($project){
            $result = ((int)$project->historyDownloadSkipped)*pow(10, 12);
            if($project->historyDownloadSkipped){
                $result +=pow(10, 8)*$project->historyDownloadPercent;
            }

            if($project->historyDownloadSkipped == 2){
                $result += $project->timeOnLoadingPageSec;
            }else {
                $result += pow(10, 8) - $project->timeOnLoadingPageSec;
            }
            return $result;
        });




        View::share("projectsCount", count($projectsOnLoadingPage));
        View::share("projects", $projectsOnLoadingPage);
    }


    private function historyDownload($history) {
        $createdAt = DateTimeHelper::getCloneSelf($history->created_at);
        $dateFrom = DateTimeHelper::getCloneSelf($history->date_from);
        $dateTo = DateTimeHelper::getCloneSelf($history->date_to);

        if($dateFrom > $dateTo){
            return [100, true];
        }

        $allDaysDiff = $createdAt->diffInDays($dateFrom);
        $remaining = $dateTo->diffInDays($dateFrom);

        // min 1 day for UX
        $alreadyDownloaded = max($allDaysDiff - $remaining, 1);

        $percents = round(($alreadyDownloaded / $allDaysDiff)*100);
        // min 1% for UX
        // $percents = max($percents, 1);

        $skipped = false;
        if($percents >= 100){
            $percents = 100;
            $skipped = 2;
        }

        if($alreadyDownloaded > 5){
            $skipped = 1;
        }


        return [$percents, $skipped];
    }
}