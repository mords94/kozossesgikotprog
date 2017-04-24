<?php

require_once 'app/lib/Singleton.php';
require_once 'app/lib/PropertyTrait.php';
require_once 'app/lib/Session.php';
require_once 'app/lib/Auth.php';
require_once 'app/lib/helpers.php';
require_once 'app/lib/View.php';
require_once 'app/lib/ViewBag.php';
require_once 'app/lib/NoMethodException.php';
require_once 'app/lib/Driver.php';
require_once 'app/lib/BaseModel.php';
require_once 'app/Model.php';
require_once 'app/lib/BaseController.php';
require_once 'app/Controller.php';
require_once 'app/lib/NoMethodException.php';
require_once 'app/lib/Request.php';
require_once 'app/lib/Bootstrap.php';


Session::getInstance()->start();

try {
    Bootstrap::getInstance()->init();
    dump(Bootstrap::getInstance()->getController()->model->getDatabase()->getLastSql());

} catch (NoMethodException $e) {
    error_page('Nincs ilyen metodus a controllerben. <br> <ul><h2>Elerheto metodusok: <h2><li>'. implode($e->listMethods(), '</li><li>'). '</li></ul>');
} catch (PDOException $e) {
    error_page('HIBA VAN AZ SQL PARANCSBAN:<br><pre>'
        .Bootstrap::getInstance()->getController()->model->getDatabase()->getLastSql()
        .'</pre><br>'.$e->getMessage()
        .'<br><br><br><pre>'
        .$e->getTraceAsString()
        .'</pre>');
} catch (Exception $e) {
    error_page($e->getMessage());
}