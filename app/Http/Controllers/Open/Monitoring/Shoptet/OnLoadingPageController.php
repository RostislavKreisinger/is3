<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;

use App\Http\Controllers\Open\Monitoring\Shoptet\Objects\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Arrays;
use Monkey\View\View;

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

        if($this->isDebug()) {
            vdQuery($query);
        }

        $data = $query->get();
        //vd($data);

        foreach ($projects as $project){
            $project->timeOnLoadingPageSec = null;
            $project->timeOnLoadingPage = null;

            $dth = new DateTimeHelper($project->created_at, 'UTC');
            $project->timeOnLoadingPageSec = $dth->diffInSeconds();
            $days = (int)($project->timeOnLoadingPageSec / (3600*24));
            $project->timeOnLoadingPage = ($days > 0?"{$days}d ": '') . gmdate("H:i:s", $dth->diffInSeconds());


            $project->historyDownloadPercent = null;
            $project->historyDownloadSkipped = false;

            $project->historyDownloadPercentOld = null;
            $project->historyDownloadSkippedOld = false;
        }

       //  $projectsOnLoadingPage = $projects;
        foreach ($data as $item){
            $project = $projects[$item->project_id];

            list($percent, $skipped) = $this->historyDownloadOld($item);
            $project->historyDownloadPercent = $percent;
            $project->historyDownloadSkipped = $skipped;

            list($percentOld, $skippedOld) = $this->historyDownload($item);
            $project->historyDownloadPercentOld = $percentOld;
            $project->historyDownloadSkippedOld = $skippedOld;

            if($project->historyDownloadSkipped == 2){
                $dth = new DateTimeHelper($item->created_at);
                $project->timeOnLoadingPageSec = $dth->diffInSeconds($item->updated_at);

                $days = (int)($project->timeOnLoadingPageSec / (3600*24));
                $project->timeOnLoadingPage = ($days > 0?"{$days}d ": '') . gmdate("H:i:s", $project->timeOnLoadingPageSec);
            }

            // $projectsOnLoadingPage[] = $project;
        }


        /**
         * @var $projectsOnLoadingPage Project[]
         */
        $projectsOnLoadingPage = Arrays::sortArrayOfObjects($projects, function($obj){
            $result = ((int)$obj->historyDownloadSkipped)*pow(10, 12);
            if($obj->historyDownloadSkipped){
                $result +=pow(10, 8)*$obj->historyDownloadPercent;
            }

            if($obj->historyDownloadSkipped == 2){
                $result += $obj->timeOnLoadingPageSec;
            }else {
                $result += pow(10, 8) - $obj->timeOnLoadingPageSec;
            }
            return $result;
        });


        // vd($projectsOnLoadingPage);

        // IMPORTANT
        foreach ($projectsOnLoadingPage as $project){
            if($project->historyDownloadSkipped == 0 && $project->timeOnLoadingPageSec > 60*10){
                $project->important = 1;
            }
        }
        // vde($projectsOnLoadingPage);



        View::share("projectsCount", count($projectsOnLoadingPage));
        View::share("projects", $projectsOnLoadingPage);

        $response = new JsonResponse();
        $response->setData(array(
            "html" => $this->getView()->render(),
            "projectCount" => count($projectsOnLoadingPage)
        ));
        return $response;
    }


    private function historyDownloadOld($history) {
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

    private function historyDownload($history) {
        $createdAt = DateTimeHelper::getCloneSelf($history->created_at);
        $dateFrom = DateTimeHelper::getCloneSelf($history->date_from);
        $dateTo = DateTimeHelper::getCloneSelf($history->output_date_to);

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