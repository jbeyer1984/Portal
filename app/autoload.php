<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

//if (class_exists('PHPUnit_Runner_Version')) {
//  set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../vendor/mockery/library/');
//  require_once('Mockery/Loader.php');
//  $loader = new \Mockery\Loader;
//  $loader->register();
//}

return $loader;
