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
        $this->presenter->show('login');
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $this->model->setUserVerified($token);
        }
    }

    public function ingresarlogin()
    {

        $username = $_POST['usuario'];
        $password = $_POST['contrasenia'];
        $hashPassword = md5($password);
        $usuario = $this->model->getUser($username, $hashPassword);
        $idUsuario = $usuario[0]['id'] ?? "";
        $usuarioVerificado = $usuario[0]['esta_verificado'] ?? "";


        if (!empty($usuario)) {
            $_SESSION['actualUser'] = $idUsuario;
            $_SESSION['user'] = $username;
            if($usuario[0]['rol'] == 'editor'){
                $_SESSION['esEditor'] = true;
            }
            header('location: /home');
        } else {
            $data['error'] = "El username o contraseña son incorrectos";
            $this->presenter->show('login', $data);
        }
    }
    public function list()
    {
        $this->presenter->show("login");
    }

}