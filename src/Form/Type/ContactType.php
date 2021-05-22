<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'help' => 'Twoje dane nie zostaną nigdy opublikowane.',
                'attr' => [
                    'placeholder' => 'Twój e-mail',
                    'maxlength' => 64,
                ],
                'constraints' => [
                    new Constraints\Email(),
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 2, max: 64),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Treść',
                'attr' => [
                    'placeholder' => 'Wpisz tutaj swoją wiadomość',
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(min: 2),
                ],
            ]);
    }
}