<?php

require_once './autoload.php';


try {
    \Library\Session::getInstance()->start();
    \Library\Bootstrap::getInstance()->init();
} catch (\Exception $e) {
    new \Library\Exceptions\Handler($e);
}