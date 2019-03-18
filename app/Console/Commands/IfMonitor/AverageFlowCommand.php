<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 15. 3. 2019
 * Time: 14:47
 */

namespace App\Console\Commands\IfMonitor;


use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use Illuminate\Console\Command;

class AverageFlowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifmonitor:average';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all attributes of average flow';

    /**
     * @throws \App\Helpers\Monitoring\ImportFlow\Exception\UnknownMonitoringAttributeException
     */
    public function handle()
    {
        $monitoring = new StepPoolMonitoring();
        $monitoring->checkAverageFlowAttributes();

    }
}