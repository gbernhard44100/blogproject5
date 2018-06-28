<?php

namespace Lib\GBFram;

class Managers extends ApplicationComponent 
{

    protected $api = null;
    protected $dao = null;
    protected $managers = [];

    public function __construct($app, $api, $dao) 
    {
        parent::__construct($app);

        $this->api = $api;
        $this->dao = $dao;
    }

    public function getManagerOf($module) 
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module spécifié est invalide');
        }
        if (!isset($this->managers[$module])) {
            $manager = 'App\\' . $this->app->name() . '\\Models\\' . $module . 'Manager' . $this->api;
            $this->managers[$module] = new $manager(parent::app(), $this->dao);
        }
        return $this->managers[$module];
    }

}
