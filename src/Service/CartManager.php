<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartProductVariant;
use App\Entity\Factory\CartFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\OrderProductFactory;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Exception\EmptyCartException;
use App\Exception\InsufficientStockException;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CartManager
{
    private EntityManagerInterface $entityManager;
    private OrderFactory $orderFactory;
    private OrderProductFactory $orderProductFactory;
    private CartFactory $cartFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderFactory $orderFactory,
        OrderProductFactory $orderProductFactory,
        CartFactory $cartFactory
    ) {
        $this->entityManager = $entityManager;
        $this->orderFactory = $orderFactory;
        $this->orderProductFactory = $orderProductFactory;
        $this->cartFactory = $cartFactory;
    }

    /**
     * Retrieves active cart for the given user.
     *
     * @param  User $user
     *
     * @return Cart
     */
    public function get(User $user): Cart
    {
        $cart = $this->getCartRepository()->findActiveForUser($user);

        if (null === $cart) {
            $cart = $this->cartFactory->create(
                owner: $user,
            );

            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    /**
     * Creates an order from given cart, and marks the cart as "resolved".
     *
     * @param  Cart   $cart
     * @param  string $firstName
     * @param  string $lastName
     * @param  string $phone
     *
     * @return Order
     * @throws InsufficientStockException
     * @throws EmptyCartException
     */
    public function resolve(Cart $cart, string $firstName, string $lastName, string $phone): Order
    {
        if ($cart->getCartProductVariants()->isEmpty()) {
            throw new EmptyCartException();
        }

        $order = $this->orderFactory->create(
            owner: $cart->getOwner(),
            firstName: $firstName,
            lastName: $lastName,
            phone: $phone,
        );

        $this->entityManager->persist($order);

        $insufficientStockVariants = $cart->getCartProductVariants()->withInsufficientStock();

        if (!$insufficientStockVariants->isEmpty()) {
            $exception = new InsufficientStockException($insufficientStockVariants);

            foreach ($insufficientStockVariants as $cartProductVariant) {
                $cartProductVariant->setQuantity(
                    $cartProductVariant->getProductVariant()->getQuantity()
                );
            }

            $this->entityManager->flush();

            throw $exception;
        }

        foreach ($cart->getCartProductVariants() as $cartProductVariant) {
            $orderProduct = $this->orderProductFactory->create(
                order: $order,
                productVariant: $cartProductVariant->getVariant(),
                quantity: $cartProductVariant->getQuantity()
            );

            $this->entityManager->persist($orderProduct);
        }

        $cart->markAsResolved();

        $this->entityManager->flush();

        return $order;
    }

    private function getCartRepository(): ObjectRepository|CartRepository
    {
        return $this->entityManager->getRepository(Cart::class);
    }
}