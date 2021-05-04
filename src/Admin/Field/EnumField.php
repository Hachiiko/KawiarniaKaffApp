<?php

declare(strict_types=1);

namespace App\Admin\Field;

use App\Enum\EnumInterface;
use App\Form\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EnumField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('crud/field/enum.html.twig')
            ->setFormType(EnumType::class)
            ->formatValue(static function (EnumInterface $enum) {
                return $enum->getLabel();
            });
    }

    public function setEnumClass(string $class): self
    {
        if (!is_a($class, EnumInterface::class, true)) {
            throw new UnexpectedTypeException($class, EnumInterface::class);
        }

        $this->setFormTypeOption(EnumType::OPTION_CLASS, $class);
        $this->setFormTypeOption(EnumType::OPTION_CHOICES, array_flip($class::getLabels()));

        return $this;
    }
}