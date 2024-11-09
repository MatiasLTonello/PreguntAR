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

        if (empty($_GET['idUsuario'])) {

            $userId = $_SESSION['actualUser'];

            $miUser = $this->model->getUserById($userId)[0];
            $stats = $this->model->getUserStats($userId);

            $data['miUser'] = $miUser;
            $data['stats'] = $stats;

        } else {

            $data["otroPerfil"] = $this->model->getUserById($_GET['idUsuario']);
        }

        $this->presenter->show('profile', $data);

        exit();
    }
}
