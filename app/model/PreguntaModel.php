<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function crearPregunta($pregunta, $categoria)
    {
        $sql = "INSERT INTO preguntas(pregunta, categoria, creadaPorUsuario) VALUES('$pregunta','$categoria', true)";
        return $this->database->insert($sql);
    }

    public function validarQueNoHayaDosPreguntasIguales($pregunta)
    {
        $sql = "SELECT * FROM preguntas WHERE pregunta = '$pregunta'";
        return $this->database->query($sql);
    }

    public function getIdPregunta($pregunta)
    {
        $sql = "SELECT idPregunta FROM preguntas WHERE pregunta = '$pregunta'";
        return $this->database->query($sql);
    }

    public function insertarRespuesta($respuesta, $idPregunta)
    {
        $sql = "INSERT INTO respuestas(respuesta, isCorrecta, idPregunta) VALUES('$respuesta', 0 , '$idPregunta')";
        return $this->database->insert($sql);
    }

    public function getLastPreguntaInsertada()
    {
        $query = "SELECT * FROM preguntas WHERE idPregunta = (SELECT MAX(idPregunta) FROM preguntas);";
        return $this->database->query($query);
    }

    public function setearTrue($respuesta, $idPregunta)
    {
        $sql = "UPDATE respuestas SET isCorrecta = 1 WHERE respuesta = '$respuesta' AND idPregunta = $idPregunta";
        return $this->database->insert($sql);
    }

    public function getPreguntaById($id)
    {
        $query = "SELECT * FROM preguntas WHERE id= '$id' ";
        return $this->database->query($query);
    }

}