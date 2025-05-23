<?php
namespace models;

require_once __DIR__ . '/DataRepositoryInterface.php';
use classes\DBORM;

class UserRepository implements DataRepositoryInterface {
    private DBORM $db;

    public function __construct(DBORM $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->table('user')->select()->getAll();
    }

    public function get($id) {
        // Required by the interface
        return $this->getById($id);
    }

    public function getById($id) {
        return $this->db->table('user')->select()->where('ID', $id)->get();
    }

    public function create(array $data) {
        return $this->db->table('user')->insert([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function update($id, $data) {
        return $this->db->table('user')->where('ID', $id)->update([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function delete($id) {
        return $this->db->table('user')->where('ID', $id)->delete();
    }

    public function getByEmail($email) {
        return $this->db
            ->table('user')
            ->select()
            ->where('email', $email)
            ->get();
    }
    
}
