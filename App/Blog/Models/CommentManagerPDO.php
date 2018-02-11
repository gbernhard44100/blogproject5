<?php

namespace App\Blog\Models;

use Lib\GBFram\ManagerPDO;
use Lib\GBFram\Application;

class CommentManagerPDO extends ManagerPDO {

    function __construct(Application $app, $dao) {
        parent::__construct($app, $dao);
        $this->table = 'comment';
    }

}
