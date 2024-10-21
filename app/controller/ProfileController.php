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
        if (isset($_SESSION['user'])) {
            $data['user'] = $_SESSION['user'];
        }

        $data['actualUser'] = $this->model->getUserById($_SESSION['actualUser'])[0];

        $this->presenter->show('profile', $data['actualUser']);
    }
}



