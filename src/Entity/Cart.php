<?php

namespace App\Entity;

use App\Entity\Collection\CartProductVariantCollection;
use App\Enum\CartStatusEnum;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @ORM\Column(type="cart_status")
     */
    private CartStatusEnum $status;

    /**
     * @ORM\OneToMany(targetEntity=CartProductVariant::class, mappedBy="cart", cascade={"persist"})
     */
    private Collection $cartProductVariants;

    public function __construct()
    {
        $this->cartProductVariants = new CartProductVariantCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(CartStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function markAsResolved(): void
    {
        $this->status = CartStatusEnum::RESOLVED();
    }

    public function getCartProductVariants(): CartProductVariantCollection
    {
        return new CartProductVariantCollection($this->cartProductVariants->toArray());
    }

    public function addCartProductVariant(CartProductVariant $cartProductVariant): void
    {
        if (!$this->cartProductVariants->contains($cartProductVariant)) {
            $this->cartProductVariants[] = $cartProductVariant;
        }
    }

    public function removeCartProductVariant(CartProductVariant $cartProductVariant): void
    {
        $this->cartProductVariants->removeElement($cartProductVariant);
    }
}
