<?php

declare(strict_types=1);

namespace App\Entity\Collection;

use App\Entity\CartProductVariant;
use Doctrine\Common\Collections\ArrayCollection;

class CartProductVariantCollection extends ArrayCollection
{
    public function withInsufficientStock(): self
    {
        $collection = new self();

        /** @var CartProductVariant $cartProductVariant */

        foreach ($this as $cartProductVariant) {
            $productVariant = $cartProductVariant->getProductVariant();

            if (null === $productVariant->getQuantity()) {
                continue;
            }

            if ($cartProductVariant->getQuantity() > $productVariant->getQuantity()) {
                $collection->add($cartProductVariant);
            }
        }

        return $collection;
    }

    public function getPriceSum(): int
    {
        $sum = 0;

        /** @var CartProductVariant $cartProductVariant */

        foreach ($this as $cartProductVariant) {
            $sum += $cartProductVariant->getProductVariant()->getPrice() * $cartProductVariant->getQuantity();
        }

        return $sum;
    }
}