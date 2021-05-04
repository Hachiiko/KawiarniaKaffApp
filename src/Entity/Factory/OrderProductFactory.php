<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\ProductVariant;

class OrderProductFactory
{
    public function create(Order $order, ProductVariant $productVariant, int $quantity): OrderProduct
    {
        $orderProduct = new OrderProduct();

        $orderProduct->setOrder($order);
        $orderProduct->setQuantity($quantity);
        $orderProduct->setName($productVariant->getProduct()->getName());
        $orderProduct->setVariantName($productVariant->getName());
        $orderProduct->setPrice($productVariant->getPrice() * $quantity);

        return $orderProduct;
    }
}