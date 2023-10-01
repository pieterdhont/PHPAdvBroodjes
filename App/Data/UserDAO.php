<?php 

// App/Data/UserDAO.php
declare(strict_types=1);

namespace App\Data;

use App\Entities\User;
use PDO;

class UserDAO {
    
    private PDO $db; 
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance(); 
    }
  
    public function emailIsAlreadyUsed(string $email): bool {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } finally {
            
        }
    }
    
    public function register(User $user): User {
        try {
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT); 
            
            $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->bindValue(":email", $user->getEmail());
            $stmt->bindValue(":password", $hashedPassword);
            $stmt->execute();
            
            $lastNewId = (int) $this->db->lastInsertId();
            return new User($lastNewId, $user->getEmail(), $user->getPassword()); 
        } finally {
            
        }
    }
    
    public function getUserByEmail(string $email): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$resultSet) {
                return null;
            } else {
                return new User($resultSet["id"], $resultSet["email"], $resultSet["password"]);
            }
        } finally {
            
        }
    }
    
    public function updatePassword(int $userId, string $newPassword): void {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // hash it here before storing in the database
            $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindValue(":password", $hashedPassword);
            $stmt->bindValue(":id", $userId);
            $stmt->execute();
        } finally {
            
        }
    }
    
    public function getUserById(int $userId): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindValue(":id", $userId);
            $stmt->execute();
            $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$resultSet) {
                return null;
            } else {
                return new User((int)$resultSet["id"], $resultSet["email"], $resultSet["password"]);
            }
        } finally {
            
        }
    }
}
