<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $image;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="products", cascade={"persist"})
     */
    private ProductCategory $category;

    /**
     * @ORM\OneToMany(targetEntity=ProductVariant::class, mappedBy="product")
     */
    private Collection $variants;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getImage(): ?string
    {
        return $this->image ?? null;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getCategory(): ProductCategory
    {
        return $this->category;
    }

    public function setCategory(ProductCategory $category): void
    {
        $this->category = $category;
    }

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(ProductVariant $variant): void
    {
        if (!$this->variants->contains($variant)) {
            if ($variant->getProduct() !== $this) {
                $variant->setProduct($this);
            }

            $this->variants->add($variant);
        }
    }

    public function removeVariant(ProductVariant $variant): void
    {
        if ($this->variants->contains($variant)) {
            $this->variants->removeElement($variant);
        }
    }
}
