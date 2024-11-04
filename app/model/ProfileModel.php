<?php

class ProfileModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }
    public function getUserById($id) {
        $query = "
        SELECT usuarios.*, roles.descripcion AS rol
        FROM usuarios
        JOIN roles ON usuarios.id_rol = roles.id
        WHERE usuarios.id = '$id'
    ";
        return $this->database->query($query);
    }
}