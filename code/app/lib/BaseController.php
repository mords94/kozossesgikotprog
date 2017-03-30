<?php

class BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function error(Request $request)
    {
        return view('inc/error', [
            'message' => $request->post('message')
        ]);
    }

}