<?php

require_once __DIR__ . '/init.php';

use Classes\DBORM;
use Request\Request;
use Routes\Router;
use Routes\RouteMatcher;

use Models\StudentRepository;
use Models\UserRepository;

use Controllers\StudentController;
use Controllers\UserController;

$db = new DBORM('mysql:host=localhost;dbname=data', 'root', '');

$StudentRepository = new StudentRepository($db);
$UserRepository = new UserRepository($db);

$request = new Request();

$StudentController = new StudentController($StudentRepository, $request);
$userController = new UserController($UserRepository, $request);

$routes = include __DIR__ . '/routes/routes.php';

$router = new Router($request, new RouteMatcher());

foreach ($routes as $route) {
    $router->addRoute($route['method'], $route['path'], $route['handler']);
}

$response = $router->dispatch();

if ($response instanceof \Responses\Response) {
    http_response_code($response->getStatusCode());
    header('Content-Type: application/json');
    echo $response->getBody();
} elseif (is_string($response)) {
    echo $response;
}
