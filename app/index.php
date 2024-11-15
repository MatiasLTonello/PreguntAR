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

// Validación de rol para rutas de /editor
if (strpos($page, 'editor') === 0 && (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 2)) {
    header('Location: /home');
    exit();
}

// Ruta principal
$router->route($page, $action);

