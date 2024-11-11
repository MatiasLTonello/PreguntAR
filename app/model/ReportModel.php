<?php

class ReportModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function guardarReporte($idUsuario, $idPregunta, $motivo) {
        $sql = "INSERT INTO reportes (`id`, `id_pregunta`, `id_usuario`, `motivo`, `fecha_reporte`, `revisado`) 
        VALUES (NULL, '$idPregunta', '$idUsuario', '$motivo', current_timestamp(), '0')";

        return $this->database->execute($sql);
    }
}
