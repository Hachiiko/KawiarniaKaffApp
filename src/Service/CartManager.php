<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Factory\CartFactory;
use App\Entity\Factory\CartProductVariantFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\OrderProductFactory;
use App\Entity\Order;
use App\Entity\ProductVariant;
use App\Entity\User;
use App\Exception\EmptyCartException;
use App\Exception\InsufficientStockException;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CartManager
{
    private EntityManagerInterface $entityManager;
    private OrderFactory $orderFactory;
    private OrderProductFactory $orderProductFactory;
    private CartFactory $cartFactory;
    private CartProductVariantFactory $cartProductVariantFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderFactory $orderFactory,
        OrderProductFactory $orderProductFactory,
        CartFactory $cartFactory,
        CartProductVariantFactory $cartProductVariantFactory
    ) {
        $this->entityManager = $entityManager;
        $this->orderFactory = $orderFactory;
        $this->orderProductFactory = $orderProductFactory;
        $this->cartFactory = $cartFactory;
        $this->cartProductVariantFactory = $cartProductVariantFactory;
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
     * Add product variant to cart of given quantity.
     *
     * @param  Cart           $cart
     * @param  ProductVariant $productVariant
     * @param  int            $quantity
     */
    public function addProductVariant(Cart $cart, ProductVariant $productVariant, int $quantity): void
    {
        $cartProductVariant = $this->cartProductVariantFactory->create(
            cart: $cart,
            productVariant: $productVariant,
            quantity: $quantity,
        );

        $cart->addCartProductVariant($cartProductVariant);
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
                productVariant: $cartProductVariant->getProductVariant(),
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