<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Blog\Entity;

use \Lib\GBFram\Entity;

/**
 * Description of Connection
 *
 * @author CathyGaetanB
 */
class Connection extends Entity {

    protected $id;
    protected $username;
    protected $password;
    protected $valid = FALSE;

    public function id() {
        return $this->id;
    }

    public function username() {
        return $this->username;
    }

    public function password() {
        return $this->password;
    }

    public function valid() {
        return $this->valid;
    }

    public function setValid() {
        $this->valid = TRUE;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

}
