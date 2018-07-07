<?php

namespace Lib\GBFram\Form;

class TextArea extends Field
{

    Private $rows = "10";
    Private $cols = "80";

    function __construct(array $data)
    {
        parent::__construct($data);
        $this->fieldType = 'TextArea';
    }

    public function rows()
    {
        return $this->rows;
    }

    public function cols()
    {
        return $this->cols;
    }

    public function setRows(int $rows)
    {
        $this->rows = $rows;
    }

    public function setCols(int $cols)
    {
        $this->cols = $cols;
    }

}
