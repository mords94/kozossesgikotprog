<?php

/**
 * Created by PhpStorm.
 * User: drava
 * Date: 2017. 03. 30.
 * Time: 1:44
 */



class Bootstrap
{
    protected static $instance;

    private $url;

    private $parameters = [];

    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
    }

    public function init() {
        $this->route();
        $this->loadPage();
    }

    private function route()
    {

        if(isset($_GET['url'])) {
            $this->url = $_GET['url'];
            unset($_GET['url']);
            if($this->multiple($this->url)) {
                $array = explode('/', $this->url);
                $this->url = $array[0];
                unset($array[0]);
                $this->parameters = array_values($array);
            }

        } else {
            header("Location: /home");
        }
    }

    private function multiple($str) {
        return strpos($str, '/') !== false;
    }

    private function createRequest()
    {
        return new Request(array_merge($this->parameters, $_GET), $_POST, $_FILES);
    }

    private function loadPage()
    {
        $controller = new Controller();
        $method = $this->url;

        if(method_exists($controller, $method)) {
            $view = $controller->{$method}($this->createRequest());

            if(is_string($view)) echo $view;
            elseif($view instanceof View) {
                $view->inc();

                extract(ViewBag::get()->all());
            }
        } else {
            throw new NoMethodException($controller, $method);
        }
    }

}