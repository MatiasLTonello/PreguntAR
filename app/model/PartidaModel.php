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

    public function setPartida($idUsuario, $puntaje, $fecha, $terminada)
    {
        $query = "INSERT INTO partidas (id_usuario, puntaje, fecha, terminada) VALUES ('$idUsuario', '$puntaje', '$fecha', '$terminada')";
        return $this->database->execute($query);
    }

    public function getPartidaEnCurso($idUsuario)
    {
        $query = "SELECT * FROM partidas WHERE id_usuario = '$idUsuario' AND terminada = 0";
        return $this->database->query($query);
    }

    public function setPuntaje($idPartida, $puntaje)
    {
        $query = "UPDATE partidas SET puntaje = '$puntaje' WHERE id = '$idPartida'";
        return $this->database->execute($query);
    }

    public function setPartidaTerminada($idPartida)
    {
        $query = "UPDATE partidas SET terminada = 1 WHERE id = '$idPartida'";
        return $this->database->execute($query);
    }

    public function setHistorialPreguntas($idUsuario, $idPregunta, $contestoCorrectamente)
    {
        $query = "INSERT INTO historial_usuarios_preguntas (id_usuario, id_pregunta, contesto_correctamente) VALUES ('$idUsuario', '$idPregunta', '$contestoCorrectamente')";
        return $this->database->execute($query);
    }

    public function getHistorialPreguntas($idUsuario)
    {
        $query = "SELECT hp.id, p.pregunta, hp.contesto_correctamente FROM historial_usuarios_preguntas hp JOIN preguntas p ON hp.id_pregunta = p.id WHERE hp.id_usuario = '$idUsuario'";
        return $this->database->query($query);
    }

    public function getPreguntas()
    {
        $query = "SELECT * FROM preguntas";
        return $this->database->query($query);
    }

    public function getPreguntaRandomSinRepetir($idUsuario)
    {
        $query = "SELECT * FROM preguntas WHERE id NOT IN (SELECT id_pregunta FROM historial_usuarios_preguntas WHERE id_usuario = '$idUsuario') ORDER BY RAND() LIMIT 1";
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

    public function getOpcionCorrecta($opciones)
    {
        foreach ($opciones as $opcion) {
            if ($opcion['es_correcta']) {
                return $opcion;
            }
        }
    }
}
