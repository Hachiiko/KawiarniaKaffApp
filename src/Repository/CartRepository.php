<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\User;
use App\Enum\CartStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findActiveForUser(User $user): ?Cart
    {
        try {
            return $this->createQueryBuilder('cart')
                ->where('cart.owner = :owner')
                ->andWhere('cart.status = :status')
                ->setParameter('owner', $user)
                ->setParameter('status', (string) CartStatusEnum::NEW())
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }
}
