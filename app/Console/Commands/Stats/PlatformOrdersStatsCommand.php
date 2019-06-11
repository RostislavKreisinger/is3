<?php

namespace App\Console\Commands\Stats;

use App\Helpers\IO\ErrorReporter;
use App\Helpers\Monitoring\Onboarding\Provider\PlatformDataProvider;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Monkey\DateTime\DateTimeHelper;

class PlatformOrdersStatsCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:platform-orders-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stats about platform';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {


        $errorReporter = new ErrorReporter(false);
        $provider = new PlatformDataProvider("shoptet", $errorReporter);

        $dateFrom = new DateTimeHelper("2018-04-01");
        $dateTo = new DateTimeHelper();
        $data = $provider->getStats($dateFrom, $dateTo);


        $headers = [
            "UID",
            "email",
            "PID",
            "createdAt",
            "productVariantCount",
            "RevenueCZK-2017",
            "RevenueCZK-2018",
            "RevenueCZK-2019",
            "OrdersCZK-2017",
            "OrdersCZK-2018",
            "OrdersCZK-2019",

            "weburl",
            "CustomersCountAllTheTime",
            "TopProductName",
            "TopProductSales"
        ];

        echo $this->getCsvRow($headers);
        // echo "UID;email;PID;createdAt;productVariantCount;RevenueCZK-2017;RevenueCZK-2018;RevenueCZK-2019;OrdersCZK-2017;OrdersCZK-2018;OrdersCZK-2019\n";
        foreach ($data["projects"] as $project) {
            echo $this->getCsvRow([
                    $project->user_id,
                    $project->user_email,
                    $project->id,
                    $project->created_at,
                    $project->productsVariant,
                    $project->revenueCKZ[2017],
                    $project->revenueCKZ[2018], 
                    $project->revenueCKZ[2019],
                    $project->ordersYear[2017],
                    $project->ordersYear[2018],
                    $project->ordersYear[2019],

                    $project->weburl,
                    $project->customers,
                    $project->topProductName,
                    $project->topProductSales
                ]);
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function getCsvRow($data) {
        return $this->getRow($data) . "\n";
    }

    /**
     * @param $data
     * @return string
     */
    private function getRow($data) {
        $string = "";
        foreach ($data as $item){
            if(is_string($item)){
                $item = "\"{$item}\"";
            }
            $string .= $item . ";";
        }
        return $string;
    }
}
