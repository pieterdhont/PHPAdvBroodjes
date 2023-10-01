<?php 
//App/Business/FillingService.php

declare(strict_types=1);

namespace App\Business;

use App\Data\FillingDAO;


class FillingService {

  private FillingDAO $fillingDAO;

  public function __construct(FillingDAO $fillingDAO) {
    $this->fillingDAO = $fillingDAO;
  }
  public function getFillings(): array {
    $fillingDAO = $this->fillingDAO; 
    $lijst = $fillingDAO->getAll(); 
    return $lijst; 
  }
  public function prepareBasketItem(array $item, array $sandwiches, array $fillings, int $index): array {
    $sandwichPrice = $sandwiches[$item['sandwich']]->getPrice();
    list($fillingsPrice, $fillingsNames) = $this->prepareFillings($item['fillings'], $fillings);
    
    $itemTotal = $sandwichPrice + $fillingsPrice;
    $preparedItem = [
        'name' => $sandwiches[$item['sandwich']]->getName(),
        'fillings' => implode(', ', $fillingsNames),
        'total' => $itemTotal,
        'index' => $index,
    ];
    return [$itemTotal, $preparedItem];
}
  private function prepareFillings(array $fillingIds, array $fillings): array {
    $fillingsPrice = 0;
    $fillingsNames = [];
    foreach ($fillingIds as $fillingId) {
        if (isset($fillings[$fillingId])) {
            $fillingsPrice += $fillings[$fillingId]->getPrice();
            $fillingsNames[] = $fillings[$fillingId]->getName();
        }
    }
    return [$fillingsPrice, $fillingsNames];
}

public function processFillings(array $fillings, float &$sandwichTotal): array {
  $fillingsList = [];
  foreach ($fillings as $filling) {
      $sandwichTotal += $filling['price'];
      $fillingsList[] = $filling['name'];
  }
  return $fillingsList;
}
}
