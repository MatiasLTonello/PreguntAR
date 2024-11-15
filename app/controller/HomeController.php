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
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
        unset($_SESSION['mensaje']);
        $data['mensaje'] = $mensaje;
        $data['user'] = $_SESSION['user'];
        $data['idUser'] = $_SESSION['actualUser'];
        $usuarioActual = $this->model->getUserById($_SESSION['actualUser']);
        $rol = $this->model->getRolById($usuarioActual[0]['id_rol']);
        $data['isAdmin'] = $rol === 'admin';
        $data['isEditor'] = $rol === 'editor';

        $this->presenter->show('home', $data);

    }
}