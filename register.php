<?php 
//register.php
declare(strict_types=1);
session_start();

require_once("bootstrap.php");

use App\Factory\ServiceFactory;
use App\Exceptions\InvalidEmailAddressException;
use App\Exceptions\UserAlreadyExistsException;

$error = "";
$message = "";

if (isset($_POST["btnRegister"])) {
    $email = $_POST["txtEmail"] ?? "";

    if (!$email) $error .= "The email address must be filled in.";
    
    if ($error === "") {
        try {
            $serviceFactory = new ServiceFactory();
            $userService = $serviceFactory->createUserService();
            $user = $userService->registerUser($email, $_SESSION);
            $message = "U bent geregistreerd. " . ($_SESSION['message'] ?? '');
           
            
        } catch (InvalidEmailAddressException $e) {
            $error .= "The entered email address is not a valid email address.";
        } catch (UserAlreadyExistsException $e) {
            $message = "You are already registered.";
        }
    }
}

$templateVariables = [
  'error' => $error,
  'user' => $_SESSION['user'] ?? null,
  'message' => $message,
  'php_self' => $_SERVER['PHP_SELF']
];

echo $twig->render('registratieForm.twig', $templateVariables);


if(isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}
