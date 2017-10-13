<?php

namespace Library;

class ViewBag
{
    protected static $instance;

    protected $bag;

    public function __construct() {}

    public function initialise(array $bag)
    {
        $this->bag = $bag;
    }

    public static function get()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function all()
    {
        return $this->bag;
    }

}