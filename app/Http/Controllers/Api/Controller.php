<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAuthController;

class Controller extends BaseAuthController  {


    /**
     * @var ApiResponse
     */
    private $apiResponse = null;

    public function __construct(ApiResponse $apiResponse) {
        $this->apiResponse = $apiResponse;
    }

    /**
     * @return ApiResponse
     */
    protected function getApiResponse() {
        return $this->apiResponse;
    }

    public function callAction($method, $parameters) {
        $result = parent::callAction($method, $parameters);
        if ($result === null) {
            $result = $this->getApiResponse()->createJsonReponse();
        }
        return $result;
    }


}
