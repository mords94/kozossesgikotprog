<?php
namespace Library;

trait PropertyTrait
{

    public function setArray($array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function __toString()
    {
        return $this->name;
    }
}