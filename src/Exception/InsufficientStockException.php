<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Collection\CartProductVariantCollection;
use Exception;

class InsufficientStockException extends Exception
{
    private CartProductVariantCollection $cartProductVariants;

    public function __construct(CartProductVariantCollection $cartProductVariants)
    {
        parent::__construct();

        $this->cartProductVariants = $cartProductVariants;
    }

    public function getCartProductVariants(): CartProductVariantCollection
    {
        return $this->cartProductVariants;
    }
}