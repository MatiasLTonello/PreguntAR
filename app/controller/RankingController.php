<?php

class RankingController
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
        $users = $this->model->getRankingByPuntajeDesc();

        $data = array('users' => $users);
        $this->presenter->show('ranking', $data);
    }

    public function filtrar()
    {

        $filtro = $_GET['filtro'];

        switch ($filtro) {
            case 'ascPuntos':
                $users = $this->model->getRankingByPuntajeAsc();
                break;
            case 'descPartidas':
                $users = $this->model->getRankingByPartidasDesc();
                break;
            case 'ascPartidas':
                $users = $this->model->getRankingByPartidasAsc();
                break;
            default:
                $users = $this->model->getRankingByPuntajeDesc();
                break;
        }

        $response = ['users' => $users];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

}