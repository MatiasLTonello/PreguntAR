<?php

class ReportController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function list()
    {

    }

    public function reportarPregunta()
    {

        $idUsuario = $_SESSION['actualUser'];
        $idPregunta = $_POST['idPregunta'];
        $motivo = $_POST['motivo'];

        $this->model->guardarReporte($idUsuario ,$idPregunta ,$motivo);

        $this->presenter->show('reportado');

    }

}