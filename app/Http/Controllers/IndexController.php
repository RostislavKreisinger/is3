<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Model\ImportPools\CurrencyEtlCatalog;
use App\Model\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Monkey\Helpers\Strings;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\View\View;
use Symfony\Component\Routing\Route;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {


    public function __construct(){
        parent::__construct();


    }

    public function getIndex() {


    }


}
