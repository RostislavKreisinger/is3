<?php

namespace App\Helpers\Monitoring\Onboarding;


use Monkey\Constants\MonkeyData\Resource\EshopType;
use \Monkey\Constants\Inside\Platform as PlatformConstants;

class Platform {

    const ALL = "all";

    const PLATFORM_CODE = [
        self::ALL => [EshopType::CODE_SHOPTETINSIDE, EshopType::CODE_EPAGESINSIDE, EshopType::CODE_ECWID],
        PlatformConstants::CODE_SHOPTET => EshopType::CODE_SHOPTETINSIDE,
        PlatformConstants::CODE_VILKAS => EshopType::CODE_EPAGESINSIDE,
        PlatformConstants::CODE_ECWID => EshopType::CODE_ECWID
    ];


    /**
     * @param $platformCode
     * @return int[]
     * @throws \Exception
     */
    public static function getEshopType($platformCode) {
        if(!array_key_exists($platformCode, Platform::PLATFORM_CODE)){
            throw new \Exception("Unsupported platform code");
        }
        $eshopTypes = static::PLATFORM_CODE[$platformCode];
        if(!is_array($eshopTypes)){
            $eshopTypes = array($eshopTypes);
        }
        return $eshopTypes;
    }

    /**
     * @param $eshopTypeId
     * @return string
     * @throws \Exception
     */
    public static function getPlatformCode($eshopTypeId) {
        foreach (static::PLATFORM_CODE as $platformCode => $eshopType){
            if($eshopTypeId == $eshopType){
                return $platformCode;
            }
        }
        throw new \Exception("Unsupported eshop type ID");
    }

}