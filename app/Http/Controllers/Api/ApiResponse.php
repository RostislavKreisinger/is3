<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

/**
 * Class ApiResponse
 * @package App\Http\Controllers\Api
 */
class ApiResponse {

    /** @var int */
    protected $status = 200;

    /** @var array */
    protected $headers = [];

    /** @var */
    protected $options = JSON_NUMERIC_CHECK;

    /** @var array */
    protected $data = [];

    /** @var array */
    protected $errors = [];

    /** @var JsonResponse */
    protected $jsonResponse;

    /**
     * ApiResponse constructor.
     * @param JsonResponse $jsonResponse
     */
    public function __construct(JsonResponse $jsonResponse) {
        $this->jsonResponse = $jsonResponse;
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function success($data) {
        return $this->setStatus(200)->setData($data)->createJsonReponse();
    }

    /**
     * @param $message
     * @param array $params
     * @return JsonResponse
     */
    public function unauthorized($message, array $params = []) {
        $params['message'] = $message;
        return $this->setStatus(401)->setData($params)->createJsonReponse();
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    /**
     * @param $message
     * @param array $params
     * @return JsonResponse
     */
    public function badRequest($message, array $params = []) {
        $params['message'] = $message;
        return $this->setStatus(400)->setData($params)->createJsonReponse();
    }

    /**
     * @param $message
     * @param array $params
     * @return JsonResponse
     */
    public function notFound($message, array $params = []) {
        $params['message'] = $message;
        return $this->setStatus(404)->setData($params)->createJsonReponse();
    }

    /**
     * @param $message
     * @param array $params
     * @return JsonResponse
     */
    public function forbidden($message, array $params = []) {
        $params['message'] = $message;
        return $this->setStatus(403)->setData($params)->createJsonReponse();
    }

    /**
     * @param $url
     * @param array $params
     * @return JsonResponse
     */
    public function redirect($url, array $params = []) {
        $params['redirect'] = $url;
        return $this->setStatus(303)->setData($params)->createJsonReponse();
    }

    /**
     * @return JsonResponse
     */
    public function createJsonReponse() {

        $this->jsonResponse
            ->setData($this->createReponseData())
            ->setStatusCode($this->getStatus())
            ->setEncodingOptions($this->getOptions());

        foreach ($this->getHeaders() as $key => $value) {
            $this->jsonResponse->header($key, $value);
        }

        return $this->jsonResponse;
    }

    /**
     * @return array
     */
    private function createReponseData() {

        return $this->getData();
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     * @return ApiResponse
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return ApiResponse
     */
    public function setErrors($errors) {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $error
     * @return $this
     */
    public function addError($error) {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     * @return ApiResponse
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return ApiResponse
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return ApiResponse
     */
    public function setOptions($options) {
        $this->options = $options;
        return $this;
    }


}