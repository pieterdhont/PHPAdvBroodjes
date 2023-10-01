<?php
//App/Entities/OrderFilling.php
declare(strict_types=1);

namespace App\Entities;

use App\Entities\OrderSandwich;
use App\Entities\Filling;

class OrderFilling {
    private OrderSandwich $orderSandwich; 
    private Filling $filling; 
    
    public function __construct(OrderSandwich $orderSandwich, Filling $filling) {
        $this->orderSandwich = $orderSandwich; 
        $this->filling = $filling; 
    }

    public function getOrderSandwich(): OrderSandwich {
        return $this->orderSandwich; 
    }
    
    public function getFilling(): Filling {
        return $this->filling; 
    }
}
