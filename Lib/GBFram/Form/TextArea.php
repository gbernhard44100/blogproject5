<?php

namespace Lib\GBFram\Form;

class TextArea extends Field {

    function __construct(array $data) {
        parent::__construct($data);
        $this->fieldType = 'TextArea';
    }

    Private $_rows = "10";
    Private $_cols = "80";

    public function rows() {
        return $this->_rows;
    }

    public function cols() {
        return $this->_cols;
    }

    public function setRows(int $rows) {
        $this->_rows = $rows;
    }

    public function setCols(int $cols) {
        $this->_cols = $cols;
    }

}
