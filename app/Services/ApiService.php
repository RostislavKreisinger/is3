<?php

namespace App\Services;


use App\Helpers\API\ISAPIClient;
use App\Helpers\API\ISAPIRequest;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiService
 * @package App\Services
 */
abstract class ApiService {
    /**
     * @var ISAPIClient $client
     */
    private $client;

    /**
     * ResourceSettingsService constructor.
     * @param ISAPIClient $client
     */
    public function __construct(ISAPIClient $client) {
        $this->setClient($client);
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id) {
        $result = $this->getClient()->call(new ISAPIRequest("{$this->getEndpoint()}/{$id}", [], [], [], []));

        if (empty($result['data'])) {
            return null;
        }

        $model = $this->getModel();
        $model->id = $result['data']['id'];
        $model->fill($result['data']['attributes']);
        return $model;
    }

    /**
     * @return string
     */
    abstract protected function getEndpoint(): string;

    /**
     * @return Model
     */
    abstract protected function getModel(): Model;

    /**
     * @return ISAPIClient
     */
    protected function getClient(): ISAPIClient {
        return $this->client;
    }

    /**
     * @param ISAPIClient $client
     * @return ApiService
     */
    private function setClient(ISAPIClient $client): self {
        $this->client = $client;
        return $this;
    }
}