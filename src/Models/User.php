<?php

namespace App\Models;

class User {
    private $db;
    
    public function __construct() {
        $this->db = \App\Utils\Database::getInstance()->getConnection();
    }

    public function create(array $userData) {
        $sql = "INSERT INTO users (email, password, name, is_verified, created_at) 
                VALUES (:email, :password, :name, :is_verified, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'email' => $userData['email'],
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'name' => $userData['name'],
            'is_verified' => 0
        ]);
    }

    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function verifyEmail(int $userId) {
        $sql = "UPDATE users SET is_verified = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }

    public function createGoogleUser(array $userData) {
        $sql = "INSERT INTO users (email, password, name, is_verified, google_oauth_token, created_at) 
                VALUES (:email, :password, :name, 1, :google_oauth_token, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'email' => $userData['email'],
            'password' => $userData['password'],
            'name' => $userData['name'],
            'google_oauth_token' => $userData['google_oauth_token']
        ]);
    }

    public function updateGoogleToken(string $email, string $token) {
        $sql = "UPDATE users SET google_oauth_token = :token WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'email' => $email
        ]);
    }

    public function verifyEmailToken(string $token) {
        $sql = "UPDATE users SET is_verified = 1, verification_token = NULL 
                WHERE verification_token = :token";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token]);
    }

    public function storeResetToken(string $email, string $token) {
        $sql = "UPDATE users SET reset_token = :token, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) 
                WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'email' => $email
        ]);
    }
} 