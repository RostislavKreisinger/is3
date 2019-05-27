<?php

namespace App\Helpers\IO;


use Throwable;

class ErrorReporter implements IErrorReporter {


    /**
     * @var bool
     */
    private $debug;

    public function __construct($debug = false) {
        $this->debug = $debug;
    }

    /**
     * @param Throwable $exception
     */
    public function report(Throwable $exception) {
        if($this->debug){
            vde($exception);
        }
    }
}