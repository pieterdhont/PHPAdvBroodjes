<?php
//user_orders.php
declare(strict_types=1);

session_start();

require_once("bootstrap.php");

use App\Factory\ServiceFactory;


$serviceFactory = new ServiceFactory();
$userService = $serviceFactory->createUserService();
$orderService = $serviceFactory->createOrderService();
$sandwichService = $serviceFactory->createSandwichService();
$user = $userService->authenticateUser($_SESSION);
$userId = $user->getId();
$userOrdersDetail = $orderService->getUserOrdersDetail($userId);
$processedUserOrdersDetail = $sandwichService->processUserOrderDetails($userOrdersDetail);


$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);


echo $twig->render('user_orders.twig', [
  'userOrdersDetail' => $processedUserOrdersDetail,
  'session' => $_SESSION,
  'error_message' => $error_message,
  'success_message' => $success_message

]);
