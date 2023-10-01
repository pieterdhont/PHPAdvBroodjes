<?php
//App/Entities/Order.php
declare(strict_types=1);

namespace App\Entities;
use App\Entities\User;

class Order {
    private int $id;
    private User $user;
    private \DateTime $orderDatetime;
    
    public function __construct(int $id, User $user, \DateTime $orderDatetime) {
        $this->id = $id;
        $this->user = $user;
        $this->orderDatetime = $orderDatetime;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getUser(): User {
        return $this->user;
    }

    public function getOrderDatetime(): \DateTime {
        return $this->orderDatetime;
    }
}
