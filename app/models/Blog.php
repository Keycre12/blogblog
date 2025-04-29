<?php

namespace Aries\Dbmodel\Models;

use Aries\Dbmodel\Includes\Database;

class Blog extends Database {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->getConnection();
    }


    public function getAllPosts() {
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getPostsByUser($userId) {
        $sql = "SELECT * FROM blogs WHERE author_id = :author_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['author_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createPost($data) {
        $sql = "INSERT INTO blogs (title, content, author_id, created_at) 
                VALUES (:title, :content, :author_id, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'author_id' => $data['author_id']
        ]);
        return $this->db->lastInsertId();
    }
}