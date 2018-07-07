<?php

namespace Lib\GBFram;

abstract class Application
{

    protected $httpRequest;
    protected $httpResponse;
    protected $router;
    protected $name;

    public function __construct($name)
    {
        session_start();
        $this->name = $name;
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);
        $this->router = new Router($this);
    }

    public function run()
    {
        $this->httpRequest->setRoute($this->router->getRoute($this->httpRequest->requestURI()));

        if ($this->httpRequest->route()) {
            $controller = $this->router->getController($this->httpRequest->requestURI());
            $controller->execute();
            $this->httpResponse->setPage($controller->page());
        } else {
            $this->httpResponse->redirect404();
        }
        $this->httpResponse->send();
    }

    public function httpRequest()
    {
        return $this->httpRequest;
    }

    public function httpResponse()
    {
        return $this->httpResponse;
    }

    public function name()
    {
        return $this->name;
    }

}
