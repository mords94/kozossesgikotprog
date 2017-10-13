<?php
namespace Library\Exceptions;

use Library\Bootstrap;

class Handler
{
    function __construct(\Exception $e)
    {
        $this->boot($e);
    }

    function boot($e)
    {

        if ($e instanceof NoMethodException) {
            error_page('Nincs ilyen metodus a controllerben. <br> <ul><h2>Elerheto metodusok: <h2><li>'.implode($e->listMethods(), '</li><li>').'</li></ul>');
        }

        if ($e instanceof \PDOException) {
            error_page('ADATBÁZIS HIBA / HIBA VAN AZ SQL PARANCSBAN:<br><pre>'.Bootstrap::getInstance()->getController()->model->getDatabase()->getLastSql().'</pre><br>'.$e->getMessage().'<br><br><br><pre>'.$e->getTraceAsString().'</pre>');
        } else {
            error_page($e->getMessage());
        }
    }
}