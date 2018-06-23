<?php

namespace Lib\GBFram;

class Router extends ApplicationComponent {

    protected $routes = [];

    const NO_ROUTE = 1;

    function __construct(Application $app, string $routesFilePath) {
        parent::__construct($app);
        $xml = new \DOMDocument;
        $xml->load($routesFilePath);

        $routes = $xml->getElementsByTagName('route');

        
        foreach ($routes as $route) {
            $vars = [];

            
            if ($route->hasAttribute('vars')) {
                $vars = explode(',', $route->getAttribute('vars'));
            }

            
            $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
        }
    }

    public function addRoute(Route $route) {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    public function getController($url) {
        $route = $this->getRoute($url);
        $controllerClass = 'App\\' . $this->app->name() . '\\Modules\\' . $route->module() . '\\' . $route->module() . 'Controller';
        return new $controllerClass($this->app, $route->module(), $route->action());
    }

    public function getRoute($url) {
        foreach ($this->routes as $route) {
            
            if (($varsValues = $route->match($url)) !== false) {
                
                if ($route->hasVars()) {
                    $varsNames = $route->varsNames();
                    $listVars = [];

                    
                    foreach ($varsValues as $key => $match) {
                        
                        if ($key !== 0) {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    
                    $route->setVars($listVars);
                }
                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

}
