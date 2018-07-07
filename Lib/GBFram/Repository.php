<?php

namespace Lib\GBFram;

abstract class Repository extends ApplicationComponent
{

    protected $dao;

    public function __construct(Application $app, $dao)
    {
        parent::__construct($app);
        $this->dao = $dao;
    }

    public function dao()
    {
        return $this->dao;
    }

}
