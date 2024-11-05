<?php
session_start();
include_once("configuration/Configuration.php");
$configuration = new Configuration();
$router = $configuration->getRouter();

$page = $_GET["page"] ?? null;
$action = $_GET["action"] ?? null;


if (!isset($_SESSION['user']) || !isset($_SESSION['actualUser'])) {
    if ($page !== 'login' && $page !== 'register') {
        header('Location: /login');
        exit();
    }
}

$router->route($page, $action);
