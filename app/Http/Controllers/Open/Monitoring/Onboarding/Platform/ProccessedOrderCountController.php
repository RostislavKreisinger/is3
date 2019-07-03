<?php

namespace App\Http\Controllers\Open\Monitoring\Onboarding\Platform;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Constants\MonkeyData\Currency\CurrencyNames;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Strings;

class ProccessedOrderCountController extends BaseController {


    public function getEshop() {
        $projectId = Input::get('project_id');
        $userId = Input::get('user_id');

        if (!isset($projectId, $userId)) {
            throw new \Exception('Missing Query Params');
        }

        $project = $this->getShoptetProjectByProjectAndUserId($projectId, $userId);

        if (is_null($project)) {
            throw new \Exception('Missing Query Params');
        }

        $project->orders = 0;
        $project->revenue = array();

        $user = MDDatabaseConnections::getMasterAppConnection()
            ->table("user as u")
            ->join("client as c", 'c.user_id', '=', 'u.id')
            ->where("u.id", '=', $project->user_id)
            ->first(["u.email", "u.id", 'c.id as client_id' ]);


        $currentStartDateTimeHelper = new DateTimeHelper();
        $currentEndDateTimeHelper = $currentStartDateTimeHelper->getCloneThis();

        $currentStartDateTimeHelper->setDate($currentStartDateTimeHelper->getYear() , 1, 1);

        $previousStartDateTimeHelper = $currentStartDateTimeHelper->getCloneThis()->setDate($currentStartDateTimeHelper->getYear() - 1, $currentStartDateTimeHelper->getMonth(), $currentStartDateTimeHelper->getDay());
        $previousEndDateTimeHelper = $currentEndDateTimeHelper->getCloneThis()->setDate($currentEndDateTimeHelper->getYear() - 1, $currentEndDateTimeHelper->getMonth(), $currentEndDateTimeHelper->getDay());

        $projectResponse = new \stdClass();
        $projectResponse->timezone = $project->timezone;
        $projectResponse->currency = $project->currency;
        $projectResponse->country = $project->country;
        $projectResponse->revenue = $project->revenue;
        $projectResponse->url = $project->url;
        $projectResponse->name = $project->name;
        $projectResponse->revenue = [];
        $projectResponse->email = $user->email;
        $projectResponse->projectAlphaId = Strings::id2alpha($projectId);
        $projectResponse->userAlphaId = Strings::id2alpha($userId);
        $projectResponse->revenue['previous'] = $this->getRevenueInRange($user->client_id, $previousStartDateTimeHelper->getMySqlId(), $previousEndDateTimeHelper->getMySqlId());
        $projectResponse->revenue['current'] = $this->getRevenueInRange($user->client_id, $currentStartDateTimeHelper->getMySqlId(), $currentEndDateTimeHelper->getMySqlId());

        $response = new JsonResponse();
        $response->setData($projectResponse);
        return $response;
    }

    private function getRevenueInRange($clientId, $dateFrom, $dateTo) {
        $table = "f_eshop_order_{$clientId}";

        $query = MDDataStorageConnections::getImportDw2Connection()
            ->table($table)
            ->whereBetween('date_id', [$dateFrom, $dateTo])
            ->selectRaw("count(id) as orderSum, SUM(price_without_vat) as revenue, currency_id")
            ->groupBy("currency_id");

        $orders = $query->get();

        $revenue = [];

        foreach ($orders as $order){
            $code = CurrencyNames::getById($order->currency_id);
            $revenue[$code] = $order->revenue;
        }

        return $revenue;

    }


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

        $outputData["dateFrom"] = $this->getDateFrom()->mysqlFormatDate();
        $outputData["dateTo"] = $this->getDateTo()->mysqlFormatDate();

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
