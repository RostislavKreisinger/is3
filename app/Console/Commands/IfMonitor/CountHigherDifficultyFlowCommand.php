<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 25. 3. 2019
 * Time: 8:24
 */

namespace App\Console\Commands\IfMonitor;


use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use Illuminate\Console\Command;

class CountHigherDifficultyFlowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifmonitor:counthigherflow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check count higher difficulty flows.';

    /**
     * @throws \App\Helpers\Monitoring\ImportFlow\Exception\UnknownMonitoringAttributeException
     */
    public function handle()
    {
        $monitoring = new StepPoolMonitoring();
        $monitoring->checkCountHigherWorkflowDifficulty();

    }

}