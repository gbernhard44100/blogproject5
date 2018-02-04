<?php
namespace Lib\GBFram;
 
abstract class Application
{
  protected $httpRequest;
  protected $httpResponse;
  protected $router;
  protected $name;
  protected $config;
 
  public function __construct($name)
  {
    $this->name = $name;
    $this->httpRequest = new HTTPRequest($this);
    $this->httpResponse = new HTTPResponse($this);
    $this->router = new Router($this,'App/'.$this->name.'/Config/routes.xml');
    $this->config = new Config($this); 
  }
 
  public function run(){    
    session_start();
    $this->httpRequest->setRoute($this->router->getRoute($this->httpRequest->requestURI()));
    
    if($this->httpRequest->route()){       
        $controller = $this->router->getController($this->httpRequest->requestURI());
        $controller->execute(); 
        $this->httpResponse->setPage($controller->page());        
    }
    else{
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
 
  public function config()
  {
    return $this->config;
  }
}