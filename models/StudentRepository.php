<?php
namespace Models;

class StudentRepository implements DataRepositoryInterface{
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM students");
    }
    public function get($id){
        return $this->db->table('students')
                        ->select()
                        ->where('id', $id)
                        ->get();
}
    public function getById($id) {
        return $this->db->query("SELECT * FROM students WHERE studID = ?", [$id]);
    }

    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $this->db->query(
            "INSERT INTO students ($columns) VALUES ($placeholders)",
            array_values($data)
        );
    }

    public function update($id, $data) {
        $setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $this->db->query(
            "UPDATE students SET $setClause WHERE studID = ?",
            [...array_values($data), $id]
        );
    }

    public function delete($id) {
        $this->db->query("DELETE FROM students WHERE studID = ?", [$id]);
    }
}
