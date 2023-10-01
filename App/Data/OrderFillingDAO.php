<?php
declare(strict_types=1);

//App/Data/OrderFillingDAO.php
namespace App\Data;

use App\Entities\OrderFilling;
use PDO;
use Exception;


class OrderFillingDAO {
    private OrderSandwichDAO $orderSandwichDAO;
    private FillingDAO $fillingDAO;

    private PDO $db;

    public function __construct(OrderSandwichDAO $orderSandwichDAO, FillingDAO $fillingDAO) {
        $this->orderSandwichDAO = $orderSandwichDAO;
        $this->fillingDAO = $fillingDAO;
        $this->db = DatabaseConnection::getInstance();
    }
    
    
    
    public function getByOrderSandwichId(int $orderSandwichId): array {
        
        $sql = "SELECT * FROM order_filling WHERE order_sandwich_id = :orderSandwichId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderSandwichId', $orderSandwichId, PDO::PARAM_INT);
        $stmt->execute();

        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $orderSandwich = $this->orderSandwichDAO->getById((int)$row['order_sandwich_id']);
            $filling = $this->fillingDAO->getById((int)$row['filling_id']);
            $list[] = new OrderFilling($orderSandwich, $filling);
        }

        $dbh = null;
        return $list;
    }

    public function insertOrderFilling(int $orderSandwichId, int $fillingId): bool {
        // Debugging Code
        // echo 'Insert Order Filling Start at ' . microtime(true) . '<br>';
        
        
        // Debugging Code
        // $startTime = microtime(true);
        
        try {
            $sql = "INSERT INTO order_filling (order_sandwich_id, filling_id) VALUES (:orderSandwichId, :fillingId)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orderSandwichId', $orderSandwichId, PDO::PARAM_INT);
            $stmt->bindParam(':fillingId', $fillingId, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                // Debugging Code
                // echo 'Execute failed at ' . microtime(true) . '<br>';
                throw new Exception('Failed to insert order filling');
            }
        } catch (Exception $e) {
            // Debugging Code
            // echo 'Exception caught at ' . microtime(true) . ': ' . $e->getMessage() . '<br>';
            throw $e;
        } finally {
            $dbh = null;
        }
        
        // Debugging Code
        // $endTime = microtime(true);
        // $elapsedTime = $endTime - $startTime;
        // echo "SQL Query executed in $elapsedTime seconds" . '<br>';
        
        return true;
    }
    
    
    
}
