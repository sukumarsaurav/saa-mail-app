<?php

namespace App\Models;

class UserList {
    private $db;
    
    public function __construct() {
        $this->db = \App\Utils\Database::getInstance()->getConnection();
    }

    public function createList($userId, $name, $fileName, $totalRecords) {
        $sql = "INSERT INTO user_lists (user_id, name, file_name, total_records, created_at) 
                VALUES (:user_id, :name, :file_name, :total_records, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'file_name' => $fileName,
            'total_records' => $totalRecords
        ]);
    }

    public function getUserLists($userId) {
        $sql = "SELECT * FROM user_lists WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteList($listId, $userId) {
        $sql = "DELETE FROM user_lists WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $listId,
            'user_id' => $userId
        ]);
    }

    public function getList($listId, $userId) {
        $sql = "SELECT * FROM user_lists WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $listId,
            'user_id' => $userId
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
