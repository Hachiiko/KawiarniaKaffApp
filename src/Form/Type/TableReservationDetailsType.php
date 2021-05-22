<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Data\TableReservationDetailsData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class TableReservationDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * Example 'available_hours' value:
         *
         * [
         *   "01/01/2021": [ 9, 10, 11, 12 ],
         *   "02/01/2021": [ 9, 10, 11, 12 ],
         * ]
         */

        $availableHours = $options['available_hours'];

        $builder
            ->add('day', ChoiceType::class, [
                'label' => 'Dzień',
                'choices' => array_combine(
                    array_keys($availableHours),
                    array_keys($availableHours),
                ),
            ])
            ->add('hour', ChoiceType::class, [
                'label' => 'Godzina',
                'attr' => [
                    'data-available-hours' => json_encode($options['available_hours']),
                ],
                'choices' => array_combine(
                    array_map(fn (int $hour) => sprintf('%s:00', $hour), current($availableHours)),
                    current($availableHours)
                )
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'attr' => [
                    'placeholder' => 'Twoje imię',
                    'maxlength' => 64,
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 2, max: 64),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => [
                    'placeholder' => 'Twoje nazwisko',
                    'maxlength' => 64,
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 2, max: 64),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numer telefonu',
                'attr' => [
                    'placeholder' => '123 456 789',
                    'maxlength' => 16,
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 4, max: 16),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class', TableReservationDetailsData::class)
            ->setRequired('available_hours')
            ->setAllowedTypes('available_hours', 'array');
    }
}