<?php
namespace Lib\GBFram\Form;

class Input extends Field {
    private $_type = 'text';
    
    function __construct(array $data) {
        parent::__construct($data);
        $this->fieldType = 'Input';        
    }
    
    public function type(){
        return $this->_type;
    }
    
    public function setType($type){
        $alltypes = array('button', 'checkbox', 'color', 'date', 'datetime-local',
            'email', 'file', 'hidden', 'image', 'month', 'number', 'password',
            'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time',
            'url', 'week');
        if (in_array('text', $alltypes)){
            $this->_type = $type;
        }
        else{
            echo 'Error : the type of input you have selected doesn\'t exist in HTML';
        }
    }
}

