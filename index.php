<?php

require 'Lib/GBFram/SplClassLoader.php';
use Lib\GBFram\SplClassLoader;

require 'vendor/autoload.php';


$GBFramLoader = new SplClassLoader('Lib\GBFram');
$GBFramLoader->register();

$appLoader = new SplClassLoader('App');
$appLoader->register();

$modelLoader = new SplClassLoader('Models', __DIR__.'\Lib\Vendors');
$modelLoader->register();

$entityLoader = new SplClassLoader('Entity', __DIR__.'\Lib\Vendors');
$entityLoader->register();

$entityLoader = new SplClassLoader('Form', __DIR__.'\Lib\Vendors');
$entityLoader->register();


$app= new App\Blog\BlogApplication();
$app->run();

