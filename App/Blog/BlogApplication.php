<?php
namespace App\Blog;

use Lib\GBFram\Application;

class BlogApplication extends Application
{
  public function __construct()
  {
    parent::__construct('Blog');
  }
}
