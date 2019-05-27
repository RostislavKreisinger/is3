<?php


namespace App\Helpers\IO;


use Throwable;

interface IErrorReporter {

    /**
     * @param Throwable $exception
     */
    public function report(Throwable $exception);

}