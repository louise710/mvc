<?php

namespace Routes;

use Responses\Response;

/**
 * Authentication helper function
 */
function authenticate($userController) {
    $headers = getallheaders();
    $authResult = $userController->verifyToken($headers['Authorization'] ?? '');

    if ((is_array($authResult) && isset($authResult["error"])) || (is_object($authResult) && isset($authResult->error))) {
        return new Response(401, json_encode($authResult));
    } else {
        return true;
    }
}

/**
 * Route definitions
 */
function getRoutes($StudentController, $userController,$employeeController) {
    return [
        ['method' => 'GET', 'path' => '/Student', 'handler' => function () use ($StudentController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $StudentController->getAllStudent();
        }],
        ['method' => 'GET', 'path' => '/Student/{id}', 'handler' => function ($id) use ($StudentController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $StudentController->getStudentById($id);
        }],
        ['method' => 'POST', 'path' => '/Student', 'handler' => function () use ($StudentController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $StudentController->createStudent();
        }],
        ['method' => 'PUT', 'path' => '/Student/{id}', 'handler' => function ($id) use ($StudentController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $StudentController->updateStudent($id);
        }],
        ['method' => 'DELETE', 'path' => '/Student/{id}', 'handler' => function ($id) use ($StudentController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $StudentController->deleteStudent($id);
        }],

        ['method' => 'POST', 'path' => '/register', 'handler' => function () use ($userController) {
            return $userController->register();
        }],
        ['method' => 'POST', 'path' => '/login', 'handler' => function () use ($userController) {
            return $userController->login();
        }],
         ['method' => 'POST', 'path' => '/addEmployee', 'handler' => function () use ($employeeController) {
        return $employeeController->addEmployee();
        }],

         ['method' => 'GET', 'path' => '/getEmployee', 'handler' => function () use ($employeeController, $userController) {
            $authCheck = authenticate($userController);
            if ($authCheck !== true) return $authCheck;
            return $employeeController->getEmployee();
        }],

        ['method' => 'POST', 'path' => '/updateEmployee/{id}', 'handler' => function ($id) use ($employeeController) {
        return $employeeController->updateEmployee($id);
        }],

        ['method' => 'DELETE', 'path' => '/deleteEmployee/{id}', 'handler' => function ($id) use ($employeeController) {
            return $employeeController->deleteEmployee($id);
        }],


     




        

        
        ['method' => 'GET', 'path' => '/register', 'handler' => function () {
            include __DIR__ . '/../views/register.php';  
        }],
        ['method' => 'GET', 'path' => '/login', 'handler' => function () {
            include __DIR__ . '/../views/login.php';
        }],
        
        ['method' => 'GET', 'path' => '/dashboard', 'handler' => function () {
            include __DIR__ . '/../views/dashboard.php';
        }],
    ];
}
