<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 15. 3. 2019
 * Time: 8:57
 */

namespace App\Helpers\Monitoring\ImportFlow\Exception;


class UnknownMonitoringAttributeException extends \Exception
{
    public function __construct(string $attrName = ""){
        parent::__construct("Unknown IF monitoring attribute '{$attrName}'");
    }
}