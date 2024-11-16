<?php

class ReportModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getReportes() {
        $sql = "SELECT R.id, P.pregunta AS 'pregunta', R.id_pregunta, R.id_usuario, R.motivo, R.fecha_reporte, R.revisado FROM `reportes` AS R JOIN preguntas AS P ON R.id_pregunta = P.id WHERE 1";

        return $this->database->query($sql);
    }

    public function guardarReporte($idUsuario, $idPregunta, $motivo) {
        $sql = "INSERT INTO reportes (`id`, `id_pregunta`, `id_usuario`, `motivo`, `fecha_reporte`, `revisado`) 
        VALUES (NULL, '$idPregunta', '$idUsuario', '$motivo', current_timestamp(), '0')";

        return $this->database->execute($sql);
    }

    public function marcarComoRevisado($idReporte) {
        $sql = "UPDATE reportes SET revisado = '1' WHERE id = '$idReporte'";

        return $this->database->execute($sql);
    }


}
