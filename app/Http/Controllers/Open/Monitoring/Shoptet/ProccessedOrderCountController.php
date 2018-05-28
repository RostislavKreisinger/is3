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

        $ordersSum = 0;
        $productsSum = 0;
        $productsVariantSum = 0;

        $proccessedProjects = 0;

        $this->out("Start processing data for ". count($projects) . " projects");

        foreach ($projects as $project){
            $table = "f_eshop_order_{$project->user_id}";
            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($table)
                ->selectRaw("count(id) as orderSum")
            ;

            $this->out("process orders for: {$table}");
            try {
                $orders = $query->first();
            }catch (\Throwable $e){
                $this->out($e->getMessage());
                continue;
            }
            if(empty($orders)){
                continue;
            }
            $ordersSum += $orders->orderSum;




            $tableProducts = "d_eshop_product_{$project->user_id}";
            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableProducts)
                ->selectRaw("count(id) as productsVariantSum, count(distinct product_parent_id) as productsSum")
            ;

            $this->out("process products for: {$tableProducts}");
            try {
                $products = $query->first();
            }catch (\Throwable $e){
                $this->out($e->getMessage());
                continue;
            }
            if(empty($products)){
                continue;
            }
            $productsSum += $products->productsSum;
            $productsVariantSum += $products->productsVariantSum;

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
            "orderSum" => $ordersSum,
            "productsSum" => $productsSum,
            "productsVariantSum" => $productsVariantSum,
        ));
        return $response;
    }

    private function out($text) {
        if(Input::exists("debug")){
            vdEcho($text);
        }
    }

}