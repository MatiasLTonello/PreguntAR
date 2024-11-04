<?php

class ProfileController
{
    private $model;
    private $presenter;


    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }
    public function list()
    {
        /*if (isset($_SESSION['user'])) {
            $data['user'] = $_SESSION['user'];
        }

        $data['actualUser'] = $this->model->getUserById($_SESSION['actualUser'])[0];

        $this->presenter->show('profile', $data['actualUser']);*/
        {
            if (empty($_GET['idUsuario'])) {
                $idUser = $_SESSION['user'];
                $esEditor = false;
                $esAdmin = false;
                $data['miUser'] = $this->model->getUserById($_SESSION['actualUser'])[0];
                if($data['miUser']['rol'] === 'editor') {
                    $esEditor = true;
                }
                if($data['miUser']['rol'] === 'admin') {
                    $esAdmin = true;
                }
            } else {
                $data["otroPerfil"] = $this->model->getUserById($_GET['idUsuario']);
            }
            $this->presenter->show('profile', $data);
            exit();

        }
    }
}



