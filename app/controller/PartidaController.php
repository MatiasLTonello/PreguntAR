<?php

class PartidaController
{

    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

   public function list()
    {
        $data['user'] = $_SESSION['user'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        if (isset($_POST['respuesta'])){
            $opciones = $this->model->getOpciones($_GET['pregunta']);
            $opcionCorrecta = $this->model->getOpcionCorrecta($opciones);
            $partida = $this->model->getPartidaEnCurso($_SESSION['actualUser']);

            if ($_POST['respuesta'] == $opcionCorrecta['id']) {
                $this->model->setHistorialPreguntas($_SESSION['actualUser'], $_GET['pregunta'], 1);
                $this->model->setPuntaje($partida[0]['id'], $partida[0]['puntaje'] + 1);
                $this->model->updatePreguntaCorrecta($_GET['pregunta']);
                unset($_SESSION['datosPreguntaActual']);
                header('Location: /partida/respuestaCorrecta');
                exit();
            }else {
                $this->model->setHistorialPreguntas($_SESSION['actualUser'], $_GET['pregunta'], 0);
                $this->model->setPartidaTerminada($partida[0]['id']);
                unset($_SESSION['datosPreguntaActual']);
                header('Location: /partida/respuestaIncorrecta?pregunta=' . $_GET['pregunta'] . '&puntaje=' . $partida[0]['puntaje']);
                exit();
            }
        }else{
            if(isset($_SESSION['datosPreguntaActual'])){
                $pregunta = $_SESSION['datosPreguntaActual']['preguntaActual'];

                $categoria = $this->model->getCategoria($pregunta['id_categoria']);
                $opciones = $this->model->getOpciones($pregunta['id']);

                // Mezclar las opciones
                shuffle($opciones);

                $data['pregunta'] = $pregunta['pregunta'];
                $data['preguntaId'] = $pregunta['id'];
                $data['categoria'] = $categoria[0]['nombre'];
                $data['opciones'] = $opciones;
                $data['timestampInicioPregunta'] = $_SESSION['datosPreguntaActual']['timestampInicioPregunta'];

                $data['categoriaColor'] = str_replace(' ', '', $categoria[0]['nombre']);
                $partidaEnCurso = $this->model->getPartidaEnCurso($_SESSION['actualUser']);
                $data['puntaje'] = $partidaEnCurso[0]['puntaje'];

                $this->presenter->show('partida', $data);

                return;
            }else{
                $partida = $this->model->getPartidaEnCurso($_SESSION['actualUser']);
                if(count($partida) == 0){
                    $this->model->setPartida($_SESSION['actualUser'], 0, date('Y-m-d'), 0);
                }
            }
        }

        $partidaEnCurso = $this->model->getPartidaEnCurso($_SESSION['actualUser']);
        $data['puntaje'] = $partidaEnCurso[0]['puntaje'];

        $pregunta = $this->model->getPreguntaRandomSinRepetir($_SESSION['actualUser']);

        if (empty($pregunta)) {
            $this->model->limpiarHistorialPreguntasUsuario($_SESSION['actualUser']);
            $pregunta = $this->model->getPreguntaRandomSinRepetir($_SESSION['actualUser']);
        }
        $timestampInicioPregunta = time();
        $datosPreguntaActual = [
          "preguntaActual" => $pregunta[0],
            "timestampInicioPregunta" => $timestampInicioPregunta
        ];
        $_SESSION['datosPreguntaActual'] = $datosPreguntaActual;

        $categoria = $this->model->getCategoria($pregunta['id_categoria']);
        $opciones = $this->model->getOpciones($pregunta['id']);

        // Mezclar las opciones
        shuffle($opciones);

        $data['pregunta'] = $pregunta['pregunta'];
        $data['preguntaId'] = $pregunta['id'];
        $data['categoria'] = $categoria[0]['nombre'];
        $data['opciones'] = $opciones;
        $data['timestampInicioPregunta'] = $timestampInicioPregunta;

        $data['categoriaColor'] = str_replace(' ', '', $categoria[0]['nombre']);

        $this->presenter->show('partida', $data);
    }

    public function respuestaIncorrecta()
    {
        $data['user'] = $_SESSION['user'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);
        
        $this->model->updateUserNivel($_SESSION['actualUser']);

        $opciones = $this->model->getOpciones($_GET['pregunta']);
        $opcionCorrecta = $this->model->getOpcionCorrecta($opciones);

        $data['opcionCorrecta'] = $opcionCorrecta['opcion'];
        $data['puntaje'] = $_GET['puntaje'];

        $this->presenter->show('respuestaIncorrecta', $data);
    }

    public function respuestaCorrecta(){
        $this->presenter->show('respuestaCorrecta');
    }
}
