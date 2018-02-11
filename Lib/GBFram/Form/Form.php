<?php

namespace Lib\GBFram\Form;

use \Lib\GBFram\Entity;
use Lib\GBFram\Hydrator;

Class Form {

    private $_name;
    private $_entity;
    private $_targetUrl;
    private $_method = 'post';
    private $_fields = array();

    use Hydrator;

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    public function name() {
        return $this->_name;
    }

    public function fields() {
        return $this->_fields;
    }

    public function targetUrl() {
        return $this->_targetUrl;
    }

    public function method() {
        return $this->_method;
    }

    public function entity() {
        return $this->_entity;
    }

    public function addField(Field $field) {
        $this->_fields[] = $field;
    }

    public function setTargetUrl(string $url) {
        $this->_targetUrl = $url;
    }

    public function setMethod(string $method) {
        $this->_method = $method;
    }

    public function setEntity(Entity $entity) {
        $this->_entity = $entity;
    }

    public function isValid() {
        $valid = true;
        // On vérifie que tous les champs sont valides.
        foreach ($this->_fields as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }
        return $valid;
    }

}
