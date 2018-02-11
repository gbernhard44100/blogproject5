<?php

namespace Lib\GBFram;

abstract class Manager extends ApplicationComponent {

    protected $dao;

    public function __construct(Application $app, $dao) {
        parent::__construct($app);
        $this->dao = $dao;
    }

    public function dao() {
        return $this->dao;
    }

}
