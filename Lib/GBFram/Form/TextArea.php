<?php

namespace Lib\GBFram\Form;

class TextArea extends Field
{

    private $rows = "10";
    private $cols = "80";

    public function __construct(array $data)
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
