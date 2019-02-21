<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
abstract class ApiController extends Controller {
    /**
     * @var ApiClient $client
     */
    private $client;

    /**
     * @return bool|string
     */
    public function index() {
        return $this->getClient()->index($this->getEndpoint());
    }

    /**
     * @return ApiClient
     */
    public function getClient(): ApiClient {
        if ($this->client === null) {
            $this->client = new ApiClient;
        }

        return $this->client;
    }

    /**
     * @return string
     */
    abstract public function getEndpoint(): string;
}