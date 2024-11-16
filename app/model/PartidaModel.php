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

    public function getTotalPreguntas($idUsuario)
    {
        $query = "SELECT COUNT(*) AS total_preguntas FROM historial_usuarios_preguntas WHERE id_usuario = '$idUsuario'";
        $result = $this->database->query($query);
        return $result[0]['total_preguntas'];
    }

    public function getPreguntasCorrectas($idUsuario)
    {
        $query = "SELECT COUNT(*) AS preguntas_correctas FROM historial_usuarios_preguntas WHERE id_usuario = '$idUsuario' AND contesto_correctamente = 1";
        $result = $this->database->query($query);
        return $result[0]['preguntas_correctas'];
    }

    public function updateUserNivel($id)
    {

        $totalPreguntas = $this->getTotalPreguntas($id);
        $preguntasCorrectas = $this->getPreguntasCorrectas($id);

        $ratio = $totalPreguntas > 0 ? ($preguntasCorrectas / $totalPreguntas) : 0;

        // Determinar el nivel basado en el ratio
        if ($ratio >= 0.7) {
            $nivel = 3; // Difícil
        } elseif ($ratio >= 0.3) {
            $nivel = 2; // Medio
        } else {
            $nivel = 1; // Fácil
        }
        
        // Actualizar el nivel del usuario
        $query = "UPDATE usuarios SET nivel = '$nivel' WHERE id = '$id'";

        $this->database->execute($query, [$nivel, $id]);
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
        $query = "SELECT * FROM preguntas WHERE estado = 'activa' AND id NOT IN (SELECT id_pregunta FROM historial_usuarios_preguntas WHERE id_usuario = '$idUsuario') ORDER BY RAND() LIMIT 1";
        $pregunta = $this->database->query($query);
        if (empty($pregunta)) {
            return null;
        }
        return $pregunta[0];
    }

    public function setAparicionesPregunta($idPregunta)
    {
        $updateQuery = "UPDATE preguntas SET apariciones = apariciones + 1 WHERE id = '$idPregunta'";
        $this->database->execute($updateQuery);
    }

    public function updatePreguntaCorrecta($idPregunta)
    {

        $updateQuery = "UPDATE preguntas SET correctas = correctas + 1 WHERE id = '$idPregunta'";

        $this->database->execute($updateQuery);
    }

    public function limpiarHistorialPreguntasUsuario($idUsuario)
    {
        $query = "DELETE FROM historial_usuarios_preguntas WHERE id_usuario = '$idUsuario'";
        $this->database->execute($query);
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
