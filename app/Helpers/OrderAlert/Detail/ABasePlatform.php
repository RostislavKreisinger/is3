<?php

namespace App\Helpers\OrderAlert\Detail;


use App\Helpers\OrderAlert\Detail\Platform\Lightspeed;
use Monkey\Constants\MonkeyData\Resource\EshopType;

abstract class ABasePlatform {

    private static $platforms = [
        EshopType::CODE_LIGHTSPEED => Lightspeed::class
    ];




    public static function getPlatformObject(int $eshopTypeID): ABasePlatform {
        if(!array_key_exists($eshopTypeID, static::$platforms)){
            throw new \Exception("Platform do not exists:", $eshopTypeID);
        }
        return new static::$platforms[$eshopTypeID];
    }



    public abstract function getRawOrders(int $storeId): array;

    public abstract function getVisibleColumns(): array;


    /**
     * @return array
     */
    public abstract function getOmittedColumns(): array ;

    public abstract function getColumnsConfig(): string;

    /**
     * @param string $orderStatus
     * @return string|null
     */
    public abstract function translateOrderStatus(string $orderStatus);
}