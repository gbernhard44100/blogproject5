<?php

namespace Lib\GBFram;

class Router extends ApplicationComponent
{

    protected $routes = [];
    protected $controllers = [];

    const NO_ROUTE = 1;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        /** Load the route from the routes.xml in the Config folder */
        $xml = new \DOMDocument;
        $xml->load('App/' . $app->name() . '/Config/routes.xml');

        $routes = $xml->getElementsByTagName('route');

        foreach ($routes as $route) {
            $vars = [];
            if ($route->hasAttribute('vars')) {
                $vars = explode(',', $route->getAttribute('vars'));
            }
            $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
        }

        /** Load the controllers from the config.xml in the Config folder */
        $xml = new \DOMDocument;
        $xml->load('App/' . $app->name() . '/Config/config.xml');
        $controllers = $xml->getElementsByTagName('controller');
        foreach ($controllers as $controller) {
            $module = $controller->getAttribute('module');
            $class = $controller->getAttribute('class');
            if (!class_exists($class)) {
                throw new \InvalidArgumentException(
                'Le controller spécifié dans le fichier config n\'existe pas.'
                );
            }
            $this->controllers[$module] = new $class($app, $module);
        }
    }

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    public function getController($url)
    {
        $route = $this->getRoute($url);
        $controller = $this->controllers[$route->module()];
        $controller->setAction($route->action());
        $controller->setView($route->action());
        return $controller;
    }

    public function getRoute($url)
    {
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
