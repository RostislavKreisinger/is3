<?php

namespace App\Http\Controllers;


use App\Helpers\API\ISAPIClient;
use App\Helpers\API\ISAPIRequest;
use Illuminate\Routing\Controller;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
abstract class ApiController extends Controller {
    /**
     * @var ISAPIClient $client
     */
    private $client;

    /**
     * @return bool|string
     */
    public function index() {
        $pageSize = request('take', 15);
        $pageNumber = request('skip', 0) / $pageSize + 1;
        return $this->getClient()->call(new ISAPIRequest(
            $this->getEndpoint(),
            [],
            request('filter', []),
            [],
            ['number' => $pageNumber, 'size' => $pageSize],
            request('sort', [])
        ));
    }

    /**
     * @return ISAPIClient
     */
    public function getClient(): ISAPIClient {
        if ($this->client === null) {
            $this->client = new ISAPIClient();
        }

        return $this->client;
    }

    /**
     * @return string
     */
    abstract public function getEndpoint(): string;
}