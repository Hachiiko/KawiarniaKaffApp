<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * @method static TableReservationStatusEnum NEW()
 * @method static TableReservationStatusEnum CONFIRMED()
 * @method static TableReservationStatusEnum CANCELLED()
 */
class TableReservationStatusEnum extends Enum
{
    private const NEW = 'new';
    private const CONFIRMED = 'confirmed';
    private const CANCELLED = 'cancelled';

    public static function getLabels(): array
    {
        return [
            self::NEW => 'Nowe',
            self::CONFIRMED => 'Potwierdzone',
            self::CANCELLED => 'Anulowane',
        ];
    }
}