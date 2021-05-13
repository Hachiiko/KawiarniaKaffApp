<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CartProductVariant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartProductVariants")
     */
    private Cart $cart;

    /**
     * @ORM\ManyToOne(targetEntity=ProductVariant::class)
     */
    private ProductVariant $productVariant;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getProductVariant(): ProductVariant
    {
        return $this->productVariant;
    }

    public function setProductVariant(ProductVariant $productVariant): void
    {
        $this->productVariant = $productVariant;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function isStockSufficient(): bool
    {
        $productVariant = $this->getProductVariant();

        if (null === $productVariant->getQuantity()) {
            return true;
        }

        return $productVariant->getQuantity() < $this->getQuantity();
    }
}
