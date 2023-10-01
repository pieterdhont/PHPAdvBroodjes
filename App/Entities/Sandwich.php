<?php
//App/Entities/Sandwich.php
declare(strict_types=1);

namespace App\Entities;

class Sandwich {
    private int $id;
    private string $name;
    private float $price;

    public function __construct(int $id, string $name, float $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }
}
