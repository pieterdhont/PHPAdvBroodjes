<?php 
// App/Data/SandwichDAO.php
declare(strict_types=1);

namespace App\Data;

use App\Entities\Sandwich;
use PDO;


class SandwichDAO {
    
    private PDO $db; 
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance(); 
    }

    public function getAll(): array {
        $sql = "SELECT * FROM sandwiches";
        $resultSet = $this->db->query($sql);
        
        $sandwiches = array();
        foreach ($resultSet as $row) {
            $sandwich = new Sandwich($row["id"], $row["name"], (float)$row["price"]);
            array_push($sandwiches, $sandwich);
        }
        
        return $sandwiches;
    }

    public function getById(int $id): ?Sandwich {
        $sql = "SELECT * FROM sandwiches WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        
        return new Sandwich((int)$row['id'], $row['name'], (float)$row['price']);
    }
}
