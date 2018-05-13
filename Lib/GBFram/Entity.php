<?php

namespace Lib\GBFram;

abstract class Entity implements \ArrayAccess {

    protected $erreurs = [];

    use Hydrator;

    public function __construct(array $donnees = []) {
        if (!empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    public function isNew() {
        return empty($this->id);
    }

    public function erreurs() {
        return $this->erreurs;
    }

    public function offsetGet($var) {
        if (isset($this->$var) && is_callable([$this, $var])) {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value) {
        $method = 'set' . ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method])) {
            $this->$method($value);
        }
    }

    public function offsetExists($var) {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset() {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }

    public function hydrateFromPostRequest(HTTPRequest $request) {
        if (!$request->method() == 'POST') {
            throw new \Exception('Il ne s\'agit pas d\'une requête POST');
        }
        $object = new \ReflectionObject($this);
        foreach ($object->getProperties() as $attribut) {
            if ($attribut->isPrivate()) {
                $method = 'set' . ucfirst(substr($attribut->getName(), 1));

                if (is_callable([$this, $method]) && $request->postData(substr($attribut->getName(), 1))) {
                    $this->$method($request->postData(substr($attribut->getName(), 1)));
                }
            } elseif ($attribut->isProtected()) {
                $method = 'set' . ucfirst($attribut->getName());
                if (is_callable([$this, $method]) && $request->postData($attribut->getName())) {
                    $this->$method($request->postData($attribut->getName()));
                }
            }
        }
    }

}
