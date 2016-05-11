<?php

namespace App\Http\Controllers\Admin\Error;

use App\Http\Controllers\Admin\Controller;

class IndexController extends Controller {

    public function getIndex() {
        $errorsResult = \App\Model\ImportSupport\ResourceError::orderBy('resource_id', 'ASC')
                ->orderBy('id', 'DESC')
                ->get()
        ;
        
        $errors = array();
        
        foreach($errorsResult as $error){
            if(!isset($errors[$error->resource_id])){
                $errors[$error->resource_id] = array();
            }
            $errors[$error->resource_id][] = $error;
        }
        $this->getView()->addParameter('errors', $errors);
        
        $resources = array();
        $resources[null] = "";
        foreach(\App\Model\Resource::all() as $resource){
            $resources[$resource->id] = $resource;
        }
        $this->getView()->addParameter('resources', $resources);
    }

}
