<?php

class ProfileModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserById($id)
    {
        $query = "
        SELECT usuarios.*, roles.descripcion AS rol
        FROM usuarios
        JOIN roles ON usuarios.id_rol = roles.id
        WHERE usuarios.id = '$id'
    ";

        return $this->database->query($query);
    }

    public function getUserStats($id)
    {
        $query = "
        SELECT id_usuario, COUNT(*) AS total_partidas, MAX(puntaje) AS puntaje_maximo
        FROM partidas
        WHERE terminada = 1 AND id_usuario = '$id'
        GROUP BY id_usuario
    ";

        return $this->database->query($query);
    }
}
