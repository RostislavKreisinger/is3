<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\EshopType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;

/**
 * Class EshopTypesController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class EshopTypesController extends Controller {
    /**
     * @return Collection
     */
    public function index(): Collection {
        return EshopType::all();
    }
}