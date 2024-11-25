<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    // Método para obtener el ranking basado en el puntaje total y la cantidad de partidas jugadas
    public function getRankingByPuntajeDesc()
    {
        $query = "
        SELECT usuarios.id, usuarios.nombre_completo, 
               (SELECT MAX(puntaje) 
                FROM partidas 
                WHERE partidas.id_usuario = usuarios.id) AS mayor_puntaje,
               COUNT(partidas.id) AS partidas_jugadas
        FROM usuarios
        LEFT JOIN partidas ON usuarios.id = partidas.id_usuario
        GROUP BY usuarios.id
        HAVING partidas_jugadas > 0
        ORDER BY mayor_puntaje DESC
    ";


        $users = $this->database->query($query);

        $posicion = 1;
        foreach ($users as &$user) {
            $user['posicion'] = $posicion;
            $posicion++;
        }

        return $users;
    }

    public function getRankingByPuntajeAsc()
    {
        $query = "
        SELECT usuarios.id, usuarios.nombre_completo, 
               (SELECT MAX(puntaje) 
                FROM partidas 
                WHERE partidas.id_usuario = usuarios.id) AS mayor_puntaje,
               COUNT(partidas.id) AS partidas_jugadas
        FROM usuarios
        LEFT JOIN partidas ON usuarios.id = partidas.id_usuario
        GROUP BY usuarios.id
        HAVING partidas_jugadas > 0
        ORDER BY mayor_puntaje ASC
    ";
        $users = $this->database->query($query);

        $posicion = 1;
        foreach ($users as &$user) {
            $user['posicion'] = $posicion;
            $posicion++;
        }

        return $users;
    }

    // Método para obtener el ranking basado en el número de partidas jugadas en orden descendente
    public function getRankingByPartidasDesc()
    {
        $query = "
            SELECT usuarios.id, usuarios.nombre_completo, 
                   (SELECT MAX(puntaje) 
                    FROM partidas 
                    WHERE partidas.id_usuario = usuarios.id) AS mayor_puntaje,
                   COUNT(partidas.id) AS partidas_jugadas
            FROM usuarios
            LEFT JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
            HAVING partidas_jugadas > 0
            ORDER BY partidas_jugadas DESC
        ";
        $users = $this->database->query($query);

        $posicion = 1;
        foreach ($users as &$user) {
            $user['posicion'] = $posicion;
            $posicion++;
        }

        return $users;
    }

    // Método para obtener el ranking basado en el número de partidas jugadas en orden ascendente
    public function getRankingByPartidasAsc()
    {
        $query = "
            SELECT usuarios.id, usuarios.nombre_completo, 
                   (SELECT MAX(puntaje) 
                    FROM partidas 
                    WHERE partidas.id_usuario = usuarios.id) AS mayor_puntaje,
                   COUNT(partidas.id) AS partidas_jugadas
            FROM usuarios
            LEFT JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
            HAVING partidas_jugadas > 0
            ORDER BY partidas_jugadas ASC
        ";
        $users = $this->database->query($query);

        $totalUsers = count($users);
        $posicion = 1;
        foreach ($users as &$user) {
            $user['posicion'] = $posicion;
            $posicion++;
        }

        return $users;
    }
}
