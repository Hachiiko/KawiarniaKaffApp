<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Service\OrderTokenGenerator;

class OrderFactory
{
    private OrderTokenGenerator $tokenGenerator;

    public function __construct(OrderTokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function create(User $owner, string $firstName, string $lastName, string $phone): Order
    {
        $order = new Order();

        $order->setOwner($owner);
        $order->setFirstName($firstName);
        $order->setLastName($lastName);
        $order->setPhone($phone);

        $order->setStatus(OrderStatusEnum::NEW());
        $order->setToken($this->tokenGenerator->generate());

        return $order;
    }
}