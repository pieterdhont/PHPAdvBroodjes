<?php
//resetPassword.php
declare(strict_types=1);
session_start();

require_once("bootstrap.php");

use App\Factory\ServiceFactory;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\InvalidEmailAddressException;


$error = "";
$message = "";

if (isset($_POST["btnReset"])) {
    $email = $_POST["txtEmail"] ?? "";

    if (!$email) $error .= "Het emailadres moet ingevuld worden.<br>";

    if ($error === "") {
        try {
            $serviceFactory = new ServiceFactory();
            $userService = $serviceFactory->createUserService();
            $user = $userService->resetPassword($email, $_SESSION);
          
            $message = "Uw wachtwoord is gereset." . $_SESSION['message'];
            header("Location: " . $_SERVER['PHP_SELF']); 
        exit;
            
            
    } catch (InvalidEmailAddressException $e) {
        $_SESSION['error'] = "Het ingevoerde emailadres is geen geldig emailadres.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (UserNotFoundException $e) {
        $_SESSION['error'] = "Gebruiker met dit emailadres bestaat niet.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    }
}

$templateVariables = [
    'error' => $_SESSION['error'] ?? "",
    'user' => $_SESSION['user'] ?? null,
    'message' => $_SESSION['message'] ?? "",
    'php_self' => $_SERVER['PHP_SELF']
];

echo $twig->render('resetPassword.twig', $templateVariables);


if(isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}
if(isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}