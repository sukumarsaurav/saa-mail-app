<?php

namespace App\Models;

class Template {
    private $db;
    
    public function __construct() {
        $this->db = \App\Utils\Database::getInstance()->getConnection();
    }

    public function createTemplate($userId, $data) {
        $sql = "INSERT INTO templates (user_id, name, html_content, css_content, js_content, is_default) 
                VALUES (:user_id, :name, :html_content, :css_content, :js_content, :is_default)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'name' => $data['name'],
            'html_content' => $data['html_content'],
            'css_content' => $data['css_content'] ?? '',
            'js_content' => $data['js_content'] ?? '',
            'is_default' => $data['is_default'] ?? 0
        ]);
    }

    public function getUserTemplates($userId) {
        $sql = "SELECT * FROM templates WHERE user_id = :user_id OR is_default = 1 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTemplate($id, $userId) {
        $sql = "SELECT * FROM templates WHERE (id = :id AND user_id = :user_id) OR (id = :id AND is_default = 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateTemplate($id, $userId, $data) {
        $sql = "UPDATE templates 
                SET name = :name, 
                    html_content = :html_content, 
                    css_content = :css_content, 
                    js_content = :js_content 
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'name' => $data['name'],
            'html_content' => $data['html_content'],
            'css_content' => $data['css_content'] ?? '',
            'js_content' => $data['js_content'] ?? ''
        ]);
    }

    public function deleteTemplate($id, $userId) {
        $sql = "DELETE FROM templates WHERE id = :id AND user_id = :user_id AND is_default = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
