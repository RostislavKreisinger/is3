<?php

namespace App\Http\Controllers\Button;

use App\Http\Controllers\BaseAuthController;
use Monkey\View\Message\MessageList;
use Session;


abstract class Controller extends BaseAuthController {

    /**
     *
     * @var MessageList 
     */
    private $messages;
    
    public function __construct() {
        parent::__construct();
        $this->messages = MessageList::load();
    }
    
    protected function getRedirect() {
        return redirect()->back();
    }
    
    
    abstract protected function buttonAction();
    
    public function getIndex() {
        $redirect = $this->getRedirect();
        call_user_func_array([$this, 'buttonAction'], func_get_args());
        return $redirect;
    }
    
    /**
     * 
     * @return MessageList
     */
    public function getMessages() {
        return $this->messages;
    }



    
}
