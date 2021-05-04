<?php

declare(strict_types=1);

namespace App\Entity\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class CartProductVariantCollection extends ArrayCollection
{
    public function withInsufficientStock(): self
    {
        $collection = new self();

        foreach ($this as $cartProductVariant) {
            $productVariant = $cartProductVariant->getVariant();

            if ($cartProductVariant->getQuantity() > $productVariant->getQuantity()) {
                $collection->add($productVariant);
            }
        }

        return $collection;
    }
}