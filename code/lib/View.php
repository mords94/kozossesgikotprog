<?php
namespace Library;


class View
{

    private $view;

    private $viewBag;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function inc($all = true)
    {
        $viewFile = 'view/'.$this->view.'.php';
        if(!file_exists($viewFile)) {
            throw new Exception('Nem letezik a view file: '. $this->view . '.php');
        }

        if($all) include 'view/inc/header.php';
        extract(ViewBag::get()->all());
        include $viewFile;
        if($all) include 'view/inc/footer.php';
    }

}