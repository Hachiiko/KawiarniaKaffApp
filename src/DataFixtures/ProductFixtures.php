<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getProductsData() as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setCategory($productData['category']);

            foreach ($productData['variants'] as $variantData) {
                $variant = new ProductVariant();
                $variant->setName($variantData['name']);
                $variant->setPrice($variantData['price']);
                $variant->setProduct($product);

                $manager->persist($variant);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getProductsData(): array
    {
        $categories = [
            'coffee' => (function () {
                $category = new ProductCategory();
                $category->setName('Kawa');

                return $category;
            })(),
            'Herbata' => (function () {
                $category = new ProductCategory();
                $category->setName('Herbata');

                return $category;
            })(),
            'Desery' => (function () {
                $category = new ProductCategory();
                $category->setName('Desery');

                return $category;
            })(),
        ];

        return [
            [
                'name' => 'Kawa czarna klasyczna',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => 's',
                        'price' => 700,
                    ],
                    [
                        'name' => 'm',
                        'price' => 900,
                    ],
                    [
                        'name' => 'l',
                        'price' => 1100,
                    ],
                ],
            ],
            [
                'name' => 'Kawa biała klasyczna',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => 's',
                        'price' => 700,
                    ],
                    [
                        'name' => 'm',
                        'price' => 900,
                    ],
                    [
                        'name' => 'l',
                        'price' => 1100,
                    ],
                ],
            ],
            [
                'name' => 'Espresso',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => '50ml',
                        'price' => 700,
                    ],
                ],
            ],
            [
                'name' => 'Flat white',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => '300ml',
                        'price' => 1000,
                    ],
                ],
            ],
            [
                'name' => 'Cappuccino',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => 'm',
                        'price' => 1000,
                    ],
                    [
                        'name' => 'l',
                        'price' => 1200,
                    ],
                ],
            ],
            [
                'name' => 'Drip',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => '500ml',
                        'price' => 1500
                    ],
                ],
            ],
            [
                'name' => 'Aeropress',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => '350ml',
                        'price' => 1200,
                    ],
                ],
            ],
            [
                'name' => 'Szybki przelew',
                'category' => $categories['coffee'],
                'variants' => [
                    [
                        'name' => '400ml',
                        'price' => 1100,
                    ]
                ],
            ],
        ];
    }
}
