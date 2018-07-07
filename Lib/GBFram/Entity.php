<?php

namespace Lib\GBFram;

abstract class Entity
{

    protected $repository;

    public function getRepository()
    {
        return $this->repository;
    }

}
