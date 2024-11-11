<?php

class HomeModel

{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM usuarios WHERE id = '$id'";
        return $this->database->query($query);
    }

    public function getRolById($id_rol)
    {
        switch ($id_rol) {
            case 1:
                return 'admin';
            case 2:
                return 'editor';
            case 3:
                return 'normal';
            default:
                return 'desconocido';
        }
    }


}