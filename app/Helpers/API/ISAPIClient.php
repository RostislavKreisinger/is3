<?php

namespace App\Helpers\API;


use Monkey\Config\Application\ProjectEndpointBaseUrl;

/**
 * Class ISAPIClient
 * @package App\Helpers\API
 */
class ISAPIClient {
    /**
     * @var string $apiUrl
     */
    private $apiUrl;

    /**
     * @param ISAPIRequest $request
     * @param int $method
     * @return bool|string
     */
    public function call(ISAPIRequest $request, int $method = CURLOPT_HTTPGET) {
        $url = $this->getUrl($request);
        $url = trim($url, '?');
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.api+json',
            //TODO: Change access token acquisition
            'Authorization: Bearer UOM0d36nrSB9odnbpfWxVpFGM28K0Q5DLQCt8JRh'
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, $method, true);
        $results = curl_exec($curl);
        curl_close($curl);
        return json_decode($results, true);
    }

    /**
     * @param ISAPIRequest $request
     * @return string
     */
    private function getUrl(ISAPIRequest $request): string {
        return "{$this->getApiUrl()}/v1/{$request->getEndpoint()}?{$this->getParamsString($request)}";
    }

    /**
     * @param ISAPIRequest $request
     * @return string
     */
    private function getParamsString(ISAPIRequest $request): string {
        return "{$this->getPageString($request->getPage())}{$this->getFilterString($request->getFilters())}{$this->getSortString($request->getSorts())}";
    }

    /**
     * @param array $page
     * @return string
     */
    private function getPageString(array $page): string {
        $pageParts = [];

        foreach ($page as $key => $value) {
            $pageParts[] = "page[{$key}]={$value}";
        }

        return implode('&', $pageParts);
    }

    /**
     * @param array $filters
     * @return string
     */
    private function getFilterString(array $filters): string {
        $filtersString = "";

        foreach ($filters as $name => $value) {
            $value = is_array($value) ? implode(',', $value) : $value;
            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $value = !is_null($boolValue) ? intval($boolValue) : $value;
            $filtersString .= "&filter[{$name}]={$value}";
        }

        return $filtersString;
    }

    /**
     * @param array $sorts
     * @return string
     */
    private function getSortString(array $sorts): string {
        if (empty($sorts)) {
            return "";
        }

        $sortParam = implode(',', $sorts);
        return "&sort={$sortParam}";
    }

    /**
     * @return string
     */
    private function getApiUrl(): string {
        if ($this->apiUrl === null) {
            $this->apiUrl = ProjectEndpointBaseUrl::getInstance()->getImportSupportApiUrl();
        }

        return $this->apiUrl;
    }
}