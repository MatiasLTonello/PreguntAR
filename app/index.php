<?php
session_start();
include_once("configuration/Configuration.php");
$configuration = new Configuration();
$router = $configuration->getRouter();

$page = $_GET["page"] ?? null;
$action = $_GET["action"] ?? null;

$router->route($page, $action);