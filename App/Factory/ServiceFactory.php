<?php
//App/Factory/ServiceFactory.php
declare(strict_types=1);

namespace App\Factory;

use App\Business\{
    UserService,
    SandwichService,
    FillingService,
    OrderService
};
use App\Data\{
    UserDAO,
    SandwichDAO,
    FillingDAO
};

class ServiceFactory
{

    public function createUserService(): UserService
    {
        return new UserService(new UserDAO());
    }

    public function createSandwichService(): SandwichService
    {
        $fillingService = $this->createFillingService();
        return new SandwichService($fillingService, new SandwichDAO());
    }

    public function createFillingService(): FillingService
    {
        return new FillingService(new FillingDAO());
    }

    public function createOrderService(): OrderService
    {
        return OrderService::getOrderServiceInstance();
    }
}
