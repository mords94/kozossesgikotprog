<?php

namespace Library;

class Auth extends Singleton
{
    private $request;

    public static function user()
    {
        return self::getInstance()->getUser();
    }

    public function __construct()
    {
        $this->request = Request::make();
    }

    public function update($key, $value) {
        $user = $this->getUser();
        $user[$key] = $value;
        session('user', $user);
    }

    public function register($user)
    {
        session('user', $user);
    }

    public function getUser() {
        return session('user');
    }

    public function guard()
    {
        return !(session('user'));
    }
}