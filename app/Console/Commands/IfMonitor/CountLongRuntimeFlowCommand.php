<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 15. 3. 2019
 * Time: 14:48
 */

namespace App\Console\Commands\IfMonitor;


use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use Illuminate\Console\Command;

class CountLongRuntimeFlowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifmonitor:longruntime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check count flow with long runtime (longer than average runtime)';

    /**
     * @throws \App\Helpers\Monitoring\ImportFlow\Exception\UnknownMonitoringAttributeException
     */
    public function handle()
    {
        $monitoring = new StepPoolMonitoring();
        $monitoring->checkCountLongRunTimeFlows();

    }
}