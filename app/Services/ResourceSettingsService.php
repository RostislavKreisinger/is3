<?php

namespace App\Services;


use App\Helpers\API\ISAPIRequest;
use App\Model\ResourceSetting;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceSettingsService
 * @package App\Services
 */
class ResourceSettingsService extends ApiService {
    /**
     * @param int $resourceSettingId
     * @return object|null
     */
    public function getDetail(int $resourceSettingId) {
        $result = $this->getClient()->call(new ISAPIRequest("/base/resource-settings/{$resourceSettingId}/detail", [], [], [], []));

        if (empty($result['data'])) {
            return null;
        }

        $model = (object) $result['data']['attributes'];
        $model->id = $result['data']['id'];
        return $model;
    }

    /**
     * @return string
     */
    protected function getEndpoint(): string {
        return '/base/resource-settings';
    }

    /**
     * @return ResourceSetting
     */
    protected function getModel(): Model {
        return new ResourceSetting;
    }
}