<?php

namespace App\Http\Controllers;


/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex() {
        return view('default.index', ['menu' => $this->prepareMenu()]);
    }
}