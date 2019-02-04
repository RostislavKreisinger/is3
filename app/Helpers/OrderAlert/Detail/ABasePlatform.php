<?php

namespace App\Helpers\OrderAlert\Detail;

use App\Helpers\OrderAlert\Detail\Platform\LightspeedPlatform;
use App\Helpers\OrderAlert\Detail\Platform\ShoptetPlatform;
use App\Helpers\OrderAlert\Detail\Platform\EPagesPlatform;
use App\Helpers\OrderAlert\Detail\Platform\WoocommercePlatform;
use Illuminate\Support\Collection;
use Monkey\Constants\MonkeyData\Resource\EshopType;

abstract class ABasePlatform {

    private static $platforms = [
        EshopType::CODE_LIGHTSPEED => LightspeedPlatform::class,
        EshopType::CODE_WOOCOMMERCE => WoocommercePlatform::class,
        EshopType::CODE_EPAGES=> EPagesPlatform::class,
        EshopType::CODE_SHOPTET => ShoptetPlatform::class
    ];


    /**
     * @param int $eshopTypeID
     * @return ABasePlatform
     * @throws \Exception
     */
    public static function getPlatformObject(int $eshopTypeID): ABasePlatform {
        if(!array_key_exists($eshopTypeID, static::$platforms)){
            throw new \Exception("Platform do not exists:", $eshopTypeID);
        }
        return new static::$platforms[$eshopTypeID];
    }


    public abstract function getRawOrders(int $storeId): Collection;

    public abstract function getVisibleColumns(): array;


    /**
     * @return array
     */
    public abstract function getOmittedColumns(): array ;

    public abstract function getColumnsConfig(): string;

    /**
     * @param string $orderStatus
     * @return null|string
     */
    public abstract function translateOrderStatus(string $orderStatus): string;
}