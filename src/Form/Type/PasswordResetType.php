<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Podane hasła muszą być takie same.',
                'first_options'  => [
                    'label' => 'Hasło',
                    'attr' => [
                        'placeholder' => 'Twoje hasło',
                        'maxlength' => 64,
                    ],
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło',
                    'attr' => [
                        'placeholder' => 'Twoje hasło ponownie',
                        'maxlength' => 64,
                    ],
                ],
                'constraints' => [
                    new Constraints\Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\x21-\x7E][^a-zA-Z])[\x21-\x7E]{8,}$/',
                        message: 'Hasło musi składać sie z 8 znaków, w tym co najmniej jednej dużej litery, co najmniej jednej małej oraz co najmniej jednego znaku specjalnego lub liczby.',
                    ),
                ],
            ]);
    }
}