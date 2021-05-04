<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Transformer\EnumTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    public const OPTION_CLASS = 'class';
    public const OPTION_CHOICES = 'choices';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->resetViewTransformers()
            ->addViewTransformer(new EnumTransformer($options[self::OPTION_CLASS]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::OPTION_CLASS)
            ->addAllowedTypes(self::OPTION_CLASS, 'string');
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}