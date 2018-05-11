<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet\Objects;


class Project extends \stdClass {

    // rs.created_at', 'p.id', 'p.user_id', 'rs.active as rs_active

    public $id;

    public $user_id;

    public $rs_active;

    public $created_at;

    public $important = 0;

    public function __construct($object = null) {
        $data = (array) $object;
        foreach ($data as $name => $value){
            $this->{$name} = $value;
        }

    }

    public function getImportantClass() {
        return "important-".$this->important;
    }


}