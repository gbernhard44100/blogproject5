<?php

namespace Lib\GBFram\Form;

use Lib\GBFram\Hydrator;

abstract class Field
{

    protected $label;
    protected $name;
    protected $value;
    protected $validators = [];
    protected $fieldType;
    protected $errorMessage;

    use Hydrator;

    public function __construct(array $data)
    {
        $this->receiveHydratation($data);
    }

    public function label()
    {
        return $this->label;
    }

    public function name()
    {
        return $this->name;
    }

    public function value()
    {
        return $this->value;
    }

    public function validators()
    {
        return $this->validators;
    }

    public function fieldType()
    {
        return $this->fieldType;
    }

    public function errorMessage()
    {
        return $this->errorMessage;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function setName(string $name)
    {

        $this->name = $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setValidators(array $validators)
    {
        foreach ($validators as $validator) {
            if ($validator instanceof Validator && !in_array($validator, $this->validators)) {
                $this->validators[] = $validator;
            }
        }
    }

    public function setFieldType(string $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function isValid()
    {
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($this->value)) {
                $this->errorMessage = $validator->errorMessage();
                return false;
            }
        }

        return true;
    }

}
