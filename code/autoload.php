<?php
include_once './vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Test\\", 'test', true);
$classLoader->register();

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("App\\", 'app', true);
$classLoader->register();

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Library\\", 'lib', true);
$classLoader->register();




