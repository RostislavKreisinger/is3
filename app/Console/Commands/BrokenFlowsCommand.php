<?php

namespace App\Console\Commands;


use App\Model\ImportPools\IFDailyPool;
use Illuminate\Database\Query\JoinClause;
use Monkey\Laravel\Console\Command\Command;
use Monkey\Laravel\Console\Command\CommandBuilder\Interfaces\IArgumentBuilderFactory;
use Monkey\Laravel\Console\Command\CommandBuilder\Interfaces\IOptionBuilderFactory;
use Monkey\Laravel\Console\Command\CommandParameters\Interfaces\ICommandParameters;
use Monkey\Slack\Exception\SlackResponseException;
use Monkey\Slack\Slack;

/**
 * Class BrokenFlowsCommand
 * @package App\Console\Commands
 */
class BrokenFlowsCommand extends Command {
    /**
     * @param ICommandParameters $parameters
     * @return int|null|void
     * @throws SlackResponseException
     */
    protected function action(ICommandParameters $parameters) {
        $results = IFDailyPool::query()->join('if_import', function (JoinClause $join) {
            $join->on('if_import.id', '=', 'if_daily.if_import_id')
                ->where('if_import.project_id', '!=', 'if_daily.project_id');
        })->get();

        if ($results->count() > 0) {
            $message = "<@U0SFWA9U7>, {$results->count()} broken flows have been found!\n";

            foreach ($results as $result) {
                $message .= "\nPID: {$result->project_id}, Resource: {$result->resource_id}, Unique: {$result->unique}";
            }

            Slack::getInstance()->sendIFNotification($message);
        }
    }

    /**
     * @return string
     */
    protected function getCommandDescription(): string {
        return 'Attempts to find records in daily and import pools that are linked by if_import_id but their project_id is different';
    }

    /**
     * @param IArgumentBuilderFactory $factory
     * @return array
     */
    protected function getArgumentsTemplates(IArgumentBuilderFactory $factory): array {
        return [];
    }

    /**
     * @param IOptionBuilderFactory $factory
     * @return array
     */
    protected function getOptionsTemplates(IOptionBuilderFactory $factory): array {
        return [];
    }
}