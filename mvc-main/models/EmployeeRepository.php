<?php
// <?php
namespace Models;

require_once __DIR__ . '/DataRepositoryInterface.php';
use classes\DBORM;

class EmployeeRepository implements DataRepositoryInterface {
    private DBORM $db;

    public function __construct(DBORM $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->table('employees')->select()->getAll();
    }

    public function get($id) {
        return $this->getById($id);
    }

    public function getById($id) {
        return $this->db->table('employees')->select()->where('ID', $id)->get();
    }

    public function create(array $data) {
        return $this->db->table('employees')->insert([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'office' => $data['office']
        ]);


    }


    public function update($id, array $data) {
        return $this->db->table('employees')->where('ID', $id)->update([
        'first_name' => $data['first_name'],      
        'last_name' => $data['last_name'],
        'office' => $data['office'] 
    ]);

    }

    public function delete($id) {
        return $this->db->table('employees')->where('ID', $id)->delete();
    }
}

