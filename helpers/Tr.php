<?php

use Monkey\Translator\TranslatorBase;

/**
 * Description of Tr
 *
 * @author toms049
 */
class Tr extends TranslatorBase {

    protected $usecache = false;

    protected function getNewTranslationModel() {
        return new App\Model\Translation();
    }

    protected function putNewTranslationIntoDB() {
        return false;
    }

}
