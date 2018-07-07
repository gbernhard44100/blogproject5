<?php

namespace Lib\GBFram\Form;

use Lib\GBFram\HTTPRequest;

Class Form
{

    private $name;
    private $entity;
    private $targetUrl;
    private $method = 'post';
    private $fields = array();

    public function __construct($name, $entity, $target, HTTPRequest $request = null)
    {
        $this->setName($name);
        $this->setEntity($entity);
        $this->setTargetUrl($target);
        if (!is_null($request)) {
            $this->hydrateFromPostRequest($request);
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function fields()
    {
        return $this->fields;
    }

    public function targetUrl()
    {
        return $this->targetUrl;
    }

    public function method()
    {
        return $this->method;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setTargetUrl(string $url)
    {
        $this->targetUrl = $url;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function isValid()
    {
        $valid = true;

        foreach ($this->fields as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function hydrateFromPostRequest(HTTPRequest $request)
    {
        if (!$request->method() == 'POST') {
            throw new \Exception('Il ne s\'agit pas d\'une requête POST');
        }
        $object = new \ReflectionObject($this->entity);
        foreach ($object->getProperties() as $attribut) {
            $method = 'set' . ucfirst($attribut->getName());
            if (is_callable([$this->entity, $method]) && $request->postData($attribut->getName())) {
                $this->entity->$method($request->postData($attribut->getName()));
            }
        }
    }

}
