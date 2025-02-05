<?php

namespace App\Models;

class Setting {
    private $db;
    
    public function __construct() {
        $this->db = \App\Utils\Database::getInstance()->getConnection();
    }

    public function createSmtpSetting(array $data, int $userId) {
        $sql = "INSERT INTO settings (user_id, name, smtp_host, smtp_port, smtp_username, 
                smtp_password, created_at) 
                VALUES (:user_id, :name, :smtp_host, :smtp_port, :smtp_username, 
                :smtp_password, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'name' => $data['name'],
            'smtp_host' => $data['smtp_host'],
            'smtp_port' => $data['smtp_port'],
            'smtp_username' => $data['smtp_username'],
            'smtp_password' => $this->encryptPassword($data['smtp_password'])
        ]);
    }

    public function getUserSettings(int $userId) {
        $sql = "SELECT * FROM settings WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function updateSmtpSetting(array $data, int $settingId, int $userId) {
        $sql = "UPDATE settings 
                SET name = :name, 
                    smtp_host = :smtp_host,
                    smtp_port = :smtp_port,
                    smtp_username = :smtp_username,
                    smtp_password = :smtp_password
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $settingId,
            'user_id' => $userId,
            'name' => $data['name'],
            'smtp_host' => $data['smtp_host'],
            'smtp_port' => $data['smtp_port'],
            'smtp_username' => $data['smtp_username'],
            'smtp_password' => $this->encryptPassword($data['smtp_password'])
        ]);
    }

    public function deleteSetting(int $settingId, int $userId) {
        $sql = "DELETE FROM settings WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $settingId,
            'user_id' => $userId
        ]);
    }

    private function encryptPassword($password) {
        // In a production environment, use proper encryption
        return openssl_encrypt(
            $password,
            'AES-256-CBC',
            $_ENV['ENCRYPTION_KEY'],
            0,
            $_ENV['ENCRYPTION_IV']
        );
    }

    private function decryptPassword($encryptedPassword) {
        return openssl_decrypt(
            $encryptedPassword,
            'AES-256-CBC',
            $_ENV['ENCRYPTION_KEY'],
            0,
            $_ENV['ENCRYPTION_IV']
        );
    }
}
