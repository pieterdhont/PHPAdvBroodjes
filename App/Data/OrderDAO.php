<?php 
//App/Data/OrderDAO.php
declare(strict_types=1);

namespace App\Data;

use App\Entities\Order;
use PDO;
use App\Data\UserDAO;
use Exception;

class OrderDAO {
    private UserDAO $userDAO;
    private PDO $db; 
    
    public function __construct(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
        $this->db = DatabaseConnection::getInstance();
    }

    

    public function getAll(): array {
        $sql = "SELECT * FROM orders";
        $resultSet = $this->db->query($sql);
        
        $list = [];
        foreach ($resultSet as $row) {
            $user = $this->userDAO->getUserById((int)$row["user_id"]);
            $orderDateTime = new \DateTime($row["order_datetime"]);
            $order = new Order((int)$row["id"], $user, $orderDateTime);
            $list[] = $order;
        }
        return $list;
    }

    public function getOrdersByUserId(int $userId): array {
        $sql = "SELECT * FROM orders WHERE user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
      
        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $user = $this->userDAO->getUserById((int)$row["user_id"]);
            $orderDateTime = new \DateTime($row["order_datetime"]);
            $order = new Order((int)$row["id"], $user, $orderDateTime);
            $list[] = $order;
        }
        return $list;
    }

    public function getById(int $id): ?Order {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        
        $user = $this->userDAO->getUserById((int)$row['user_id']);
        $orderDateTime = new \DateTime($row['order_datetime']);
        return new Order((int)$row['id'], $user, $orderDateTime);
    }

    public function insertOrder(int $userId): int {
        $sql = "INSERT INTO orders (user_id, order_datetime) VALUES (:userId, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
        // Debugging Code
        // $startTime = microtime(true);
        
        try {
            // If execute fails, it will throw an Exception
            if (!$stmt->execute()) {
                // Debugging Code
                // echo 'Execute failed at ' . microtime(true) . '<br>';
                throw new Exception('Failed to insert order');
            }
        } catch (Exception $e) {
            // Debugging Code
            // echo 'Exception caught at ' . microtime(true) . ': ' . $e->getMessage() . '<br>';
            // Rethrow the caught exception for higher-level error handling
            throw $e; 
        }
        
        // Debugging Code
        // $endTime = microtime(true);
        // $elapsedTime = $endTime - $startTime;
        // echo "SQL Query executed in $elapsedTime seconds" . '<br>';
    
        // Return the ID of the last inserted row or sequence value
        return (int)$this->db->lastInsertId();
    }
    
    public function getLastOrderDate(int $userId): ?string {
        try {
            $stmt = $this->db->prepare("SELECT order_datetime AS last_order_date FROM orders WHERE user_id = :userId ORDER BY order_datetime DESC LIMIT 1");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result['last_order_date'])) { 
                $date = new \DateTime($result['last_order_date']); 
                return $date->format('Y-m-d'); 
            }
            return null;
        } catch (Exception $e) {
            
            return null;
        }
    }
    
    

    public function beginTransaction(): void {
        $this->db->beginTransaction();
    }

    public function commit(): void {
        $this->db->commit();
    }

    public function rollBack(): void {
        $this->db->rollBack();
    }
}
