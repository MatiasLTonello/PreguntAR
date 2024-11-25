<?php
class LogoutController
{
    private $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function logout()
    {
        session_start();
        $preguntaActual = isset($_SESSION['datosPreguntaActual'])? $_SESSION['datosPreguntaActual']: null;
        $partida = $this->model->getPartidaEnCurso($_SESSION['actualUser']);
        if($preguntaActual){
            $this->model->setHistorialPreguntas($_SESSION['actualUser'], $preguntaActual['id'], 0);
        }
        if($partida){
            $this->model->setPartidaTerminada($partida[0]['id']);
            $this->model->updateUserNivel($_SESSION['actualUser']);
        }
        session_destroy();
        header('location: /login');
    }
}