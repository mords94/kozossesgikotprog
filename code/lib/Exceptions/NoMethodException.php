<?php
namespace Library\Exceptions;


class NoMethodException extends \Exception
{

    protected $method;

    protected $class;

    public function __construct($object, $method)
    {
        $this->method = $method;
        $this->class = get_class($object);
    }

    public function listMethods()
    {

        $methods = get_class_methods($this->class);

        foreach($methods as $key => $method)
        {
            if(preg_match('/((__)([a-zA-Z0-9])|error)/', $method)) {
                unset($methods[$key]);
            }
        }

        return $methods;
    }

}