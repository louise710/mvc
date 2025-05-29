<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/init.php';
require_once __DIR__ . '/routes/routes.php';  // <<<<< add this line

use Classes\DBORM;
use Request\Request;
use Routes\Router;
use Routes\RouteMatcher;

use Models\StudentRepository;
use Models\UserRepository;
use Models\EmployeeRepository;

use Controllers\StudentController;
use Controllers\UserController;
use Controllers\EmployeeController;

$db = new DBORM('mysql:host=localhost;dbname=db', 'root', '');

$StudentRepository = new StudentRepository($db);
$UserRepository = new UserRepository($db);
$EmployeeRepository = new EmployeeRepository($db);

$request = new Request();

$StudentController = new StudentController($StudentRepository, $request);
$userController = new UserController($UserRepository, $request);
$employeeController = new EmployeeController($EmployeeRepository, $request);

$routes = \Routes\getRoutes($StudentController, $userController,$employeeController);

$router = new Router($request, new RouteMatcher());

foreach ($routes as $route) {
    $router->addRoute($route['method'], $route['path'], $route['handler']);
}

$response = $router->dispatch();

if (is_string($response)) {
    header('Content-Type: text/html; charset=utf-8');
    echo $response;
} elseif ($response instanceof \Responses\Response) {
    http_response_code($response->getStatusCode());
    header('Content-Type: application/json');
    echo $response->getBody();
} else {
    // fallback, just output
    echo $response;
}
