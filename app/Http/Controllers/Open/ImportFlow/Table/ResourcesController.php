<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;

/**
 * Class ResourcesController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ResourcesController extends Controller {
    /**
     * @return Collection
     */
    public function index(): Collection {
        return Resource::all();
    }
}