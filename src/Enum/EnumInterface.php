<?php

declare(strict_types=1);

namespace App\Enum;

interface EnumInterface
{
    /**
     * Retrieves an array of all available enum labels.
     *
     * @return array
     */
    public static function getLabels(): array;

    /**
     * Retrieves a label for the current enum value.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return string
     */
    public function getKey(): string;
}
