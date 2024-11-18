<?php

class AdminController
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

        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $data['user'] = $_SESSION['user'];
        $data['idUser'] = $_SESSION['actualUser'];
        $usuarioActual = $this->model->getUserById($_SESSION['actualUser']);
        $rol = $this->model->getRolById($usuarioActual[0]['id_rol']);
        $data['isAdmin'] = $rol === 'admin';

        if (!$data['isAdmin']) {
            header('Location: /');
            exit();
        }

        $data['cantidadUsuarios'] = $this->model->getCantidaUsuarios();
        $data['cantidadPartidas'] = $this->model->getCantidadPartidas();
        $data['cantidadPreguntas'] = $this->model->getCantidaPreuntas();
        $data['cantidadNuevosUsuarios'] = $this->model->getCantidaNuevosUsuarios();

        $usuariosPorPaisQuery = $this->model->getUsuariosPorPais();
        $paises = [];
        $usuariosPorPais = [];

        foreach ($usuariosPorPaisQuery as $usuarioPorPais) {
            $paises[] = $usuarioPorPais['ubicacion'];
            $usuariosPorPais[] = $usuarioPorPais['cantidad_usuarios'];
        }

        $data['paises'] = json_encode($paises);
        $data['usuariosPorPais'] = json_encode($usuariosPorPais);

        $usuariosPorSexoQuery = $this->model->getUsuariosPorSexo();
        $sexos = [];
        $usuariosPorSexo = [];

        foreach ($usuariosPorSexoQuery as $usuarioPorSexo) {
            $sexos[] = $usuarioPorSexo['sexo'];
            $usuariosPorSexo[] = $usuarioPorSexo['cantidad_usuarios'];
        }

        $data['sexos'] = json_encode($sexos);
        $data['usuariosPorSexo'] = json_encode($usuariosPorSexo);

        $usuariosPorEdadQuery = $this->model->getUsuariosPorEdad();
        $usuariosPorEdad = [0, 0, 0];

        foreach ($usuariosPorEdadQuery as $usuarioPorEdad) {
            $edad = $usuarioPorEdad['edad'];
            if ($edad < 18) {
                $usuariosPorEdad[0] += $usuarioPorEdad['cantidad_usuarios'];
            } else if ($edad >= 18 && $edad < 65) {
                $usuariosPorEdad[2] += $usuarioPorEdad['cantidad_usuarios'];
            } else {
                $usuariosPorEdad[1] += $usuarioPorEdad['cantidad_usuarios'];
            }
        }

        $data['usuariosPorEdad'] = json_encode($usuariosPorEdad);

        $porcentajeRespondidasCorrectamentePorDia = $this->model->getPorcentajeRespondidasCorrectamentePorDia();
        $data['porcentajeRespondidasCorrectamentePorDia'] = json_encode($porcentajeRespondidasCorrectamentePorDia);

        $data["datosPorDia"] = $this->model->getDatosPorDia();

        $this->presenter->show('admin', $data);
    }

    public function filtrar()
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
            header('Location: /login');
            exit();
        }

        $filtro = $_GET['filtro'] ?? '1'; // Obtener el filtro de la solicitud GET
        $data = $this->model->getDatosPorFiltro($filtro);

        echo json_encode($data);
    }
}
