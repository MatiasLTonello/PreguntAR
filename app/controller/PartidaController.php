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

        if (isset($_POST['respuesta'])) {
            $opciones = $this->model->getOpciones($_GET['pregunta']);
            $opcionCorrecta = $this->model->getOpcionCorrecta($opciones);
            $partida = $this->model->getPartidaEnCurso($_SESSION['actualUser']);

            if ($_POST['respuesta'] == $opcionCorrecta['id']) {
                $data['respuesta'] = "¡Correcto!";
                $this->model->setHistorialPreguntas($_SESSION['actualUser'], $_GET['pregunta'], 1);
                $this->model->setPuntaje($partida[0]['id'], $partida[0]['puntaje'] + 1);
                $this->model->updatePreguntaCorrecta($_GET['pregunta']);
            } else {
                $this->model->setHistorialPreguntas($_SESSION['actualUser'], $_GET['pregunta'], 0);
                $this->model->setPartidaTerminada($partida[0]['id']);
                header('Location: /partida/respuestaIncorrecta?pregunta=' . $_GET['pregunta'] . '&puntaje=' . $partida[0]['puntaje']);
                exit();
            }
        }

        if ($this->model->getPartidaEnCurso($_SESSION['actualUser'])) {
            $partida = $this->model->getPartidaEnCurso($_SESSION['actualUser']);
            $data['puntaje'] = $partida[0]['puntaje'];
        } else {
            $this->model->setPartida($_SESSION['actualUser'], 0, date('Y-m-d'), 0);
            $data['puntaje'] = 0;
        }

        $pregunta = $this->model->getPreguntaRandomSinRepetir($_SESSION['actualUser']);

        if (empty($pregunta)) {
            header('Location: /partida/fin');
            exit();
        }

        $categoria = $this->model->getCategoria($pregunta['id_categoria']);
        $opciones = $this->model->getOpciones($pregunta['id']);

        // Mezclar las opciones
        shuffle($opciones);

        $data['pregunta'] = $pregunta['pregunta'];
        $data['preguntaId'] = $pregunta['id'];
        $data['categoria'] = $categoria[0]['nombre'];
        $data['opciones'] = $opciones;

        $data['categoriaColor'] = str_replace(' ', '', $categoria[0]['nombre']);

        $this->presenter->show('partida', $data);
    }

    public function respuestaIncorrecta()
    {
        // Verifica si 'user' y 'actualUser' están definidas en la sesión
        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $data['user'] = $_SESSION['user'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);
        
        $this->model->updateUserNivel($_SESSION['actualUser']);

        $opciones = $this->model->getOpciones($_GET['pregunta']);
        $opcionCorrecta = $this->model->getOpcionCorrecta($opciones);

        $data['opcionCorrecta'] = $opcionCorrecta['opcion'];
        $data['idPregunta'] = $_GET['pregunta'];
        $data['puntaje'] = $_GET['puntaje'];

        $this->presenter->show('respuestaIncorrecta', $data);
    }

    public function fin()
    {
        // Verifica si 'user' y 'actualUser' están definidas en la sesión
        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $data['user'] = $_SESSION['user'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);

        $data['historial'] = $this->model->getHistorialPreguntas($_SESSION['actualUser']);
        $this->presenter->show('fin', $data);
    }

    public function reportar()
    {
        $idPreguntaReportada = $_GET['idPregunta'];
        $this->model->marcarPreguntaComoReportada($idPreguntaReportada);
        $_SESSION['mensaje'] = 'Pregunta reportada correctamente';
        header('Location: /');
        exit;
    }

}
