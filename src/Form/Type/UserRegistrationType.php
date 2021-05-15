<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineConstraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Twój e-mail',
                    'maxlength' => 64,
                ],
                'constraints' => [
                    new Constraints\Email(),
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 2, max: 64),
                    new DoctrineConstraints\UniqueEntity(fields: ['email'], entityClass: User::class),
                ],
            ])
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
}