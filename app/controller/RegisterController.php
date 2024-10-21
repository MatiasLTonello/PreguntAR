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
        $foto = null;
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0 && $_FILES["foto"]["size"] > 0 )
        {
            $foto = $_FILES["foto"]["name"];
            move_uploaded_file($_FILES["foto"]["tmp_name"], './public/img/' .$foto);
        }


        $sexo = $_POST['sexo'];

        $rol = 'jugador';
        $verify_token = md5(rand());
        $duplicado = $this->registerModel->estaDuplicado($email, $username);
        $contraseniasInvalidas = $this->registerModel->validarContrasenas($password, $confirmPassword);

        $errores = [];

        if (empty($nombreCompleto)) {
            $errores['nombre-completo'] = 'El nombre completo es obligatorio.';
        }
        if (empty($fechaDeNacimiento)) {
            $errores['fecha-nacimiento'] = 'La fecha de nacimiento es obligatoria.';
        }
        if (empty($username)) {
            $errores['username'] = 'El usuario es obligatorio.';
        }
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio.';
        }
        if (empty($password)) {
            $errores['password'] = 'La contraseña es obligatoria.';
        }
        if (empty($confirmPassword)) {
            $errores['confirm-password'] = 'Debes repetir la contraseña.';
        }

        if($foto == null){
            $foto = 'default-profile.png';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato de email no es válido.';
        }

        // Si hay errores, devolverlos a la vista
        if (!empty($errores)) {
            $this->presenter->show('register', ['errores' => $errores]);
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
            exit;
        }
    }
}
