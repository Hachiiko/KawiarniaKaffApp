<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Cart;
use App\Entity\User;
use App\Enum\CartStatusEnum;

class CartFactory
{
    public function create(User $owner): Cart
    {
        $cart = new Cart();

        $cart->setOwner($owner);
        $cart->setStatus(CartStatusEnum::NEW());

        return $cart;
    }
}