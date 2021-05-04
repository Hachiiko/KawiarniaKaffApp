<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * @method static OrderStatusEnum NEW()
 * @method static OrderStatusEnum CONFIRMED()
 * @method static OrderStatusEnum READY()
 * @method static OrderStatusEnum CANCELLED()
 * @method static OrderStatusEnum COMPLETE()
 */
class OrderStatusEnum extends Enum
{
    private const NEW = 'new';
    private const CONFIRMED = 'confirmed';
    private const READY = 'ready';
    private const CANCELLED = 'cancelled';
    private const COMPLETE = 'complete';

    public static function getLabels(): array
    {
        return [
            self::NEW => 'Nowe',
            self::CONFIRMED => 'Potwierdzone',
            self::READY => 'Gotowe do odebrania',
            self::CANCELLED => 'Anulowane',
            self::COMPLETE => 'Ukończone',
        ];
    }
}