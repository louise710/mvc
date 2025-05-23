<?php
namespace Controllers;

use Models\DataRepositoryInterface;
use Request\RequestInterface;
use Responses\Response;

class StudentController {
    private $studentRepository;
    private $request;

    public function __construct(DataRepositoryInterface $studentRepository, RequestInterface $request) {
        $this->studentRepository = $studentRepository;
        $this->request = $request;
    }

    public function getStudentById($id) {
        $student = $this->studentRepository->getById($id);
        if (empty($student)) {
            return new Response(404, json_encode(['error' => 'Student not found']));
        }
        return new Response(200, json_encode($student[0]));
    }

    public function createStudent() {
        $data = $this->request->getBody();
        $this->studentRepository->create($data);
        return new Response(201, json_encode(['message' => 'Student created']));
    }

    public function updateStudent($id) {
        $data = $this->request->getBody();
        $this->studentRepository->update($id, $data);
        return new Response(200, json_encode(['message' => 'Student updated']));
    }

    public function deleteStudent($id) {
        $this->studentRepository->delete($id);
        return new Response(204, '');
    }
}
