<?php

namespace App\Repository;

use App\Entity\CartProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProductVariant[]    findAll()
 * @method CartProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProductVariant::class);
    }
}
