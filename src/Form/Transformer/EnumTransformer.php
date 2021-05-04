<?php

declare(strict_types=1);

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class EnumTransformer implements DataTransformerInterface
{
    private string $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function transform($value): string
    {
        return (string) $value;
    }

    public function reverseTransform($value)
    {
        return new $this->enumClass($value);
    }
}