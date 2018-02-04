<?php
namespace Lib\GBFram;
 
class Router extends ApplicationComponent
{
  protected $routes = [];
 
  const NO_ROUTE = 1;
 
  function __construct(Application $app,string $routesFilePath) {
    parent::__construct($app);
    $xml = new \DOMDocument;
    $xml->load($routesFilePath);
 
    $routes = $xml->getElementsByTagName('route');
 
    // On parcourt les routes du fichier XML.
    foreach ($routes as $route)
    {
      $vars = [];
 
      // On regarde si des variables sont présentes dans l'URL.
      if ($route->hasAttribute('vars'))
      {
        $vars = explode(',', $route->getAttribute('vars'));
      }
 
      // On ajoute la route au routeur.
      $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
    }
  }
  
  public function addRoute(Route $route)
  {
    if (!in_array($route, $this->routes))
    {
      $this->routes[] = $route;
    }
  }

  public function getController($url)
  {
    $route = $this->getRoute($url);
    $controllerClass = 'App\\'.$this->app->name().'\\Modules\\'.$route->module().'\\'.$route->module().'Controller';
    return new $controllerClass($this->app, $route->module(), $route->action());
  }

  public function getRoute($url)
  {
    foreach ($this->routes as $route)
    {
      // Si la route correspond à l'URL
      if (($varsValues = $route->match($url)) !== false)
      {
        // Si elle a des variables
        if ($route->hasVars())
        {
          $varsNames = $route->varsNames();
          $listVars = [];
 
          // On crée un nouveau tableau clé/valeur
          // (clé = nom de la variable, valeur = sa valeur)
          foreach ($varsValues as $key => $match)
          {
            // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
            if ($key !== 0)
            {
              $listVars[$varsNames[$key - 1]] = $match;
            }
          }
 
          // On assigne ce tableau de variables � la route
          $route->setVars($listVars);
        }
        return $route;
      }
    }
 
    throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
  }
}