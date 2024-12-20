<?php

class LoginController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function login()
    {
        if (!isset($_GET['token'])) exit();

        $this->model->setUserVerified($_GET['token']);
    }

    public function ingresarlogin()
    {

        $username = $_POST['usuario'];
        $password = $_POST['contrasenia'];

        $hashPassword = md5($password);

        $usuario = $this->model->getUser($username, $hashPassword);

        $idUsuario = $usuario[0]['id'] ?? "";

        if (empty($usuario)) {

            $data['error'] = "El username o contraseña son incorrectos";

            $this->presenter->show('login', $data);

            exit();
        }

        $_SESSION['actualUser'] = $idUsuario;

        $_SESSION['user'] = $username;
        
        $_SESSION['id_rol'] = $usuario[0]['id_rol'];

        header('location: /home');

    }

    public function list()
    {
        $this->presenter->show("login");
    }
}
