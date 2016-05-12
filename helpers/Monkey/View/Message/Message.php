<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View\Message;

/**
 * Description of MessageList
 *
 * @author Tomas
 */
class Message {

    private $message;
    private $class;

    public function __construct($message) {
        $this->setMessage($message);
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function __toString() {
        return $this->getMessage();
    }

}
