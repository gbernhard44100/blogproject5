<?php
namespace Lib\GBFram;
 
class HTTPRequest extends ApplicationComponent
{
  protected $route;
    
  function __construct(Application $app){
    parent::__construct($app);
    $router = new Router('App/'.$app->name().'/Config/routes.xml');
    try
    {
      // On récupère la route correspondante à l'URL.
        $this->route = $router->getRoute($this->requestURI());
    }
    catch (\RuntimeException $e)
    {
      if ($e->getCode() == Router::NO_ROUTE)
      {
        // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
        $this->route = null;
      }
    }
 
    // On ajoute les variables de l'URL au tableau $_GET.
    $_GET = array_merge($_GET, $this->route->vars());
  }
  
  public function route(){
      return $this->route;
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
    return isset($_POST[$key]) ? $_POST[$key] : null;
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