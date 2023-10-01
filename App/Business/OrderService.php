<?php
//App/Business/OrderService.php
declare(strict_types=1);

namespace App\Business;

use App\Business\Traits\OrderServiceTrait;
use App\Data\{UserDAO, OrderDAO, SandwichDAO, OrderSandwichDAO, FillingDAO, OrderFillingDAO};
use App\Entities\User;
use App\Business\SandwichService;
use App\Business\FillingService;

use Exception;


class OrderService
{
    use OrderServiceTrait;
    private OrderDAO $orderDAO;
    private OrderSandwichDAO $orderSandwichDAO;
    private OrderFillingDAO $orderFillingDAO;
   

    public function __construct(
        OrderDAO $orderDAO,
        OrderSandwichDAO $orderSandwichDAO,
        OrderFillingDAO $orderFillingDAO,
        
    ) {
        $this->orderDAO = $orderDAO;
        $this->orderSandwichDAO = $orderSandwichDAO;
        $this->orderFillingDAO = $orderFillingDAO;
        
    }


    public function getUserOrdersDetail(int $userId): array
    {
        $detail = [];
        $orders = $this->orderDAO->getOrdersByUserId($userId);
        foreach ($orders as $order) {
            $user = $order->getUser();
            $orderDetail = [
                'user' => $user->getEmail(),
                'orders' => []
            ];
            $orderSandwiches = $this->orderSandwichDAO->getByOrderId($order->getId());
            foreach ($orderSandwiches as $orderSandwich) {
                $sandwich = $orderSandwich->getSandwich();

                $sandwichDetail = [
                    'sandwich' => $sandwich->getName(),
                    'price' => $sandwich->getPrice(),
                    'fillings' => []
                ];

                $orderFillings = $this->orderFillingDAO->getByOrderSandwichId($orderSandwich->getId());

                foreach ($orderFillings as $orderFilling) {
                    $filling = $orderFilling->getFilling();

                    $sandwichDetail['fillings'][] = [
                        'name' => $filling->getName(),
                        'price' => $filling->getPrice()
                    ];
                }

                $orderDetail['orders'][] = $sandwichDetail;
            }

            $detail[] = $orderDetail;
        }

        return $detail;
    }



    public function placeOrder(int $userId, array $basket): int
    {   
        
        // Enable error reporting
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        /*
        try {
            $this->orderDAO->beginTransaction();
            echo "Transaction Started"; // Debug statement
        */

        $orderId = $this->orderDAO->insertOrder($userId);

        foreach ($basket as $item) {

            $orderSandwichId = $this->orderSandwichDAO->insertOrderSandwich($orderId, $item['sandwich']);

            
            if (!$orderSandwichId) {
                echo "Failed to insert OrderSandwich"; // Debug statement
                
                return -1;
            }

            foreach ($item['fillings'] as $fillingId) {

                
                $orderFillingInserted = $this->orderFillingDAO->insertOrderFilling($orderSandwichId, $fillingId);

                
                if (!$orderFillingInserted) {
                    echo "Failed to insert OrderFilling"; // Debug statement
                    
                    return -1;
                }
            }
        }
        /*
            $this->orderDAO->commit();
            echo "Transaction Committed"; // Debug statement
        } catch (Exception $e) {
            $this->orderDAO->rollBack();
            echo "Transaction Rolled Back due to: " . $e->getMessage(); // Debug statement
            
            return -1;
        }
        */

        return $orderId;
    }
    public function getLastOrderDate(int $userId): ?string
    {
        try {
            return $this->orderDAO->getLastOrderDate($userId);
        } catch (Exception $e) {

            return null;
        }
    }

    public static function getOrderServiceInstance(): OrderService
    {
        $userDAO = new UserDAO();
        $orderDAO = new OrderDAO($userDAO);
        $sandwichDAO = new SandwichDAO();
        $orderSandwichDAO = new OrderSandwichDAO($orderDAO, $sandwichDAO);
        $fillingDAO = new FillingDAO();
        $orderFillingDAO = new OrderFillingDAO($orderSandwichDAO, $fillingDAO);
        
        
        return new OrderService($orderDAO, $orderSandwichDAO, $orderFillingDAO);
    }



    public function handlePostRequest(string $action, array $postData, array &$sessionData, User $user): void
    {
        switch ($action) {
            case 'remove':
                $this->handleRemove($postData, $sessionData);
                break;
            case 'placeOrder':
                $this->handlePlaceOrder($user->getId(), $sessionData);
                break;
            default:
                $this->handleDefault($postData, $sessionData);
        }
    }
}
