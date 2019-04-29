<?php

namespace App\Console\Commands\Stats;

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


        $provider = new PlatformDataProvider("shoptet");

        $dateFrom = new DateTimeHelper("2018-04-01");
        $dateTo = new DateTimeHelper();
        $data = $provider->getStats($dateFrom, $dateTo);

        echo "UID;email;PID;createdAt;productVariantCount;RevenueCZK-2017;RevenueCZK-2018;RevenueCZK-2019;OrdersCZK-2017;OrdersCZK-2018;OrdersCZK-2019\n";
        foreach ($data["projects"] as $project) {
            echo $this->printRow([
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
                    $project->ordersYear[2019]
                ]) . "\n";
        }
    }

    private function printRow($data) {
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