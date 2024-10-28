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

        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $data['user'] = $_SESSION['user'];
        $data['idUser'] = $_SESSION['actualUser'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);

        $this->presenter->show('home', $data);
    }
}