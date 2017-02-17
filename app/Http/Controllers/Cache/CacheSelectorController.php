<?php

namespace App\Http\Controllers\Cache;

use App\Http\Controllers\BaseViewController;
use Exception;
use Monkey\CacheV2\Storage\MemcachedStorage;
use Monkey\Connections\MDCacheConnections;

class CacheSelectorController extends BaseViewController {


    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        try {
            /**
             * @var $storage MemcachedStorage
             */
            $storage = MDCacheConnections::getCurrencyRatesConnection();
             vd($storage->getMemcached());
             vde($storage->getMemcached()->getAllKeys());
        } catch (Exception $e) {
            vde($e);
        }catch (\Error $e) {
            vde($e);
        }

    }


}
