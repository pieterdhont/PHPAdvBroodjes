<?php
// App/Business/UserService.php
declare(strict_types=1);

namespace App\Business;

use App\Data\UserDAO;
use App\Entities\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\InvalidEmailAddressException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\IncorrectPasswordException;

class UserService {
    private UserDAO $userDAO;

    public function __construct(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    public function registerUser(string $email, array &$session): User {
        $this->validateEmail($email);
        
        if ($this->userDAO->emailIsAlreadyUsed($email)) {
            throw new UserAlreadyExistsException("A user with this email address already exists.");
        }
        
        $password = $this->generatePassword();    
        $session['message'] = $this->sendPasswordEmail($email, $password); 

        $user = new User(0, $email, $password); 
        return $this->userDAO->register($user);
    }
    

    private function validateEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException("The entered email address is invalid.");
        }
    }

    private function generatePassword(): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    private function sendPasswordEmail(string $to, string $password): string {
        
        return "Een email is verstuurd naar $to met het wachtwoord: $password";
    }

    public function login(string $email, string $password): User {
        $user = $this->userDAO->getUserByEmail($email);

        if (!$user) {
            throw new UserNotFoundException("User with email $email does not exist.");
        }

        $hashedPassword = $user->getPassword(); 
        if (!password_verify($password, $hashedPassword)) {
            throw new IncorrectPasswordException("The password entered is incorrect.");
        }

        return $user; 
    }

    public function resetPassword(string $email, array &$session): User {
        $user = $this->userDAO->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User with email $email does not exist.");
        }
        
        $newPassword = $this->generatePassword();
        $session['message'] = $this->sendPasswordEmail($email, $newPassword); 
        
        $this->userDAO->updatePassword($user->getId(), $newPassword);
        return $user; 
    }
    
    public function authenticateUser(array &$session): ?User {
        if (!isset($session["user"])) {
            header("Location: login.php");
            exit;
        }
        return unserialize($session["user"]);
    }
}
