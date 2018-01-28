<?php
namespace App\Blog;

use Lib\GBFram\Application;

class BlogApplication extends Application
{
  public function __construct()
  {
    parent::__construct();

    $this->name = 'Blog';
  }

  public function run()
  {      
    $controller = $this->getController();       
    $controller->execute(); 
    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->send();
  }
}
