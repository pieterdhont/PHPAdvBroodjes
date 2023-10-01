<?php
// App/Entities/OrderSandwich.php

declare(strict_types=1);

namespace App\Entities;
use App\Entities\Order;
use App\Entities\Sandwich;

class OrderSandwich {
    private int $id;
    private Order $order; 
    private Sandwich $sandwich; 
    

    public function __construct(int $id, Order $order, Sandwich $sandwich) {
        $this->id = $id;
        $this->order = $order; 
        $this->sandwich = $sandwich; 
        
    }

    public function getId(): int {
        return $this->id;
    }

    public function getOrder(): Order {
        return $this->order; 
    }

    public function getSandwich(): Sandwich {
        return $this->sandwich; 
    }

    
}
