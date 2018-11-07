<?php

namespace App\Console\Commands;


use App\Services\BrokenFlowService;
use Illuminate\Database\Eloquent\Collection;
use Monkey\Config\Application\ProjectEndpointBaseUrl;
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
    const NOTIFICATION_EMAIL = 'import-flow-notifications@monkeydata.com';

    /**
     * @param ICommandParameters $parameters
     * @return int|null|void
     * @throws SlackResponseException
     */
    protected function action(ICommandParameters $parameters) {
        $results = BrokenFlowService::get();

        if ($results->count() > 0) {
            Slack::getInstance()->sendIFNotification($this->formatSlackMessage($results));

            MDEmailConnection::getInfoEmailConnection()->sendSimpleMail(
                'Broken Daily Flows',
                $this->formatMailMessage($results),
                self::NOTIFICATION_EMAIL
            );
        }
    }

    /**
     * @param Collection $collection
     * @return string
     */
    private function formatSlackMessage(Collection $collection): string {
        $message = "{$collection->count()} broken flows have been found!\n";
        $overviewUrl = ProjectEndpointBaseUrl::getInstance()->getImportSupportUrl() . '/homepage/broken-flow';

        foreach ($collection as $result) {
            $message .= "\nIF Import ID: <{$overviewUrl}|{$result->if_import_id}>, ";
            $message .= "Unique: {$result->unique}";
        }

        if (!Environment::isProduction()) {
            $message = "*TEST ONLY*\n{$message}";
        }

        return $message;
    }

    /**
     * @param Collection $collection
     * @return string
     */
    private function formatMailMessage(Collection $collection): string {
        $message = "{$collection->count()} broken flows have been found!<br />";
        $overviewUrl = ProjectEndpointBaseUrl::getInstance()->getImportSupportUrl() . '/homepage/broken-flow';

        foreach ($collection as $result) {
            $message .= "<br />IF Import ID: <a href=\"{$overviewUrl}\">{$result->if_import_id}</a>, ";
            $message .= "Unique: {$result->unique}";
        }

        if (!Environment::isProduction()) {
            $message = "*TEST ONLY*<br />{$message}";
        }

        return $message;
    }

    /**
     * @return string
     */
    protected function getCommandDescription(): string {
        return 'Attempts to find records in daily and import pools that are linked by if_import_id ' .
        'but their project_ids are different and sends their list to Slack and email';
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