<?php

class EditorController
{
    private $editorModel;
    private $renderer;


    public function __construct($editorModel, $renderer)
    {
        $this->editorModel = $editorModel;
        $this->renderer = $renderer;
    }


    public function list()
    {
        $listaDePreguntas = $this->editorModel->listaDePreguntas();
        foreach ($listaDePreguntas as &$pregunta) {
            $pregunta['mostrarAprobar'] = in_array($pregunta['estado'], ['sugerida', 'desaprobada', 'reportada']);
        }
        $data = array("preguntas" => $listaDePreguntas);
        $this->renderer->show('editor', $data);
    }

    public function aprobar()
    {
        $idPregunta = $_GET["id_pregunta"];
        $this->editorModel->aprobarPregunta($idPregunta);
        header("Location: /editor");
    }


    public function eliminar()
    {
        $idPregunta = $_GET["id_pregunta"];

        $this->editorModel->eliminarRespuestasDePregunta($idPregunta);
        $this->editorModel->eliminarPreguntaById($idPregunta);

        header("Location: /editor");
    }

    public function editar()
    {
        $idPregunta = $_POST["idPregunta"];
        $pregunta = $_POST["pregunta"];
        $categoria = $_POST["categoria"];
        $respuestas = $_POST["respuesta"];
        $idsRespuestas = $_POST["idRespuesta"];
        $esCorrecta = $_POST["escorrecto"];

        // Editar la pregunta primero
        $this->editorModel->editarPregunta($idPregunta, $pregunta, $categoria);

        // Editar cada respuesta
        for ($i = 0; $i < count($idsRespuestas); $i++) {
            $idRespuesta = $idsRespuestas[$i];
            $respuesta = $respuestas[$i];
            $esCorrectaValor = isset($esCorrecta[$i]) ? $esCorrecta[$i] : 0; // Asume 0 si no está definido

            $this->editorModel->editarRespuesta($idRespuesta, $respuesta, $esCorrectaValor);
        }

        // Redireccionar después de la edición
        header("Location: /editor");
    }


    public function preguntaDetalle()
    {
        $idPregunta = $_GET["idPregunta"];
        $pregunta = $this->editorModel->getPreguntaById($idPregunta);
        $respuestas = $this->editorModel->getRespuestasByIdDePregunta($idPregunta);
        $categorias = $this->editorModel->getCategorias();

        // Marcar la categoría actual como seleccionada
        foreach ($categorias as &$categoria) {
            $categoria['is_selected'] = $categoria['id'] == $pregunta[0]['categoria_id'];
        }

        $data = [
            "pregunta" => $pregunta,
            "respuestas" => $respuestas,
            "categorias" => $categorias
        ];

        $this->renderer->show('pregunta', $data);
    }

    public function filtrar()
    {
        $filtro = $_GET['filtro'];

        switch ($filtro) {
            case 'aprobada':
                $preguntas = $this->editorModel->getPreguntasPorAprobadas();
                break;
            case 'reportada':
                $preguntas = $this->editorModel->getPreguntasPorReportadas();
                break;
            case 'desaprobada':
                $preguntas = $this->editorModel->getPreguntasPorDesaprobados();
                break;
            case 'sugerida':
                $preguntas = $this->editorModel->getPreguntasPorSugeridas();
                break;
            default:
                $preguntas = $this->editorModel->getPreguntasByIdASC();
                break;
        }
        $response = ['preguntas' => $preguntas];

        header('Content-Type: application/json');
        echo json_encode($response);
    }


}