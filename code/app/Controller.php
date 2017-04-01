<?php


class Controller extends BaseController
{

    /**
     * ACTION: /home
     * VERB: GET
     *
     * @return View
     */
    public function home(Request $request)
    {
        $this->model->createTables();

        $tables = $this->model->getTables();

        return view('home',
            [
                'tables'     => $tables,
                'parameters' => $request->get(),
            ]
        );
    }

    /**
     * ACTION: /formpelda
     * VERB: GET
     *
     * @return View
     */
    public function formpelda(Request $request)
    {
        return view("formpelda");
    }

    function comment(Request $request)
    {
        $id = $request->get(0);



        return view('kommentek',
            [
                "komment" => $this->model->kommentleker($id),
                "id" => $id,
            ]
        );
    }

    /**
     * ACTION: /mentes
     * VERB: POST
     *
     * @return View
     */
    public function mentes(Request $request)
    {
        return view('mentes',
            [
                'adatok' => $request->post()
            ]
        );
    }

}