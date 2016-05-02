<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource\TableConfig;

/**
 *
 * @author Tomas
 */
interface IJsonSerialize extends \JsonSerializable {
    //put your code here
    
    public function jsonDeserialize($serialize);
}
