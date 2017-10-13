<?php
namespace Library;

class Request
{

    protected $post;

    protected $get;

    protected $file;

    public static function make() {
        return new self($_GET, $_POST);
    }

    public function __construct($get = [], $post = [], $file = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->file = $file;
    }

    public function get($key = null)
    {
        if(is_null($key)) return $this->get;
        return $this->get[$key];
    }

    public function post($key = null)
    {
        if(is_null($key)) return $this->post;
        return $this->post[$key];
    }

    public function posts($array) {
        $posts = [];
        foreach($array as $key) {
            $posts[] = $this->post($key);
        }

        return $posts;
    }

    public function file($key = null)
    {
        if(is_null($key)) return $this->file;
        return $this->file[$key];
    }

    public function all()
    {
        return array_merge($this->post, $this->get);
    }

    public function has($key) {
        return isset($this->all()[$key]);
    }

}