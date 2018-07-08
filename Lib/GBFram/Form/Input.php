<?php

namespace Lib\GBFram\Form;

class Input extends Field
{

    private $type = 'text';

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->fieldType = 'Input';
    }

    public function type()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $alltypes = array('button', 'checkbox', 'color', 'date', 'datetime-local',
            'email', 'file', 'hidden', 'image', 'month', 'number', 'password',
            'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time',
            'url', 'week');
        if (in_array('text', $alltypes)) {
            $this->type = $type;
        } else {
            echo 'Error : the type of input you have selected doesn\'t exist in HTML';
        }
    }

}
