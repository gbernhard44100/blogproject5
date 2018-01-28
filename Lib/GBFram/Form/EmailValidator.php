<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lib\GBFram\Form;

/**
 * Description of EmailValidator
 *
 * @author CathyGaetanB
 */
class EmailValidator extends Validator{
    
    function __construct($errorMessage) {
        parent::__construct($errorMessage);
    }

    public function isValid($value){
        return preg_match('(.+)@(.+)\.(.{2,3})', $value)>0 ? TRUE : FALSE;
    }
}
