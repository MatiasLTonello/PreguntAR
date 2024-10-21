<?php

class RegisterController
{
    private $registerModel;
    private $presenter;

    public function __construct($registerModel, $presenter)
    {
        $this->registerModel = $registerModel;
        $this->presenter = $presenter;
    }

    public function list()
    {
        $this->presenter->show('register');
    }

    public function userRegistration()
    {

        $nombreCompleto = $_POST['nombre-completo'];
        $fechaDeNacimiento = $_POST['fecha-nacimiento'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        $hashPassword = md5($password);
        $fechaDeRegistro = $this->registerModel->getFechaDeRegistro()[0]["fechaDeRegistro"];
        $ubicacion = $_POST['ubicacion'];
        $foto = $_POST['foto'];
        $sexo = $_POST['sexo'];

        $rol = 'jugador';
        $verify_token = md5(rand());
        $duplicado = $this->registerModel->estaDuplicado($email, $username);
        $contraseñasInvalidas = $this->registerModel->validarContrasenas($password, $confirmPassword);

        if ($contraseñasInvalidas) {
            $this->presenter->show('register', $contraseñasInvalidas);
            exit;
        }

        if ($duplicado) {
            $data["duplicado"] = $duplicado;
            $this->presenter->show('register', $data);
            exit;
        }

        $method = $this->registerModel->userRegistration(
            $username,
            $nombreCompleto,
            $fechaDeNacimiento,
            $sexo,
            $hashPassword,
            $confirmPassword,
            $ubicacion,
            $email,
            $foto,
            $rol,
            $fechaDeRegistro,
            $verify_token);

        if ($method) {
            $data['statusEmail'] = 'Registro Exitoso! Verifique su mail';
            $this->presenter->show('register', $data);
        }
    }
}
