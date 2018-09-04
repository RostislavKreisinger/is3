<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Constants\MonkeyData\Currency\CurrencyNames;
use Monkey\DateTime\DateTimeHelper;

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


    public function getStats() {

        $outputData = [];
        $formSubmited = (bool)Input::get("form_submited", false);


        $outputData["dateFrom"] = $this->getDateFrom();
        $outputData["dateTo"] = $this->getDateTo();

        if(!$formSubmited){
            $dth = new DateTimeHelper($outputData["dateTo"]);
            $outputData["dateFrom"] = $dth->changeDays(-7)->mysqlFormatDate();
          //   vde($outputData);
            $view = \View::make('tmp.shoptet-project-stats', $outputData);
            return $view;
        }


        $projects = $this->getShoptetProjects(['p.weburl']);

        $this->out("Start processing data for ". count($projects) . " projects");

        foreach ($projects as $project){
            $project->orders = null;
            $project->products = null;
            $project->productsVariant = null;
            $project->revenue = array();
            $project->user_email = null;
            $project->countriesInOrders = null;

            $user = MDDatabaseConnections::getMasterAppConnection()
                ->table("user as u")
                ->join("client as c", 'c.user_id', '=', 'u.id')
                ->where("u.id", '=', $project->user_id)
                ->first(["u.email", "u.id", 'c.id as client_id' ]);
            if($user !== null) {
                $project->user_email = $user->email;
            }

            $table = "f_eshop_order_{$user->client_id}";
            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($table)
                ->selectRaw("count(id) as orderSum, SUM(price_without_vat) as revenue, currency_id")
                ->groupBy("currency_id")
            ;

            $this->out("process orders for: {$table}");
            try {
                $orders = $query->get();
            }catch (\Throwable $e){
                $this->out($e->getMessage());
                continue;
            }
            if(empty($orders)){
                continue;
            }

            $project->orders = 0;

            foreach ($orders as $order){
                $project->orders +=  $order->orderSum;
                $code = CurrencyNames::getById($order->currency_id);
                $project->revenue[$code] = $order->revenue;
            }





            $tableProducts = "d_eshop_product_{$user->client_id}";
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
            $project->products = $products->productsSum;
            $project->productsVariant = $products->productsVariantSum;

            $tableCustomersCountries = "d_eshop_customer_{$user->client_id}";
            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableCustomersCountries)
                ->whereNotNull('country_id');

            try {
                $countriesCount = $query->get();
            }catch (\Throwable $e){
                $this->out($e->getMessage());
                continue;
            }

            $project->countriesInOrders = count($countriesCount);
        }
        $outputData["projects"] = $projects;

        // vde($projects);
        $view = \View::make('tmp.shoptet-project-stats', $outputData);
        return $view;
//        $response = new JsonResponse();
//        $response->setData(array(
//            //    "html" => $this->getView()->render(),
//            "projectCount" => $proccessedProjects,
//            "orderSum" => $ordersSum,
//            "productsSum" => $productsSum,
//            "productsVariantSum" => $productsVariantSum,
//        ));
//        return $response;
    }
    
    
    private function out($text) {
        if(Input::exists("debug")){
            vdEcho($text);
        }
    }

}