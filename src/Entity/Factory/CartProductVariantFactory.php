<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Cart;
use App\Entity\CartProductVariant;
use App\Entity\ProductVariant;

class CartProductVariantFactory
{
    public function create(Cart $cart, ProductVariant $productVariant, int $quantity): CartProductVariant
    {
        $cartProductVariant = new CartProductVariant();

        $cartProductVariant->setCart($cart);
        $cartProductVariant->setProductVariant($productVariant);
        $cartProductVariant->setQuantity($quantity);

        return $cartProductVariant;
    }
}