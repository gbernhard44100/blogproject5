<?php

namespace Lib\GBFram;

abstract class Controller extends ApplicationComponent
{

    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';
    protected $rm = null;

    public function __construct(Application $app, $module)
    {
        parent::__construct($app);
        $this->setModule($module);
        $this->page = new Page($app, $module);
        $this->rm = new RepositoriesManager($app);
    }

    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);
        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action "' . $this->action . '" n\'est pas définie sur ce module');
        }

        $this->$method($this->app->httpRequest());
    }

    public function page()
    {
        return $this->page;
    }

    public function setModule($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
        }

        $this->module = $module;
    }

    public function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }

    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }
        $this->page->setContentFile($view . '.twig');
    }

}
