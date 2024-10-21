<?php

class HomeController
{

    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function list()
    {
        if (isset($_SESSION['user'])) {
            $data['user'] = $_SESSION['user'];
        }
        $data['usuarioActual'] = $this -> model -> getUserById($_SESSION['actualUser']);
        $this->presenter->show('home', $data);
    }
}