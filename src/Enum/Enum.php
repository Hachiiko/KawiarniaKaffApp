<?php

declare(strict_types=1);

namespace App\Enum;

use App\Exception\MissingEnumLabelException;
use MyCLabs\Enum\Enum as BaseEnum;

abstract class Enum extends BaseEnum implements EnumInterface
{
    public function getKey(): string
    {
        // Re-declaration allows the usage of "string" return typehint to be interface compatible.

        return parent::getKey();
    }

    /**
     * @return string
     * @throws MissingEnumLabelException
     */
    public function getLabel(): string
    {
        $labels = static::getLabels();
        $value = $this->getValue();

        if (array_key_exists($value, $labels)) {
            return $labels[$value];
        }

        throw new MissingEnumLabelException($this);
    }
}