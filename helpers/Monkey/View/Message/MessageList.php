<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View\Message;

use Session;

/**
 * Description of MessageList
 *
 * @author Tomas
 */
class MessageList {
    
    const SESSION_NAME = "view_render_message_session_storage";


    /**
     *
     * @var Message[] 
     */
    private $message =  array();
    
    /**
     *
     * @var Message[] 
     */
    private $warning =  array();
    
    /**
     *
     * @var Message[] 
     */
    private $error =  array();
    
    
    public function getMessage() {
        return $this->message;
    }

    public function getWarning() {
        return $this->warning;
    }

    public function getError() {
        return $this->error;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setWarning($warning) {
        $this->warning = $warning;
    }

    public function setError($error) {
        $this->error = $error;
    }
    
    /**
     * 
     * @param Message $message
     */
    public function addMessage($message) {
        if(is_string($message)){
            $message = new Message($message);
        }
        $message->setClass('success');
        $this->message[] = $message;
        $this->save();
    }

    /**
     * 
     * @param Message $warning
     */
    public function addWarning($warning) {      
        if(is_string($warning)){
            $warning = new Message($warning);
        }
        $warning->setClass('warning');
        $this->warning[] = $warning;
        $this->save();
    }

    /**
     * 
     * @param Message $error
     */
    public function addError($error) {
        if(is_string($error)){
            $error = new Message($error);
        }
        $error->setClass('danger');
        $this->error[] = $error;
        $this->save();
    }
    
    public function getAll() {
        Session::put(MessageList::SESSION_NAME, null);
        return array_merge($this->getError(), $this->getWarning(), $this->getMessage());
    }
    
    public static function load() {
        // Session::reflash();
        $serializedMessageList = Session::get(MessageList::SESSION_NAME, null);
        // vd($serializedMessageList);
        if($serializedMessageList === null){
            return new MessageList();
        }
        return unserialize($serializedMessageList);
    }


    public function save() {
        $serializedMessageList = serialize($this);
        Session::put(MessageList::SESSION_NAME, $serializedMessageList);
    }


}
