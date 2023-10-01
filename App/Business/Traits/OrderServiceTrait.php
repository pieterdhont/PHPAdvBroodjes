<?php 
//App/Business/Traits/OrderServiceTrait.php
declare(strict_types=1);

namespace App\Business\Traits;
use Exception;

trait OrderServiceTrait
{
    private function handleRemove(array $postData, array &$sessionData): void
    {
        $index = $postData['index'] ?? null;
        if ($index !== null && isset($sessionData['basket'][$index])) {
            unset($sessionData['basket'][$index]);
            $sessionData['basket'] = array_values($sessionData['basket']);
        }
    }
    
    private function handlePlaceOrder(int $userId, array &$sessionData): void
    {
        try {
            $this->placeOrder($userId, $sessionData['basket']);
            $sessionData['basket'] = [];
            $sessionData['success_message'] = 'Uw bestelling is geplaatst!';
        } catch (Exception $e) {
            throw new Exception('Order could not be placed: ' . $e->getMessage());
        }
    }
    
    private function handleDefault(array $postData, array &$sessionData): void
    {
        $sandwichId = (int) ($postData['sandwich'] ?? 0);
        $fillingIds = array_map('intval', $postData['fillings'] ?? []);
        $sessionData['basket'][] = [
            'sandwich' => $sandwichId,
            'fillings' => $fillingIds,
        ];
    }
}


?>