<?php

namespace Lib\GBFram\Form;

class LengthValidator extends Validator
{

    private $_length;

    public function __construct($errorMessage, $length = null)
    {
        parent::__construct($errorMessage);
        $this->setLength($length);
    }

    public function isValid($value)
    {
        if (isset($this->_length)) {
            return strlen($value) <= $this->_length && $value != '';
        } else {
            return $value != '';
        }
    }

    public function setLength($length)
    {
        $this->_length = $length;
    }

}
