<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * @method static CartStatusEnum NEW()
 * @method static CartStatusEnum RESOLVED()
 */
class CartStatusEnum extends Enum
{
    private const NEW = 'new';
    private const RESOLVED = 'resolved';

    public static function getLabels(): array
    {
        return [
            self::NEW => 'Nowy',
            self::RESOLVED => 'Przetworzony w zamówienie',
        ];
    }
}