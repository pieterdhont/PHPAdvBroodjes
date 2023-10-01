<?php
// bestel.php
declare(strict_types=1);
session_start();

require_once("bootstrap.php");

use App\Factory\ServiceFactory;

define('CUTOFF_TIME', '23:59');


$serviceFactory = new ServiceFactory();
$userService = $serviceFactory->createUserService();
$sandwichService = $serviceFactory->createSandwichService();
$fillingService = $serviceFactory->createFillingService();
$orderService = $serviceFactory->createOrderService();
$user = $userService->authenticateUser($_SESSION);
$userId = $user->getId(); 
$lastOrderDate = $orderService->getLastOrderDate($userId);

$today = new DateTime('now', new DateTimeZone('Europe/Amsterdam'));
$currentTime = $today->format('H:i');

if ($lastOrderDate === $today->format('Y-m-d') || $currentTime >= CUTOFF_TIME) {
    $_SESSION['error_message'] = $lastOrderDate === $today->format('Y-m-d') 
        ? 'U kan maar 1 bestelling per dag plaatsen' 
        : "Bestellingen kunnen enkel geplaatst worden voor " . CUTOFF_TIME;
    header("Location: user_orders.php");
    exit;
}

$_SESSION['basket'] = $_SESSION['basket'] ?? [];

$rawSandwiches = $sandwichService->getSandwiches();
$sandwiches = [];
foreach ($rawSandwiches as $sandwich) {
    $sandwiches[$sandwich->getId()] = $sandwich;
}
$rawFillings = $fillingService->getFillings();
$fillings = [];
foreach ($rawFillings as $filling) {
    $fillings[$filling->getId()] = $filling;
}

$result = $sandwichService->prepareBasket($_SESSION['basket'], $sandwiches, $fillings);
$preparedBasket = $result['preparedBasket'];
$totalPrice = $result['totalPrice'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    if ($action) {
        try {
            $orderService->handlePostRequest($action, $_POST, $_SESSION, $user);
            $location = $action === 'placeOrder' ? "user_orders.php" : "bestel.php";
            header("Location: $location");
            exit;
        } catch (Exception $e) {
            echo('Error occurred: ' . $e->getMessage());
            $_SESSION['error_message'] = 'An unexpected error occurred!';
        }
    }
}

echo $twig->render('winkelmand.twig', [
    'sandwiches' => $sandwiches,
    'fillings' => $fillings,
    'basket' => $preparedBasket,
    'totalPrice' => $totalPrice,
    'session' => $_SESSION,
]);
