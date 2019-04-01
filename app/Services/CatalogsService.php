<?php


namespace App\Services;


use App\Helpers\API\ISAPIClient;
use App\Helpers\API\ISAPIRequest;
use App\Model\EshopType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class CatalogsService
 * @package App\Services
 */
class CatalogsService {
    /**
     * @var ISAPIClient $client
     */
    private $client;

    /**
     * @var Collection $eshopTypes
     */
    private $eshopTypes;

    /**
     * ResourceSettingsService constructor.
     * @param ISAPIClient $client
     */
    public function __construct(ISAPIClient $client) {
        $this->setClient($client);
    }

    /**
     * @return Collection
     */
    public function getEshopTypes(): Collection {
        if (empty($this->eshopTypes)) {
            $this->eshopTypes = $this->get('catalogs/eshop-types', EshopType::class);
        }

        return $this->eshopTypes;
    }

    /**
     * @param int $id
     * @return EshopType
     */
    public function findEshopType(int $id): EshopType {
        return $this->getEshopTypes()->first(function (EshopType $eshopType) use ($id) {
            return $eshopType->id === $id;
        });
    }

    /**
     * @param string $endpoint
     * @param string $className
     * @return Collection
     */
    public function get(string $endpoint, string $className): Collection {
        $result = $this->getClient()->call(new ISAPIRequest("{$endpoint}", [], [], [], []));

        if (empty($result['data'])) {
            return new Collection();
        }

        $models = [];

        foreach ($result['data'] as $item) {
            /** @var Model $model */
            $model = new $className();
            $model->id = $item['id'];
            $model->fill($item['attributes']);
            $models[] = $model;
        }

        return collect($models);
    }

    /**
     * @return ISAPIClient
     */
    protected function getClient(): ISAPIClient {
        return $this->client;
    }

    /**
     * @param ISAPIClient $client
     * @return CatalogsService
     */
    private function setClient(ISAPIClient $client): self {
        $this->client = $client;
        return $this;
    }
}