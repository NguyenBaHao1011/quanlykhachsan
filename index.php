<?php
session_start();

$config = require 'config/database.php';
$routes = require 'config/routes.php';

$db = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']}",
    $config['user'],
    $config['pass']
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$route = $_GET['route'] ?? 'login';

if (!isset($routes[$route])) {
    die("404 Not Found");
}

[$controllerName, $method] = $routes[$route];

require "models/User.php";
require "controllers/$controllerName.php";

$controller = new $controllerName($db);
$controller->$method();
