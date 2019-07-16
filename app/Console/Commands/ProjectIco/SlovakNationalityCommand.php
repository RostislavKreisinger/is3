<?php

namespace App\Console\Commands\ProjectIco;


use Monkey\Connections\MDDatabaseConnections;
use Monkey\Curl\Base\ACommonCurl;
use Monkey\Curl\Base\ACurl;
use Monkey\Curl\Base\Response\Response;
use Monkey\Curl\Curl;
use Monkey\Curl\Exceptions\HttpCurlEndpointNotFoundException;
use Monkey\Curl\Exceptions\HttpCurlException;
use Monkey\Curl\Interfaces\ICommonResponse;
use Monkey\Curl\Interfaces\IHeadersBag;
use Monkey\Laravel\Console\Command\Command;
use Monkey\Laravel\Console\Command\CommandBuilder\Interfaces\IArgumentBuilderFactory;
use Monkey\Laravel\Console\Command\CommandBuilder\Interfaces\IOptionBuilderFactory;
use Monkey\Laravel\Console\Command\CommandParameters\Interfaces\ICommandParameters;
use stdClass;
use Throwable;

class SlovakNationalityCommand extends Command {

    /**
     * @param ICommandParameters $parameters
     *
     * @return int|null
     * @throws Throwable
     */
    protected function action(ICommandParameters $parameters) {
        $output = $this->getOutput();

        while(1) {

            $projects = MDDatabaseConnections::getImportSupportConnection()
                ->table("project_ico")
                ->whereNull("nationality")
                ->whereNotNull("ico")
                ->limit(5)
                ->get();

            if (count($projects) === 0) {
                $output->writeln("Nothing to do");
                return 0;
            }

            foreach ($projects as $project) {
                $output->writeln("Project: {$project->eshop_id}");
                $isSlovak = $this->isSlovak($project);
                if ($isSlovak) {
                    $this->saveNationality($project, "sk");
                    continue;
                }

                $isCzech = $this->isCzech($project);
                if ($isCzech) {
                    $this->saveNationality($project, "cz");
                    continue;
                }

                $this->saveNationality($project, "none");
            }
        }
        $output->writeln("FINISH");
    }

    /**
     * @param $project
     * @param $nationality
     */
    private function saveNationality($project, $nationality) {
        MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")
            ->where("id", $project->id)
            ->update(["nationality" => $nationality]);
        $this->getOutput()->writeln("Seve nationality for project {$project->eshop_id} to '{$nationality}'");
    }

    /**
     * @param $project
     * @return bool
     * @throws HttpCurlException
     * @throws \Monkey\Curl\Exceptions\HttpCurlCommunicationException
     * @throws \Monkey\Curl\Exceptions\HttpCurlEndpointNotImplementedException
     * @throws \Monkey\Curl\Exceptions\HttpCurlResponseDecodingException
     * @throws \Monkey\Curl\Exceptions\HttpCurlUnauthorizedException
     */
    private function isSlovak($project) {
        try {
            $curl = new class() extends ACommonCurl {
                /**
                 * @param $rawBody
                 * @param IHeadersBag $headersBag
                 * @param array $curlInfo
                 * @return ICommonResponse
                 */
                protected function decodeAndParseResponse($rawBody, IHeadersBag $headersBag, array $curlInfo) {
                    $response = new Response();
                    $response->setHeadersBag($headersBag);
                    return $response;
                }
            };
            $url = "https://www.finstat.sk/{$project->ico}";
            $this->getOutput()->writeln("Testing is slovak. URL: {$url}");
            $response = $curl->call($url, [], $curl::METHOD_GET);
            $httpStatus = $response->getHeadersBag()->current()->getHttpStatus();
            if ((int)$httpStatus == 200) {
                return true;
            }
        }catch (HttpCurlEndpointNotFoundException $exception){
            return false;
        }

        return false;
    }


    private function isCzech($project) {
        try {
            $curl = new class() extends ACommonCurl {

                /**
                 * @param $rawBody
                 * @param IHeadersBag $headersBag
                 * @param array $curlInfo
                 * @return ICommonResponse
                 */
                protected function decodeAndParseResponse($rawBody, IHeadersBag $headersBag, array $curlInfo) {
                    $response = new Response();
                    $response->setBody($rawBody);
                    return $response;
                }
            };

            // $project->ico = "12345678";

            $url = "https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_vreo.cgi";
            $this->getOutput()->writeln("Testing is czech. URL: {$url}?ico={$project->ico}");
            $response = $curl->call($url, ["ico" => $project->ico], $curl::METHOD_GET);

            if(strpos($response->getBody(), "<are:Error_kod>") === false){
                return true;
            }
        }catch (HttpCurlEndpointNotFoundException $exception){
            return false;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getCommandDescription(): string {
        return "Check if the ICO is slovak nationality";
    }

    /**
     * @param IArgumentBuilderFactory $factory
     *
     * @return array
     */
    protected function getArgumentsTemplates(IArgumentBuilderFactory $factory): array {
        return [];
    }

    /**
     * @param IOptionBuilderFactory $factory
     *
     * @return array
     */
    protected function getOptionsTemplates(IOptionBuilderFactory $factory): array {
        return [];
    }
}