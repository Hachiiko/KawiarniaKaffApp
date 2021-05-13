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
            'Kawa' => (function () {
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
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => 's',
                        'price' => 700,
                    ], [
                        'name' => 'm',
                        'price' => 900,
                    ], [
                        'name' => 'l',
                        'price' => 1100,
                    ],
                ],
            ], [
                'name' => 'Kawa biała klasyczna',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => 's',
                        'price' => 700,
                    ], [
                        'name' => 'm',
                        'price' => 900,
                    ], [
                        'name' => 'l',
                        'price' => 1100,
                    ],
                ],
            ], [
                'name' => 'Espresso',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => '50ml',
                        'price' => 700,
                    ],
                ],
            ], [
                'name' => 'Flat white',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => '300ml',
                        'price' => 1000,
                    ],
                ],
            ], [
                'name' => 'Cappuccino',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => 'm',
                        'price' => 1000,
                    ], [
                        'name' => 'l',
                        'price' => 1200,
                    ],
                ],
            ], [
                'name' => 'Drip',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => '500ml',
                        'price' => 1500
                    ],
                ],
            ], [
                'name' => 'Aeropress',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => '350ml',
                        'price' => 1200,
                    ],
                ],
            ], [
                'name' => 'Szybki przelew',
                'category' => $categories['Kawa'],
                'variants' => [
                    [
                        'name' => '400ml',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Herbata zimowa',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Herbata imbirowa',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Earl Grey',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 700,
                    ]
                ],
            ], [
                'name' => 'Herbata malinowa',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Zielona Matcha',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1300,
                    ]
                ],
            ], [
                'name' => 'Owoce leśne',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Zielona jaśminowa',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 900,
                    ]
                ],
            ], [
                'name' => 'Zielona lawendowa',
                'category' => $categories['Herbata'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 800,
                    ]
                ],
            ], [
                'name' => 'Sernik domowy',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1000,
                    ]
                ],
            ], [
                'name' => 'Szarlotka',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1200,
                    ]
                ],
            ], [
                'name' => 'Sernik z malinami',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Brownie',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ], [
                'name' => 'Sernik lawendowy',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1300,
                    ]
                ],
            ], [
                'name' => 'Sernik dyniowy',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1300,
                    ]
                ],
            ], [
                'name' => 'Tarta Oreo',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1300,
                    ]
                ],
            ], [
                'name' => 'Tiramisu',
                'category' => $categories['Desery'],
                'variants' => [
                    [
                        'name' => '',
                        'price' => 1100,
                    ]
                ],
            ],
        ];
    }
}
