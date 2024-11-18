<?php

class AdminModel

{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM usuarios WHERE id = '$id'";
        return $this->database->query($query);
    }

    public function getRolById($id_rol)
    {
        switch ($id_rol) {
            case 1:
                return 'admin';
            case 2:
                return 'editor';
            case 3:
                return 'normal';
            default:
                return 'desconocido';
        }
    }

    public function getCantidaUsuarios()
    {
        $query = "SELECT COUNT(*) AS cantidad_usuarios FROM usuarios";
        $result = $this->database->query($query);
        return $result[0]['cantidad_usuarios'];
    }

    public function getCantidadPartidas()
    {
        $query = "SELECT COUNT(*) AS cantidad_partidas FROM partidas";
        $result = $this->database->query($query);
        return $result[0]['cantidad_partidas'];
    }

    public function getCantidaPreuntas()
    {
        $query = "SELECT COUNT(*) AS cantidad_preguntas FROM preguntas";
        $result = $this->database->query($query);
        return $result[0]['cantidad_preguntas'];
    }

    public function getCantidaNuevosUsuarios()
    {
        $query = "SELECT COUNT(*) AS cantidad_nuevos_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $result = $this->database->query($query);
        return $result[0]['cantidad_nuevos_usuarios'];
    }

    public function getUsuariosPorPais()
    {
        $query = "SELECT ubicacion, COUNT(*) AS cantidad_usuarios FROM usuarios GROUP BY ubicacion";
        return $this->database->query($query);
    }

    public function getUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS cantidad_usuarios FROM usuarios GROUP BY sexo";
        return $this->database->query($query);
    }

    public function getUsuariosPorEdad()
    {
        $query = "SELECT YEAR(CURDATE()) - YEAR(fecha_nacimiento) AS edad, COUNT(*) AS cantidad_usuarios FROM usuarios GROUP BY edad";
        return $this->database->query($query);
    }

    public function getPorcentajeRespondidasCorrectamentePorDia()
    {
        $query = "SELECT DATE(h.fecha) AS fecha, ROUND((COUNT(CASE WHEN h.contesto_correctamente = TRUE THEN 1 END) * 100.0) / COUNT(*), 2) AS porcentaje_correctas FROM historial_usuarios_preguntas h GROUP BY DATE(h.fecha) ORDER BY fecha;";
        return $this->database->query($query);
    }

    public function getDatosPorDia()
    {
        $query = "SELECT fechas.fecha AS fecha,
            IFNULL(COUNT(DISTINCT u.id), 0) AS cantidad_nuevos_usuarios,
            IFNULL(COUNT(DISTINCT p.id), 0) AS cantidad_partidas_jugadas,
            IFNULL(COUNT(DISTINCT pr.id), 0) AS cantidad_preguntas_creadas,
            IFNULL(
                ROUND(
                    (COUNT(CASE WHEN h.contesto_correctamente = TRUE THEN 1 END) * 100.0) / NULLIF(COUNT(h.id), 0), 2), 0) 
                    AS porcentaje_respuestas_correctas 
            FROM (SELECT DISTINCT DATE(fecha_registro) AS fecha FROM usuarios
                UNION SELECT DISTINCT DATE(fecha) FROM partidas
                UNION SELECT DISTINCT DATE(fecha_creacion) FROM preguntas
                UNION SELECT DISTINCT DATE(fecha) FROM historial_usuarios_preguntas) fechas
            LEFT JOIN usuarios u ON DATE(u.fecha_registro) = fechas.fecha
            LEFT JOIN partidas p ON DATE(p.fecha) = fechas.fecha
            LEFT JOIN preguntas pr ON DATE(pr.fecha_creacion) = fechas.fecha
            LEFT JOIN historial_usuarios_preguntas h ON DATE(h.fecha) = fechas.fecha
            GROUP BY fechas.fecha
            ORDER BY fechas.fecha;";

        return $this->database->query($query);
    }

    public function getDatosPorFiltro($filtro)
    {
        switch ($filtro) {
            case '1':
                $intervalo = 'DAY';
                break;
            case '2':
                $intervalo = 'WEEK';
                break;
            case '3':
                $intervalo = 'MONTH';
                break;
            case '4':
                $intervalo = 'YEAR';
                break;
            default:
                $intervalo = 'DAY';
                break;
        }

        // Obtener la cantidad de usuarios
        $queryUsuarios = "SELECT COUNT(*) AS cantidad_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo)";
        $resultUsuarios = $this->database->query($queryUsuarios);
        $cantidadUsuarios = $resultUsuarios[0]['cantidad_usuarios'];

        // Obtener la cantidad de partidas
        $queryPartidas = "SELECT COUNT(*) AS cantidad_partidas FROM partidas WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo)";
        $resultPartidas = $this->database->query($queryPartidas);
        $cantidadPartidas = $resultPartidas[0]['cantidad_partidas'];

        // Obtener la cantidad de preguntas
        $queryPreguntas = "SELECT COUNT(*) AS cantidad_preguntas FROM preguntas WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo)";
        $resultPreguntas = $this->database->query($queryPreguntas);
        $cantidadPreguntas = $resultPreguntas[0]['cantidad_preguntas'];

        // Obtener la cantidad de nuevos usuarios
        $queryNuevosUsuarios = "SELECT COUNT(*) AS cantidad_nuevos_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo)";
        $resultNuevosUsuarios = $this->database->query($queryNuevosUsuarios);
        $cantidadNuevosUsuarios = $resultNuevosUsuarios[0]['cantidad_nuevos_usuarios'];

        // Obtener usuarios por país
        $queryUsuariosPorPais = "SELECT ubicacion, COUNT(*) AS cantidad_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo) GROUP BY ubicacion";
        $usuariosPorPais = $this->database->query($queryUsuariosPorPais);

        // Obtener usuarios por sexo
        $queryUsuariosPorSexo = "SELECT sexo, COUNT(*) AS cantidad_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo) GROUP BY sexo";
        $usuariosPorSexo = $this->database->query($queryUsuariosPorSexo);

        // Obtener usuarios por edad
        $queryUsuariosPorEdad = "SELECT YEAR(CURDATE()) - YEAR(fecha_nacimiento) AS edad, COUNT(*) AS cantidad_usuarios FROM usuarios WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo) GROUP BY edad";
        $usuariosPorEdad = $this->database->query($queryUsuariosPorEdad);

        // Obtener porcentaje de preguntas respondidas correctamente por día
        $queryPorcentajeCorrectas = "SELECT DATE(fecha) AS fecha, 
        ROUND((SUM(CASE WHEN contesto_correctamente = TRUE THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS porcentaje_correctas 
        FROM historial_usuarios_preguntas 
        WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 1 $intervalo) 
        GROUP BY DATE(fecha)";
        $porcentajeCorrectas = $this->database->query($queryPorcentajeCorrectas);

        return [
            'cantidadUsuarios' => $cantidadUsuarios,
            'cantidadPartidas' => $cantidadPartidas,
            'cantidadPreguntas' => $cantidadPreguntas,
            'cantidadNuevosUsuarios' => $cantidadNuevosUsuarios,
            'usuariosPorPais' => $usuariosPorPais,
            'usuariosPorSexo' => $usuariosPorSexo,
            'usuariosPorEdad' => $usuariosPorEdad,
            'porcentajeRespondidasCorrectamentePorDia' => $porcentajeCorrectas
        ];
    }
}
