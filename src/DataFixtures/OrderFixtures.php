<?php

namespace App\DataFixtures;

use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\OrderProductFactory;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    private OrderFactory $orderFactory;
    private OrderProductFactory $orderProductFactory;

    public function __construct(OrderFactory $orderFactory, OrderProductFactory $orderProductFactory)
    {
        $this->orderFactory = $orderFactory;
        $this->orderProductFactory = $orderProductFactory;
    }

    public function load(ObjectManager $manager)
    {
        /** @var User[] $users */
        $users = $manager->getRepository(User::class)->findAll();

        /** @var Product[] $products */
        $products = $manager->getRepository(Product::class)->findAll();

        foreach ($users as $user) {
            $order = $this->orderFactory->create(
                owner: $user,
                firstName: 'Jan',
                lastName: 'Kowalski',
                phone: '123 123 123',
            );

            $productVariant = $this->getRandomProductVariant($products);

            $orderProduct = $this->orderProductFactory->create(
                order: $order,
                productVariant: $productVariant,
                quantity: rand(1, 4)
            );

            $manager->persist($order);
            $manager->persist($orderProduct);
        }

        $manager->flush();
    }

    private function getRandomProductVariant(array $products): ProductVariant
    {
        $product = $products[array_rand($products)];
        $productVariants = $product->getVariants()->toArray();

        return $productVariants[array_rand($productVariants)];
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
