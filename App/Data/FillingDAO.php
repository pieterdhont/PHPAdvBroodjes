<?php
//App/Data/FillingDAO.php
declare(strict_types=1);

namespace App\Data;

use App\Entities\Filling;
use PDO;


class FillingDAO {

    private PDO $db; 
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance(); 
    }
  
    public function getAll() {
        $sql = "SELECT * FROM fillings";
        $resultSet = $this->db->query($sql);
        $fillings = array();
        foreach ($resultSet as $row) {
            $filling = new Filling((int)$row["id"], $row["name"], (float)$row["price"]);
            array_push($fillings, $filling);
        }
        
        return $fillings;
    }
  
    public function getById(int $id): ?Filling {
        $sql = "SELECT * FROM fillings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        
        return new Filling((int)$row['id'], $row['name'], (float)$row['price']);
    }
}
