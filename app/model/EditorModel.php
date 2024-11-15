<?php

class EditorModel

{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function listaDePreguntas()
    {
        $query = "SELECT * FROM preguntas WHERE esta_eliminada = 0";
        return $this->database->query($query);
    }

    public function getCategorias()
    {
        $query = "SELECT * FROM categorias";
        return $this->database->query($query);

    }

    public function aprobarPregunta($idPregunta)
    {
        $query = "UPDATE preguntas SET estado='aprobada' WHERE id='$idPregunta'";
        return $this->database->execute($query);
    }


    public function eliminarRespuestasDePregunta($idPregunta)
    {

        $query = "DELETE FROM opciones WHERE id = '$idPregunta'";
        $this->database->execute($query);
    }

    public function eliminarPreguntaById($idPregunta)
    {
        $query = "UPDATE preguntas SET esta_eliminada = 1, estado= 'eliminada' WHERE id = '$idPregunta'";
        $this->database->execute($query);
    }

    public function editarPregunta($idPregunta, $pregunta, $categoria)
    {
        $query = "UPDATE preguntas SET pregunta='$pregunta', id_categoria='$categoria' WHERE id='$idPregunta'";
        return $this->database->execute($query);
    }

    public function editarRespuesta($idRespuesta, $respuesta, $esCorrecta)
    {
        $query = "UPDATE opciones SET opcion='$respuesta', es_correcta='$esCorrecta' WHERE id ='$idRespuesta'";
        return $this->database->execute($query);
    }


    public function getPreguntasPorAprobadas()
    {
        $query = "SELECT * FROM preguntas where estado='aprobada'";
        $preguntas = $this->database->query($query);

        return $preguntas;
    }

    public function getPreguntasPorReportadas()
    {
        $query = "SELECT * FROM preguntas where estado='reportada'";
        $preguntas = $this->database->query($query);

        return $preguntas;
    }

    public function getPreguntasPorSugeridas()
    {
        $query = "SELECT * FROM preguntas where estado='sugerida'";
        $preguntas = $this->database->query($query);

        return $preguntas;
    }

    public function getPreguntasPorDesaprobados()
    {
        $query = "SELECT * FROM preguntas where estado='desaprobada'";
        $preguntas = $this->database->query($query);

        return $preguntas;
    }

    public function getPreguntasByIdASC()
    {
        $query = "SELECT * FROM preguntas where id ASC";
        $preguntas = $this->database->query($query);

        return $preguntas;
    }


    public function getPreguntaById($id)
    {
        $query = "
        SELECT 
            p.id, 
            p.pregunta, 
            p.estado, 
            c.nombre AS categoria,
            c.id AS categoria_id,
            c.color AS categoria_color
        FROM 
            preguntas p
        JOIN 
            categorias c ON p.id_categoria = c.id
        WHERE 
            p.id = '$id'
    ";

        return $this->database->query($query);
    }

    public function getRespuestasByIdDePregunta($idPregunta)
    {
        $query = "SELECT * FROM opciones WHERE id_pregunta= '$idPregunta' ";
        return $this->database->query($query);
    }

    public function getCategoriaByIdPregunta($idPregunta) {
        $query = "SELECT categoria FROM preguntas WHERE id = '$idPregunta' ";
        return $this->database->query($query);
    }

}