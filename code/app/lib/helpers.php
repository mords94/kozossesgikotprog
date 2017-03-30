<?php


function view($file, array $bag = []) {
    ViewBag::get()->initialise($bag);
    return new View($file);
}

function dump($var) {
    echo '<pre>';
        print_r($var);
    echo '</pre>';
}

function dd($var) {
    dump($var);
    die;
}

function error_page($message) {
    (new Controller())->error(new Request(
        [],
        [
            'message' => $message
        ]
    ))->inc(false);
}