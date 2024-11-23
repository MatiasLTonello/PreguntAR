<?php
session_start();
include_once("configuration/Configuration.php");
$configuration = new Configuration();
$router = $configuration->getRouter();

$page = $_GET["page"] ?? null;
$action = $_GET["action"] ?? null;

// Validación de autenticación
if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
    if ($page !== 'login' && $page !== 'register') {
        header('Location: /login');
        exit();
    }
}

if (strpos($page, 'editor') === 0 && (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 2) || strpos($page, 'partida') === 0 && (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] == 2)) {
    header('Location: /home');
    exit();
}

if (strpos($page, 'admin') === 0 && (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) || strpos($page, 'partida') === 0 && (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] == 1)) {
    header('Location: /home');
    exit();
}

// Ruta principal
$router->route($page, $action);

