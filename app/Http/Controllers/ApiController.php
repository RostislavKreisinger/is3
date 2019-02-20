<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller;

/**
 * Class ApiController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
abstract class ApiController extends Controller {
    private $apiUrl = "https://develop.monkeydata.cloud/import/import-support-v4-api.monkeydata.cloud/feature/is4api-2/v1";
    protected $endpoint = "";

    /**
     * @return bool|string
     */
    public function index() {
        $url = $this->getUrl();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/vnd.api+json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($curl);
        curl_close($curl);
        return $results;
    }

    /**
     * @return string
     */
    protected function getUrl(): string {
        return "{$this->apiUrl}/{$this->endpoint}?{$this->getParamsString()}";
    }

    /**
     * @return string
     */
    private function getParamsString(): string {
        return "{$this->getPageString()}{$this->getFilterString()}{$this->getSortString()}";
    }

    /**
     * @return string
     */
    private function getPageString(): string {
        $pageSize = request("take", 10);
        $page = request("skip", 0) / $pageSize + 1;
        return "page[size]={$pageSize}&page[number]={$page}";
    }

    /**
     * @return string
     */
    private function getFilterString(): string {
        $filters = request("filter", []);
        $filtersString = "";

        foreach ($filters as $name => $value) {
            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $value = !is_null($boolValue) ? intval($boolValue) : $value;
            $filtersString .= "&filter[{$name}]={$value}";
        }

        return $filtersString;
    }

    /**
     * @return string
     */
    private function getSortString(): string {
        $sortParam = request("sort");

        if (empty($sortParam)) {
            return "";
        }

        return "&sort={$sortParam}";
    }
}