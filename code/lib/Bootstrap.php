<?php
namespace Library;

use \App\Controller;
use \Library\Exceptions\NoMethodException;
use \App\Services;

class Bootstrap
{
    protected static $instance;

    private $url;

    private $parameters = [];

    private $controller;

    private $services;

    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getController() {
        return $this->controller;
    }

    public function getServices() {
        return $this->services;
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
        $this->controller = new Controller();
        $this->services = new Services($this->controller);
        $method = $this->url;

        if(method_exists($this->controller, $method)) {
            $view = $this->controller->{$method}($this->createRequest());

            if(is_string($view)) echo $view;
            elseif($view instanceof View) {
                $view->inc();

                extract(ViewBag::get()->all());
            }
        } else {
            throw new NoMethodException($this->controller, $method);
        }
    }

}