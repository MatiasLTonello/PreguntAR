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
            SELECT usuarios.id, usuarios.nombre_completo, SUM(partidas.puntaje) AS puntaje_total, COUNT(partidas.id) AS partidas_jugadas
            FROM usuarios
            JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
            ORDER BY puntaje_total DESC
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
            SELECT usuarios.id, usuarios.nombre_completo, SUM(partidas.puntaje) AS puntaje_total, COUNT(partidas.id) AS partidas_jugadas
            FROM usuarios
            JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
            ORDER BY puntaje_total ASC
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
            SELECT usuarios.id, usuarios.nombre_completo, COUNT(partidas.id) AS partidas_jugadas, SUM(partidas.puntaje) AS puntaje_total
            FROM usuarios
            JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
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
            SELECT usuarios.id, usuarios.nombre_completo, COUNT(partidas.id) AS partidas_jugadas, SUM(partidas.puntaje) AS puntaje_total
            FROM usuarios
            JOIN partidas ON usuarios.id = partidas.id_usuario
            GROUP BY usuarios.id
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
