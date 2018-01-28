<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lib\GBFram\Form;

/**
 * Description of LengthValidator
 *
 * @author CathyGaetanB
 */
class LengthValidator extends Validator {
    
    private $_length;
    
    public function __construct($errorMessage, $length = null){
        parent::__construct($errorMessage);
        $this->setLength($length);
    }
    
    public function isValid($value){        
        if(isset($this->_length)){
            return strlen($value)<= $this->_length && $value != '';
        }
         else {
             return $value != '';
         }
    }
    
    public function setLength($length){
        $this->_length = $length;
    }
    
}
