<?php

namespace Lib\GBFram;

class HTTPRequest extends ApplicationComponent
{

    protected $route;

    public function route()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        $_GET = array_merge($_GET, $this->route->vars());
    }

    public function cookieData($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    public function cookieExists($key)
    {
        return isset($_COOKIE[$key]);
    }

    public function getData($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    public function getExists($key)
    {
        return isset($_GET[$key]);
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function postData($key)
    {
        if (isset($_POST['postToken']) && isset($_SESSION['postToken'])) {
            if ($_POST['postToken'] != $_SESSION['postToken']) {
                throw new \UnexpectedValueException('Le formulaire édité à un token non valide.');
            }
            return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : null;
        }    
    }

    public function postExists($key)
    {
        return isset($_POST[$key]);
    }

    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }

}
