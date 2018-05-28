<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;

use App\Http\Controllers\Open\Monitoring\Shoptet\Objects\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Arrays;
use Monkey\View\View;

class ProccessedOrderCountController extends BaseController {



    public function getData() {

        $projects = $this->getShoptetProjects();
        $projectIds = array_keys($projects);

        if($this->isDebug()) {
            vdQuery($query);
        }


        $ordersSum = 0;
        $proccessedProjects = 0;

        foreach ($projects as $project){
            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table("f_eshop_order_{$project->user_id}")
                ->selectRaw("count(id) as orderSum")
            ;
            try {
                $data = $query->first();
            }catch (\Throwable $e){
                // vd($e->getMessage());
                continue;
            }

            if(empty($data)){
                continue;
            }


            $ordersSum += $data->orderSum;
            $proccessedProjects++;
        }
//
//        vde($ordersSum);
//
//
//        View::share("projectsCount", count($projectsOnLoadingPage));
//        View::share("projects", $projectsOnLoadingPage);

        $response = new JsonResponse();
        $response->setData(array(
         //    "html" => $this->getView()->render(),
            "projectCount" => $proccessedProjects,
            "orderSum" => $ordersSum
        ));
        return $response;
    }

}