<?php
//App/Business/SandwichService.php

declare(strict_types=1);

namespace App\Business;

use App\Data\SandwichDAO;
use App\Business\FillingService;




class SandwichService
{

  private FillingService $fillingService;
  private SandwichDAO $sandwichDAO;

  public function __construct(FillingService $fillingService, SandwichDAO $sandwichDAO)
  {
    $this->fillingService = $fillingService;
    $this->sandwichDAO = $sandwichDAO;
  }
  public function getSandwiches(): array
  {
    $sandwichDAO = $this->sandwichDAO;
    $lijst = $sandwichDAO->getAll();
    return $lijst;
  }
  public function prepareBasket(array $basketItems, array $sandwiches, array $fillings): array
  {
    $preparedBasket = [];
    $totalPrice = 0;
    foreach ($basketItems as $index => $item) {
      if (!isset($sandwiches[$item['sandwich']])) {
        continue;
      }
      list($itemTotal, $preparedItem) = $this->fillingService->prepareBasketItem($item, $sandwiches, $fillings, $index);
      $totalPrice += $itemTotal;
      $preparedBasket[] = $preparedItem;
    }
    return ['preparedBasket' => $preparedBasket, 'totalPrice' => $totalPrice];
  }



  public function processUserOrderDetails(array $userOrdersDetail): array
  {
    foreach ($userOrdersDetail as &$orderDetail) {
      $this->calculateGrandTotal($orderDetail);
    }
    return $userOrdersDetail;
  }

  private function calculateGrandTotal(array &$orderDetail): void
  {

    $orderDetail['grandTotal'] = 0;
    foreach ($orderDetail['orders'] as &$sandwichDetail) {
      $this->processSandwichDetail($sandwichDetail, $orderDetail['grandTotal']);
    }
  }

  private function processSandwichDetail(array &$sandwichDetail, float &$grandTotal): void
  {
    $fillingService = $this->fillingService;
    $sandwichTotal = $sandwichDetail['price'];
    $fillingsList = $fillingService->processFillings($sandwichDetail['fillings'], $sandwichTotal);

    $sandwichDetail['sandwichTotal'] = $sandwichTotal;
    $sandwichDetail['fillingsListString'] = implode(', ', $fillingsList);
    $grandTotal += $sandwichTotal;
  }
}
