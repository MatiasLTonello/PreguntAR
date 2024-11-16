<?php

class EditorController
{
    private $editorModel;
    private $reportesModel;
    private $renderer;

    public function __construct($editorModel, $reportesModel, $renderer)
    {
        $this->editorModel = $editorModel;
        $this->reportesModel = $reportesModel;
        $this->renderer = $renderer;
    }

    public function list()
    {

        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;

        $listaDePreguntas = $this->editorModel->getPreguntasPorEstado($filtro);

        foreach ($listaDePreguntas as &$pregunta) {
            $pregunta['mostrarActivar'] = $pregunta['estado'] != 'activa';
            $pregunta['mostrarEliminar'] = $pregunta['estado'] != 'eliminada';
        }

        $data = array("preguntas" => $listaDePreguntas);
        $this->renderer->show('editor', $data);
    }

    public function activar()
    {
        $idPregunta = $_GET["id_pregunta"];
        $this->editorModel->activarPregunta($idPregunta);
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

    public function reportes(){

        $reportes = $this->reportesModel->getReportes();

        $data = array("reportes" => $reportes);

        $this->renderer->show('reportes', $data);
    }

    public function marcarReporteComoLeido(){

        $idReporte = $_GET["idReporte"];

        $this->reportesModel->marcarComoRevisado($idReporte);

        header("Location: /editor/reportes");
    }

}