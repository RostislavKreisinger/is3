<?php

namespace App\Http\Controllers\Debug;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Monkey\ImportEshopDataObjects\Base\Differences;
use Monkey\ImportEshopDataObjects\Base\Request as APIRequest;
use Monkey\ImportEshopDataObjects\Base\Response;
use Monkey\ImportEshopDataObjects\Entity\Simple\CurrentSetting;
use Monkey\ImportEshopSdk\ImportEshopSdk;
use Monkey\ImportEshopSdk\Request\BaseRequestManager;
use Monkey\ImportSupport\Project;
use Monkey\ImportSupport\ResourceSettingDifference;
use NilPortugues\Serializer\JsonSerializer;

/**
 * Class PrestaDebugController
 * @package App\Http\Controllers\Debug
 * @author Lukáš Kielar
 */
class PrestaDebugController extends Controller {
    /**
     * List of endpoints that are implemented in our Prestashop API
     */
    const ENDPOINTS = [
        'orders' => 'Orders',
        'orderitems' => 'Order Items',
        'customers' => 'Customers',
        'products' => 'Products',
        'variants' => 'Variants',
        'shipments' => 'Shipments',
        'payments' => 'Payments',
        'orderstatuses' => 'Order Statuses',
        'categories' => 'Categories',
        'labels' => 'Labels',
        'attributevalues' => 'Attribute Values',
        'coupons' => 'Coupons',
        'brands' => 'Brands',
        'distributors' => 'Distributors',
        'eshopinformation' => 'Eshop Information'
    ];

    /**
     * List of controls that should be visible for each endpoint
     */
    const CONTROL_MAP = [
        'orders' => ['dates', 'id'],
        'orderitems' => ['foreign', 'id'],
        'customers' => ['dates', 'ids', 'id'],
        'products' => ['dates', 'ids', 'id'],
        'variants' => ['dates', 'ids', 'foreign'],
        'shipments' => ['id'],
        'payments' => ['id'],
        'orderstatuses' => ['id'],
        'categories' => ['dates', 'ids', 'foreign', 'id'],
        'labels' => ['dates', 'ids', 'id'],
        'attributevalues' => ['foreign', 'id'],
        'coupons' => ['dates', 'id'],
        'brands' => ['dates', 'id'],
        'distributors' => ['dates', 'id'],
        'eshopinformation' => []
    ];

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return array
     */
    public function testCall(Project $project_id, int $resource_id, Request $request) {
        /**
         * @var BaseRequestManager $requestManager
         * @var APIRequest $apiRequest
         * @var Response $item
         */
        $resource = $project_id->getResource($resource_id)->getConnectionDetail();
        $endpoint = $request->input('endpoint');
        $active = $request->input('active');
        $inactive = $request->input('inactive');
        $sdk = new ImportEshopSdk($resource['url'], $resource['password'], new JsonSerializer());
        $info = $this->getEshopInformation($sdk);
        $managerFunction = 'get' . str_replace(' ', '', self::ENDPOINTS[$endpoint]) . 'Manager';
        $requestManager = $sdk->$managerFunction();
        $apiRequest = $requestManager->getRequest();
        $apiRequest->setDebug(true);
        $apiRequest->addCurrentDefaultSettings((new CurrentSetting())->setKey('id_shop')->setValue($info['id_shop']));
        $apiRequest->addCurrentDefaultSettings((new CurrentSetting())->setKey('id_shop_group')->setValue($info['id_shop_group']));

        if (!empty($request->input('from'))) {
            $apiRequest->setCreatedFrom($request->input('from'));
            $apiRequest->setUpdatedFrom($request->input('from'));
        }

        if (!empty($request->input('to'))) {
            $apiRequest->setCreatedTo($request->input('to'));
            $apiRequest->setUpdatedTo($request->input('to'));
        }

        if (!empty($request->input('since'))) {
            $apiRequest->setSinceId($request->input('since'));
        }

        if (!empty($request->input('max'))) {
            $apiRequest->setMaxId($request->input('max'));
        }

        if (!empty($request->input('id'))) {
            $apiRequest->setId($request->input('id'));
        }

        if (!empty($request->input('foreign'))) {
            $apiRequest->setForeignKey($request->input('foreign'));
        }

        if ($active || $inactive) {
            $differencesBuilder = ResourceSettingDifference::byResourceSettingId($project_id->getResourceSettings($resource_id)->first()->id)
                ->byEndpoint($endpoint);

            if (!$active || !$inactive) {
                if ($active) {
                    $differencesBuilder->active();
                }

                if ($inactive) {
                    $differencesBuilder->inactive();
                }
            }

            $differences = $differencesBuilder->get();
            $apiRequest->setDifferences(new Differences());

            foreach ($differences as $difference) {
                switch ($difference->type) {
                    case Differences::TYPE_COLUMNS:
                        $apiRequest->getDifferences()->addColumn($difference->difference);
                        break;
                    case Differences::TYPE_TABLE_ALIAS:
                        $apiRequest->getDifferences()->addTableAlias($difference->difference);
                        break;
                    case Differences::TYPE_CONDITION:
                        $apiRequest->getDifferences()->addAdditionalCondition($difference->difference);
                        break;
                }
            }
        }

        foreach ($requestManager->get() as $item) {
            vd($item);
        }
    }

    public function selectEndpoint(Request $request) {
        return self::CONTROL_MAP[$request->input("endpoint")];
    }

    private function getEshopInformation(ImportEshopSdk $sdk) {
        $infoManager = $sdk->getEshopInformationManager();

        foreach ($infoManager->get() as $info) {
            return $info->getData();
        }
    }

    /**
     * @return array
     */
    public function debugData() {
        return ['endpoints' => self::ENDPOINTS];
    }
}