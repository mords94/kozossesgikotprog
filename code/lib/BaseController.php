<?php

namespace Library;

use \App\Model;

class BaseController
{
    public $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function getModel() {
        return $this->model;
    }

    public function error(Request $request)
    {
        return view('inc/error', [
            'message' => $request->post('message')
        ]);
    }

}