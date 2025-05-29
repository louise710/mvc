<?php
namespace Controllers;

require_once 'vendor/autoload.php';


use Request\Request;
use Responses\Response;
use Models\EmployeeRepository;
use Controllers\ApiController;
use Core\View;

class EmployeeController extends ApiController {

   private $employeeRepo;
    private $request;

    public function __construct($repo, $request) {
        $this->employeeRepo = $repo; 
        $this->request = $request;
    }

   public function getEmployee()
{
    $result = $this->employeeRepo->getAll();

    if ($result) {
        return View::render('dashboard', ['employees' => $result]);
    } else {
        return View::render('dashboard', ['error' => 'Error fetching employees.']);
    }
}
public function getEmployeeApi() {
    $result = $this->employeeRepo->getAll();

    if ($result) {
        return new Response(200, json_encode($result));
    } else {
        return new Response(500, json_encode(['error' => 'Failed to fetch employees.']));
    }
}


    public function getEmployeeById($id) {
        $result = $this->employeeRepo->getById($id);
        if (empty($result)) {
            return new Response(404, json_encode(['error' => 'Employee not found']));
        }
        return new Response(200, json_encode($result));
    }

  public function addEmployee() {
    session_start(); 

    $data = $this->request->getBody();
    $created = $this->employeeRepo->create($data);

    if ($created) {
        $_SESSION['success'] = 'Employee added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add employee.';
    }

    
    header('Location: /mvc-main/dashboard');
    exit();
}

public function updateEmployee() {
    session_start();
    $data = $this->request->getBody();
  
    $updated = $this->employeeRepo->update($data['id'], $data);

    if ($updated) {
        $_SESSION['success'] = 'Employee updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update employee because there is no changes';
    }

    header('Location: /mvc-main/dashboard');
    exit();
}


public function deleteEmployee() {
    session_start();
    $data = $this->request->getBody();

    if (!isset($data['id'])) {
        $_SESSION['error'] = 'Invalid request.';
        header('Location: /mvc-main/dashboard');
        exit();
    }

    $deleted = $this->employeeRepo->delete($data['id']);

    if ($deleted) {
        $_SESSION['success'] = 'Employee deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete employee.';
    }

    header('Location: /mvc-main/dashboard');
    exit();
}

 
}