<?php

namespace Lib\GBFram\Form;

class IntValidator extends Validator
{

    function __construct($errorMessage)
    {
        parent::__construct($errorMessage);
    }

    public function isValid($value)
    {
        return is_int($value) && $value > 0 ? TRUE : FALSE;
    }

}
