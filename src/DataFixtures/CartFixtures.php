<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\CartProductVariant;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\CartStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User[] $users */
        $users = $manager->getRepository(User::class)->findAll();

        /** @var Product[] $users */
        $products = $manager->getRepository(Product::class)->findAll();



        foreach ($users as $user) {
            $cart = new Cart();
            $cart->setOwner($user);
            $cart->setStatus(CartStatusEnum::NEW());

            $cart->addProduct($products[array_rand($products)]);

            $manager->persist($cart);

            $user->addCart($cart);
        }

        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
