<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Http\Controllers\ApiController;

/**
 * Class ResourcesController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ResourcesController extends ApiController {
    public function getEndpoint(): string {
        return 'catalogs/resources';
    }
}