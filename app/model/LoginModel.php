<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUser($username, $password)
    {
        $query = "SELECT * FROM usuarios WHERE usuario = '$username' AND contraseña = '$password'";
        return $this->database->query($query);
    }

    public function setUserVerified($token) {
        $query =  "UPDATE usuarios SET esta_verificado = 'true' WHERE verify_token = '$token'";
        return $this->database->execute($query);
    }

    public function validate($user, $pass)
    {
        $sql = "SELECT 1 
                FROM usuarios
                WHERE usuario = '" . $user. "' 
                AND contraseña = '" . $pass . "'";

        $usuario = $this->database->query($sql);

        return sizeof($usuario) == 1;
    }

}