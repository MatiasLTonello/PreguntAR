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
        $sql = "INSERT INTO preguntas(pregunta, id_categoria, apariciones, correctas, estado) VALUES('$pregunta', '$categoria', 0, 0, 'sugerida')";
        return $this->database->execute($sql);
    }

    public function validarQueNoHayaDosPreguntasIguales($pregunta)
    {
        $sql = "SELECT * FROM preguntas WHERE pregunta = '$pregunta'";
        return $this->database->query($sql);
    }

    public function getIdPregunta($pregunta)
    {
        $sql = "SELECT id FROM preguntas WHERE pregunta = '$pregunta'";
        return $this->database->query($sql);
    }

    public function insertarRespuesta($respuesta, $idPregunta)
    {
        $sql = "INSERT INTO opciones(opcion, es_correcta, id_pregunta) VALUES('$respuesta', 0 , '$idPregunta')";
        return $this->database->execute($sql);
    }

    public function getLastPreguntaInsertada()
    {
        $query = "SELECT * FROM preguntas WHERE id = (SELECT MAX(id) FROM preguntas);";
        return $this->database->query($query);
    }

    public function setearTrue($respuesta, $idPregunta)
    {
        $sql = "UPDATE opciones SET es_correcta = 1 WHERE opcion = '$respuesta' AND id_pregunta = $idPregunta";
        return $this->database->execute($sql);
    }

    public function getCategorias()
    {
        $query = "SELECT * FROM categorias";
        return $this->database->query($query);

    }

    public function getPreguntaById($id)
    {
        $query = "SELECT * FROM preguntas WHERE id= '$id' ";
        return $this->database->query($query);
    }

}