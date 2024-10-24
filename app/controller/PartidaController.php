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

    // <form id="questionForm">
    //                         <div class="form-check">
    //                             <input class="form-check-input" type="radio" name="answer" id="option1"
    //                                 value={{opciones.0.id}}>
    //                             <label class="form-check-label" for="option1">
    //                                 {{opciones.0.opcion}}
    //                             </label>
    //                         </div>
    //                         <div class="form-check">
    //                             <input class="form-check-input" type="radio" name="answer" id="option2"
    //                                 value={{opciones.1.id}}>
    //                             <label class="form-check-label" for="option2">
    //                                 {{opciones.1.opcion}}
    //                             </label>
    //                         </div>
    //                         <div class="form-check">
    //                             <input class="form-check-input" type="radio" name="answer" id="option3"
    //                                 value={{opciones.2.id}}>
    //                             <label class="form-check-label" for="option3">
    //                                 {{opciones.2.opcion}}
    //                             </label>
    //                         </div>
    //                         <div class="form-check">
    //                             <input class="form-check-input" type="radio" name="answer" id="option4"
    //                                 value={{opciones.3.id}}>
    //                             <label class="form-check-label" for="option4">
    //                                 {{opciones.3.opcion}}
    //                             </label>
    //                         </div>
    //                         <div class="d-grid gap-2 my-4">
    //                             <button type="submit" class="btn btn-primary">Responder</button>
    //                         </div>
    //                     </form>

    public function list()
    {
        // Verifica si 'user' y 'actualUser' están definidas en la sesión
        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $data['user'] = $_SESSION['user'];
        $data['usuarioActual'] = $this->model->getUserById($_SESSION['actualUser']);

        if (isset($_GET['respuesta'])) {
            $opcionCorrecta = $this->model->getOpcionCorrecta($_GET['respuesta']);
            if ($opcionCorrecta) {
                $data['respuesta'] = "¡Correcto!";
                $data['esCorrecto'] = true;
            } else {
                $data['respuesta'] = "¡Incorrecto!";
                $data['esCorrecto'] = false;
            }
        } else {
            $data['respuesta'] = "";
            $data['esCorrecto'] = false;
        }

        $pregunta = $this->model->getPreguntaRandom();
        $categoria = $this->model->getCategoria($pregunta[0]['id_categoria']);
        $opciones = $this->model->getOpciones($pregunta[0]['id']);

        // Mezclar las opciones
        shuffle($opciones);

        $data['pregunta'] = $pregunta[0]['pregunta'];
        $data['categoria'] = $categoria[0]['nombre'];
        $data['opciones'] = $opciones;

        $data['categoriaColor'] = str_replace(' ', '', $categoria[0]['nombre']);



        $this->presenter->show('partida', $data);
    }
}
