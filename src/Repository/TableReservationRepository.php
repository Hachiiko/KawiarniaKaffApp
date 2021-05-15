<?php

namespace App\Repository;

use App\Entity\TableReservation;
use App\Enum\TableReservationStatusEnum;
use DateTimeInterface;
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

    /**
     * @param  DateTimeInterface $dateTime
     *
     * @return TableReservation[]
     */
    public function findReservedForDay(DateTimeInterface $dateTime): array
    {
        return $this
            ->createQueryBuilder('tableReservation')
            ->where('DAY(tableReservation.dateFrom) = :day')
            ->andWhere('MONTH(tableReservation.dateFrom) = :month')
            ->andWhere('tableReservation.status != :status')
            ->setParameter('day', $dateTime->format('d'))
            ->setParameter('month', $dateTime->format('m'))
            ->setParameter('status', (string) TableReservationStatusEnum::CANCELLED())
            ->getQuery()
            ->getResult();
    }
}
