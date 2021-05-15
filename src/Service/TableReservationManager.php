<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Table;
use App\Repository\TableRepository;
use App\Repository\TableReservationRepository;
use DateTime;

class TableReservationManager
{
    private TableRepository $tableRepository;
    private TableReservationRepository $tableReservationRepository;

    public function __construct(TableRepository $tableRepository, TableReservationRepository $tableReservationRepository)
    {
        $this->tableRepository = $tableRepository;
        $this->tableReservationRepository = $tableReservationRepository;
    }

    public function getAvailableHours(): array
    {
        $today = new DateTime();
        $tommorow = (clone $today)->modify('+1 day');

        $days = [$today, $tommorow];

        $availableHoursTableMap = [];

        $tableCount = $this->tableRepository->count([]);

        // Create available hours table map, holding information how many tables are non-occupied in given day and hour.

        foreach ($days as $day) {
            $dayString = $day->format('d/m/Y');

            foreach (range(9, 21) as $hour) {
                $availableHoursTableMap[$dayString][$hour] = $tableCount;
            }
        }

        // Modify available hours table map subtracting amounts of occupied tables.

        foreach ($days as $day) {
            $reservations = $this->tableReservationRepository->findReservedForDay($day);

            foreach ($reservations as $reservation) {
                $day = $reservation->getDateFrom()->format('d/m/Y');
                $hour = (int) $reservation->getDateFrom()->format('H');

                $availableHoursTableMap[$day][$hour] -= 1;
            }
        }

        // Iterate through available hours table map to retrieve days with at least one non-occupied table.

        $availableHours = [];

        foreach ($availableHoursTableMap as $day => $hours) {
            foreach ($hours as $hour => $availableTableCount) {
                if ($availableTableCount < 1) {
                    continue;
                }

                if (!array_key_exists($day, $availableHours)) {
                    $availableHours[$day] = [];
                }

                $availableHours[$day][] = $hour;
            }
        }

        return $availableHours;
    }

    public function getNonOccupiedTableForHour(DateTime $date): ?Table
    {
        return $this->tableRepository->findOneNonOccupiedForHour($date);
    }
}