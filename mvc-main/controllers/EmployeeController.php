<?php
namespace Controllers;

require_once 'vendor/autoload.php';


use Request\Request;
use Responses\Response;
use Models\EmployeeRepository;
use Controllers\ApiController;

class EmployeeController extends ApiController {

   private $employeeRepo;
    private $request;

    public function __construct($repo, $request) {
        $this->employeeRepo = $repo; 
        $this->request = $request;
    }

    public function getEmployee() {
        $result = $this->employeeRepo->getAll();
        if ($result) {
            return new Response(200, json_encode($result));
        } else {
            return new Response(500, json_encode(['error' => "Failed to fetch employees"]));
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
    
        $data = $this->request->getBody();

        $created = $this->employeeRepo->create($data);

        if ($created) {
            return new \Responses\Response(200, json_encode(['message' => 'Employee added successfully']));
        } else {
            return new \Responses\Response(500, json_encode(['error' => 'Failed to create employee']));
        }   
    }

        public function updateEmployee($id) {
        $rawInput = file_get_contents('php://input');
        error_log("RAW INPUT: " . $rawInput);
        $data = json_decode($rawInput, true);

    
        if (!$data) {
            error_log("JSON DECODE ERROR: " . json_last_error_msg());
            return new \Responses\Response(400, json_encode(['error' => 'Invalid JSON data']));
        }

        $result = $this->employeeRepo->update($id, $data);

        if ($result) {
            return new \Responses\Response(200, json_encode(['message' => 'Employee updated successfully']));
        } else {
            return new \Responses\Response(500, json_encode(['error' => 'There is no changes in the employee data']));
        }
    }

    public function deleteEmployee($id) {
        
            $result = $this->employeeRepo->delete($id);
            if ($result) {
                return new \Responses\Response(200, json_encode(['message' => 'Employee deleted successfully']));
            } else {
                return new \Responses\Response(500, json_encode(['error' => 'Failed to delete employee']));
            }
    
    }
    // public function getAll() {
    //     $employees = $this->employeeRepo->getAll();
    //     if ($employees) {
    //         return new Response(200, json_encode($employees));
    //     } else {
    //         return new Response(500, json_encode(['error' => 'Failed to fetch employees']));
    //     }
    // }
}