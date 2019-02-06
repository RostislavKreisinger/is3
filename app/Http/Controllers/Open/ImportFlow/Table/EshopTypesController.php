<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use Illuminate\Database\Eloquent\Collection;

/**
 * Class EshopTypesController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class EshopTypesController extends ApiController {
    protected $endpoint = "eshop_types";

    /**
     * @return Collection
     */
    public function index() {
        $url = $this->getUrl();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/vnd.api+json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($curl);
        curl_close($curl);
        return $results;
    }
}