<?php

require 'vendor/autoload.php';

class RegisterModel
{
    private $database;
    private $emailHelper;

    public function __construct($database, $emailHelper) {
        $this->database = $database;
        $this->emailHelper = $emailHelper;
    }

    public function validarContrasenas($password, $confirmPassword)
    {

        if ($password !== $confirmPassword) {
            $data["error"] = "Las contraseñas no coinciden";
        }

        return $data ?? "";
    }

    public function userRegistration($username, $nombreCompleto, $fechaDeNacimiento, $sexo, $password, $ubicacion, $email, $rol, $foto, $fechaDeRegistro, $verify_token) {
        $sql = "INSERT INTO usuarios (nombre_completo, fecha_nacimiento,usuario, email,contraseña,ubicacion, rol, foto, sexo, fecha_registro, verify_token)
            VALUES ('$nombreCompleto', '$fechaDeNacimiento','$username', '$email', '$password', '$ubicacion', '$rol', '$foto', '$sexo','$fechaDeRegistro','$verify_token')";

        $this->emailHelper->enviarEmailDeValidacion($nombreCompleto,$email, $verify_token);

        return $this->database->execute($sql);
    }

    public function consultarTodosLosMailDeUsuarios()
    {
        return $this->database->query("SELECT email FROM usuarios");
    }

    public function consultarTodosLosNombresDeUsuarios()
    {
        return $this->database->query("SELECT usuario FROM usuarios");
    }

    public function estaDuplicado($mail, $username)
    {
        $duplicado = "";

        $todosLosMails = $this->consultarTodosLosMailDeUsuarios();
        $todosLosUsername = $this->consultarTodosLosNombresDeUsuarios();

        foreach ($todosLosMails as $mails) {
            if (isset($mails["email"]) && $mails["email"] == $mail) {
                $duplicado = "email en uso ";
                break;
            }
        }

        foreach ($todosLosUsername as $user) {
            if (isset($user["usuario"]) && $user["usuario"] == $username) {
                $duplicado = $duplicado."usuario en uso!";
                break;
            }
        }

        return $duplicado;
    }

    public function getFechaDeRegistro() {
        $sql = "SELECT CURDATE() AS fechaDeRegistro";
        return $this->database->query($sql);
    }

}