<?php

class PartidaModel

{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM usuarios WHERE id = '$id'";
        return $this->database->query($query);
    }

    public function getPreguntas()
    {
        $query = "SELECT * FROM preguntas";
        return $this->database->query($query);
    }

    public function getPreguntaRandom()
    {
        $query = "SELECT * FROM preguntas ORDER BY RAND() LIMIT 1";
        return $this->database->query($query);
    }

    public function getCategoria($idCategoria)
    {
        $query = "SELECT * FROM categorias WHERE id = '$idCategoria'";
        return $this->database->query($query);
    }

    public function getOpciones($idPregunta)
    {
        $query = "SELECT * FROM opciones WHERE id_pregunta = '$idPregunta'";
        return $this->database->query($query);
    }

    public function getOpcionCorrecta($idOpcion)
    {
        $query = "SELECT * FROM opciones WHERE id = '$idOpcion' AND es_correcta = 1";
        return $this->database->query($query);
    }
}
