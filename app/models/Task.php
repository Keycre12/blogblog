<?php

namespace Aries\Dbmodel\Models;

use Aries\Dbmodel\Includes\Database;

class Task extends Database {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    public function getTasks() {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTask($id) {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createTask($data) {
        $sql = "INSERT INTO tasks (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
        ]);
        return $this->db->lastInsertId();
    }

    public function updateTask($data) {
        $sql = "UPDATE tasks SET name = :name, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'status' => $data['status']
        ]);
        return "Task UPDATED successfully";
    }

    public function deleteTask($id) {
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return "Task DELETED successfully";
    }
}