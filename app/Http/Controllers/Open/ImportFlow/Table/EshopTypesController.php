<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\ApiController;

/**
 * Class EshopTypesController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class EshopTypesController extends ApiController {
    /**
     * @return string
     */
    public function getEndpoint(): string {
        return 'catalogs/eshop-types';
    }
}