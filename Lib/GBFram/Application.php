<?php
namespace Lib\GBFram;
 
abstract class Application
{
  protected $httpRequest;
  protected $httpResponse;
  protected $name;
  protected $user;
  protected $config;
 
  public function __construct($name)
  {
    $this->name = $name;
    $this->httpRequest = new HTTPRequest($this);
    $this->httpResponse = new HTTPResponse($this);
    $this->user = new User($this);
    $this->config = new Config($this); 
  }
 
  public function getController()
  {
    $controllerClass = 'App\\'.$this->name.'\\Modules\\'.$this->httpRequest->route()->module().'\\'.$this->httpRequest->route()->module().'Controller';
    return new $controllerClass($this, $this->httpRequest->route()->module(), $this->httpRequest->route()->action());
  }
 
  public function run(){
    if($this->httpRequest->route()){
        $controller = $this->getController();       
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
 
  public function user()
  {
    return $this->user;
  }
}