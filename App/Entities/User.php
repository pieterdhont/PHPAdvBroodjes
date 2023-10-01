<?php
//App/Entities/User.php
declare(strict_types=1);

namespace App\Entities;

class User {
    private int $id;
    private string $email;
    private string $password;

    public function __construct(int $id, string $email, string $password) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }
    
    
}
