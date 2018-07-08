<?php

namespace Lib\GBFram\Form;

class IntValidator extends Validator
{

    public function __construct($errorMessage)
    {
        parent::__construct($errorMessage);
    }

    public function isValid($value)
    {
        return is_int($value) && $value > 0 ? true : false;
    }

}
