<?php

namespace App\Repository;

use App\Entity\TableReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableReservation[]    findAll()
 * @method TableReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableReservation::class);
    }
}
