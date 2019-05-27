<?php

namespace App\Http\Controllers\Open\Monitoring\Onboarding\Platform\Objects;


use App\Helpers\Monitoring\Onboarding\Platform;

class Project extends \stdClass {

    // rs.created_at', 'p.id', 'p.user_id', 'rs.active as rs_active

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var int
     */
    public $rs_active;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var int
     */
    public $important = 0;

    /**
     * @var int
     */
    public $eshop_type_id;

    /**
     * Project constructor.
     * @param null $object
     */
    public function __construct($object = null) {
        $data = (array) $object;
        foreach ($data as $name => $value){
            $this->{$name} = $value;
        }

    }

    /**
     * @return string
     */
    public function getImportantClass() {
        return "important-".$this->important;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPlatformCode() {
        return Platform::getPlatformCode($this->eshop_type_id);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPlatformClass() {
        return "platform-".$this->getPlatformCode();
    }


    /**
     * @param $name
     * @return null
     */
    public function __get($name) {
        return null;
    }
}