<?php

const STATUS_WAITING = 0;
const STATUS_APPROVED = 1;

function view($file, array $bag = [])
{
    ViewBag::get()->initialise($bag);

    return new View($file);
}

function dump($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function dd($var)
{
    dump($var);
    die;
}

function error_page($message)
{
    (new Controller())->error(new Request(
        [],
        [
            'message' => $message,
        ]
    ))->inc(false);
}

function model()
{
    return Bootstrap::getInstance()->getController()->getModel();
}

function database()
{
    return model()->getDatabase();
}

function auth() {
    return !Auth::getInstance()->guard();
}

function session($key, $value = null)
{
    if ($value) {
        Session::getInstance()->set($key, $value);
    }

    return Session::getInstance()->get($key);
}

function redirect($to, $with = [])
{
    if(count($with) > 0) {
        $to.='?'.array_keys($with)[0].'='.array_values($with)[0];
    }
    if(count($with) > 1) {
        for($i = 1; $i<count($with); $i++) {
            $to.='&'.array_keys($with)[$i].'='.array_values($with)[$i];
        }
    }

    header("Location: " . $to);
}

function secure() {
    if(!auth()) {
        redirect('/home');
    }
}