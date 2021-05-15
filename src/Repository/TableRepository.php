<?php

namespace App\Repository;

use App\Entity\Table;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Table|null find($id, $lockMode = null, $lockVersion = null)
 * @method Table|null findOneBy(array $criteria, array $orderBy = null)
 * @method Table[]    findAll()
 * @method Table[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Table::class);
    }

    public function findOccupiedForHour(DateTime $date)
    {
        return $this->createQueryBuilder('table')
            ->leftJoin('table.reservations', 'reservation')
            ->where('DAY(reservation.dateFrom) = :day')
            ->andWhere('MONTH(reservation.dateFrom) = :month')
            ->andWhere('HOUR(reservation.dateFrom) = :hour')
            ->setParameters([
                'day' => $date->format('d'),
                'month' => $date->format('m'),
                'hour' => $date->format('H'),
            ])
            ->getQuery()
            ->getResult();
    }

    public function findOneNonOccupiedForHour(DateTime $date): ?Table
    {
        $tables = $this->findAll();
        $occupiedTables = $this->findOccupiedForHour($date);

        foreach ($tables as $table) {
            foreach ($occupiedTables as $occupiedTable) {
                if ($table->getId() === $occupiedTable->getId()) {
                    continue 2;
                }
            }

            return $table;
        }

        return null;
    }
}
