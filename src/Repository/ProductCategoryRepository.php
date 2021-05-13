<?php

namespace App\Repository;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategory[]    findAll()
 * @method ProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    public function findHavingProducts(): array
    {
        return $this->createQueryBuilder('product_category')
            ->addSelect('product')
            ->addSelect('product_variant')
            ->innerJoin('product_category.products', 'product')
            ->innerJoin('product.variants', 'product_variant')
            ->orderBy('product.id')
            ->getQuery()
            ->getResult();
    }
}
