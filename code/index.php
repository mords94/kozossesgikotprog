<?php
require_once 'app/lib/helpers.php';
require_once 'app/lib/View.php';
require_once 'app/lib/ViewBag.php';
require_once 'app/lib/NoMethodException.php';
require_once  'app/lib/BaseModel.php';
require_once 'app/Model.php';
require_once 'app/lib/BaseController.php';
require_once 'app/Controller.php';
require_once 'app/lib/NoMethodException.php';
require_once 'app/lib/Request.php';
require_once 'app/lib/Bootstrap.php';


try {
    Bootstrap::getInstance()->init();
} catch (NoMethodException $e) {
    error_page('Nincs ilyen metodus a controllerben. <br> <ul><h2>Elerheto metodusok: <h2><li>'. implode($e->listMethods(), '</li><li>'). '</li></ul>');

} catch (Exception $e) {
    error_page($e->getMessage());
}