<?php

declare(strict_types=1);

//App/Data/OrderSandwichDAO.php

namespace App\Data;

use App\Entities\OrderSandwich;
use PDO;
use Exception;


class OrderSandwichDAO {
    private OrderDAO $orderDAO;
    private SandwichDAO $sandwichDAO;

    private PDO $db;
    
    public function __construct(OrderDAO $orderDAO, SandwichDAO $sandwichDAO) {
        $this->orderDAO = $orderDAO;
        $this->sandwichDAO = $sandwichDAO;
        $this->db = DatabaseConnection::getInstance();
    }
    
    
    
    public function getByOrderId(int $orderId): array {
        
        $sql = "SELECT * FROM order_sandwich WHERE order_id = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $order = $this->orderDAO->getById((int)$row['order_id']);
            $sandwich = $this->sandwichDAO->getById((int)$row['sandwich_id']);
            $list[] = new OrderSandwich((int)$row['id'], $order, $sandwich);
        }
        
        $dbh = null;
        return $list;
    }

    public function getById(int $id): ?OrderSandwich {
      
      $sql = "SELECT * FROM order_sandwich WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $dbh = null;
      
      if(!$row) return null;
      
      $order = $this->orderDAO->getById((int)$row['order_id']);
      $sandwich = $this->sandwichDAO->getById((int)$row['sandwich_id']);
      
      return new OrderSandwich((int)$row['id'], $order, $sandwich);
    }

    public function insertOrderSandwich(int $orderId, int $sandwichId): int {
        // Debugging Code
        // echo 'Insert Order Sandwich Start at ' . microtime(true) . '<br>';
        
        
        // Debugging Code
        // $startTime = microtime(true);
        
        $lastInsertId = 0; 
        
        try {
            $sql = "INSERT INTO order_sandwich (order_id, sandwich_id) VALUES (:orderId, :sandwichId)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->bindParam(':sandwichId', $sandwichId, PDO::PARAM_INT);
        
            if (!$stmt->execute()) {
                // Debugging Code
                // echo 'Execute failed at ' . microtime(true) . '<br>';
                throw new Exception('Failed to insert order sandwich');
            }
            
            $lastInsertId = $this->db->lastInsertId(); 
            
        } catch (Exception $e) {
            // Debugging Code
            // echo 'Exception caught at ' . microtime(true) . ': ' . $e->getMessage() . '<br>';
            throw $e;
        } finally {
            $dbh = null; // Closing the connection
        }
        
        // Debugging Code
        // $endTime = microtime(true);
        // $elapsedTime = $endTime - $startTime;
        // echo "SQL Query executed in $elapsedTime seconds" . '<br>';
        
        return (int)$lastInsertId; 
    }
    
    
    
    
    
    
    
}
