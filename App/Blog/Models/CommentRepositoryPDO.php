<?php

namespace App\Blog\Models;

use Lib\GBFram\RepositoryPDO;
use Lib\GBFram\Application;

class CommentRepositoryPDO extends RepositoryPDO
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->table = 'comment';
    }

}
