<?php
//login.php
declare(strict_types=1);

session_start();
require_once("bootstrap.php");

use App\Factory\ServiceFactory;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\IncorrectPasswordException;

$error = "";

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["btnLogin"])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token.');
    }
    
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    
    if (!$email || !$password) {
        $error = "Please enter both email and password.";
    } else {
        
        $serviceFactory = new ServiceFactory();
        $userService = $serviceFactory->createUserService();
        
        try {
            $user = $userService->login($email, $password);
            $_SESSION["user"] = serialize($user);
            header("Location: bestel.php");
            exit;
        } catch (UserNotFoundException | IncorrectPasswordException $e) {
            $error = $e->getMessage();
        }
    }
}

echo $twig->render('loginForm.twig', ['error' => $error, 'csrf_token' => $_SESSION['csrf_token']]);
