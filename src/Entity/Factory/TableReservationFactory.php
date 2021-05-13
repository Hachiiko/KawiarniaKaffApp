<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;
use App\Entity\Table;
use App\Entity\TableReservation;
use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Enum\TableReservationStatusEnum;
use App\Service\OrderTokenGenerator;

class TableReservationFactory
{
    public function create(
        User $owner,
        Table $table,
        string $firstName,
        string $lastName,
        string $phone,
        \DateTimeInterface $dateFrom,
        \DateTimeInterface $dateTo
    ): TableReservation {
        $reservation = new TableReservation();

        $reservation->setOwner($owner);
        $reservation->setTable($table);
        $reservation->setFirstName($firstName);
        $reservation->setLastName($lastName);
        $reservation->setPhone($phone);
        $reservation->setDateFrom($dateFrom);
        $reservation->setDateTo($dateTo);

        $reservation->setStatus(TableReservationStatusEnum::NEW());

        return $reservation;
    }
}