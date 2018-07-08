<?php

namespace Lib\GBFram\Form;

class LengthValidator extends Validator
{

    private $length;

    public function __construct($errorMessage, $length = null)
    {
        parent::__construct($errorMessage);
        $this->setLength($length);
    }

    public function isValid($value)
    {
        if (isset($this->length)) {
            return strlen($value) <= $this->length && $value != '';
        } else {
            return $value != '';
        }
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

}
