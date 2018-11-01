<?php

namespace App\Console\Commands;


use App\Http\Controllers\Homepage\BrokenFlowController;
use App\Model\ImportPools\IFDailyPool;
use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDEmailConnection;
use Monkey\Environment\Environment;
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
    const CONTACTS = [
        "<@U0SFWA9U7>" => ['name' => "Fingarfae", 'email' => 'lukas.kielar@monkeydata.com']//'rostislav.kreisinger@monkeydata.com']
    ];

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
            $message = "%s, {$results->count()} broken flows have been found!\n";

            foreach ($results as $result) {
                $overviewUrl = action(BrokenFlowController::getMethodAction());
                $message .= "\nIF Import ID: <{$overviewUrl}|{$result->if_import_id}>, ";
                $message .= "Unique: {$result->unique}";
            }

            if (!Environment::isProduction()) {
                $message = "*TEST ONLY*\n{$message}";
            }

            Slack::getInstance()->sendIFNotification(sprintf($message, implode(', ', array_keys(self::CONTACTS))));

            foreach (self::CONTACTS as $contact) {
                MDEmailConnection::getInfoEmailConnection()->sendSimpleMail(
                    'Broken Daily Flows',
                    str_replace("\n", "<br />", sprintf($message, $contact['name'])),
                    $contact['email']
                );
            }
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