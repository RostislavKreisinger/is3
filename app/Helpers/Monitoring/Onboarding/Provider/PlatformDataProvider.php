<?php


namespace App\Helpers\Monitoring\Onboarding\Provider;


use App\Helpers\IO\IErrorReporter;
use App\Helpers\Monitoring\Onboarding\Platform;
use App\Http\Controllers\Open\Monitoring\Onboarding\Platform\Objects\Project;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDDataStorageConnections;
use Monkey\Constants\MonkeyData\Currency\CurrencyNames;
use Monkey\CurrencyRate\CurrencyRate;
use Monkey\DateTime\DateTimeHelper;
use Throwable;

class PlatformDataProvider {


    /**
     * @var string
     */
    private $platformCode;

    /**
     * @var IErrorReporter
     */
    private $errorReporter;

    /**
     * PlatformDataProvider constructor.
     * @param string $platformCode
     * @param IErrorReporter $errorReporter
     */
    public function __construct(string $platformCode, IErrorReporter $errorReporter) {
        $this->platformCode = $platformCode;
        $this->errorReporter = $errorReporter;
    }

    /**
     * @param DateTimeHelper $dateFromDth
     * @param DateTimeHelper $dateToDth
     * @param array $columns
     * @return Project[]
     */
    protected function getProjects(DateTimeHelper $dateFromDth, DateTimeHelper $dateToDth, $columns = array()) {

        $dateFrom = $dateFromDth->mysqlFormatDate();
        $dateTo = $dateToDth->mysqlFormatDate();
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


    public function getStats(DateTimeHelper $dateFromDth, DateTimeHelper $dateToDth) {

        $outputData = [];
        $projects = $this->getProjects($dateFromDth, $dateToDth, ['p.weburl']);

        $currencyRate = new CurrencyRate();
        $todayDateId = (new DateTimeHelper())->getMySqlId();

        foreach ($projects as $project){
            $project->orders = null;
            $project->products = null;
            $project->productsVariant = null;
            $project->revenue = array();
            $project->user_email = null;
            $project->countriesInOrders = null;
            $project->customers = 0;


            $project->orders = 0;
            $project->ordersYear = [
                2017 => 0,
                2018 => 0,
                2019 => 0
            ];
            $project->revenueCKZ = [
                2017 => 0,
                2018 => 0,
                2019 => 0
            ];

            $user = MDDatabaseConnections::getMasterAppConnection()
                ->table("user as u")
                ->join("client as c", 'c.user_id', '=', 'u.id')
                ->where("u.id", '=', $project->user_id)
                ->first(["u.email", "u.id", 'c.id as client_id' ]);
            if($user !== null) {
                $project->user_email = $user->email;
            }

            $tableEshopOrder = "f_eshop_order_{$user->client_id}";
            $tableEshopOrderDetail = "f_eshop_order_detail_{$user->client_id}";
            $tableEshopProduct = "d_eshop_product_{$user->client_id}";
            $tableEshopCustomer = "d_eshop_customer_{$user->client_id}";


            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableEshopOrder)
                ->where("project_id", "=", $project->id)
                ->where("row_status", "<", 100)
                // ->whereBetween("date_id", ['20180101', '20181231'])
                ->selectRaw("date_id, count(id) as orderSum, SUM(price_without_vat) as revenue, currency_id")
                ->groupBy("date_id", "currency_id")
            ;

            try {
                $orders = $query->get();
            }catch (Throwable $e){
                $this->report($e);
                continue;
            }
            if(empty($orders)){
                continue;
            }



            foreach ($orders as $order){
                $year = substr($order->date_id, 0, 4);

                $project->orders +=  $order->orderSum;
                if(array_key_exists($year, $project->ordersYear)){
                    $project->ordersYear[$year] += $order->orderSum;
                }


                $code = CurrencyNames::getById($order->currency_id);
                $project->revenue[$code] = $order->revenue;


                try {
                    $rate = $currencyRate->getRate($order->currency_id, CurrencyNames::CZK, $todayDateId);
                    if(array_key_exists($year, $project->revenueCKZ)){
                        $project->revenueCKZ[$year] += $order->revenue * $rate;
                    }
                }catch (Exception $e){
                    $this->report($e);
                    continue;
                }

            }


            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableEshopOrder)
                ->where("project_id", "=", $project->id)
                ->where("row_status", "<", 100)
                ->selectRaw("COUNT(DISTINCT customer_id) as customers");
            try {
                $customers = $query->get();
                $project->customers = $customers["customers"];
            }catch (Throwable $e){
                $this->report($e);
                // continue;
            }



            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableEshopOrderDetail)
                ->join($tableEshopProduct, "$tableEshopOrderDetail.product_id", "=", "$tableEshopProduct.id")
                ->where("project_id", "=", $project->id)
                ->where("row_status", "<", 100)
                ->selectRaw("product_id, SUM(products_count) as product_sum, product_name")
                ->groupBy("product_id")
                ->orderBy("product_sum")
            ;
            try {
                $topProduct = $query->first();
                $project->topProductName = $topProduct->product_name;
                $project->topProductSales = $topProduct->product_sum;
            }catch (Throwable $e){
                $this->report($e);
                // continue;
            }





            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableEshopProduct)
                ->selectRaw("count(id) as productsVariantSum, count(distinct product_parent_id) as productsSum")
            ;

            try {
                $products = $query->first();
            }catch (Throwable $e){
                $this->report($e);
                continue;
            }
            if(empty($products)){
                continue;
            }
            $project->products = $products->productsSum;
            $project->productsVariant = $products->productsVariantSum;


            $query = MDDataStorageConnections::getImportDw2Connection()
                ->table($tableEshopCustomer)
                ->whereNotNull('country_id');

            try {
                $countriesCount = $query->get();
            }catch (Throwable $e){
                $this->report($e);
                continue;
            }

            $project->countriesInOrders = count($countriesCount);
        }
        $outputData["projects"] = $projects;
        return $outputData;
    }


    /**
     * @return array
     * @throws \Exception
     */
    private function getEshopTypes(): array {
        $platformCode = $this->getPlatformCode();
        return Platform::getEshopType($platformCode);
    }

    /**
     * @return string
     */
    public function getPlatformCode(): string {
        return $this->platformCode;
    }

    /**
     * @param Throwable $exception
     */
    private function report(Throwable $exception) {
        $this->errorReporter->report($exception);
    }

}
