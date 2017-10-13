<?php

const CLASS_PATHS = [
    'Library' => './lib',
    'App' => './app',
];

const PHP_EXT = '.php';

function autoload($class)
{
    $file = '';
    $class = str_replace("\\", "/", $class);
    $tree = explode("/", $class);

    $className = $tree[count($tree) - 1];

    $class = str_replace("Library\/", "", $class);

    if(isset($tree[count($tree) - 2])) {
        $type = ucfirst($tree[0]);
        array_shift($tree);
        if((CLASS_PATHS[$type])) {

            $file = CLASS_PATHS[$type].DIRECTORY_SEPARATOR.implode('/',$tree).PHP_EXT;
            if(file_exists($file)) {
                require_once $file;
            } else {
                goto error;
            }
        }
    } else {
        error: throw new Exception("Class found: ".implode('/',$tree));
    }
}

function includeDir($path)
{
    foreach (glob($path . '/*.php') as $filename) {
        require_once $filename;
    }
}

//includeDir("vendor/libs/Form");
//includeDir("config");
//includeDir("interfaces");
spl_autoload_register("autoload");
require_once './lib/helpers.php';



