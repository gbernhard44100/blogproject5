<?php

namespace App\Blog\Entity;

use \Lib\GBFram\Entity;

class User extends Entity
{

    protected $id;
    protected $username;
    protected $password;
    protected $valid = false;

    public function __construct()
    {
        $this->repository = 'App\Blog\Models\UserRepositoryPDO';
    }

    public function id()
    {
        return $this->id;
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function setValid()
    {
        $this->valid = true;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

}
