<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PasswordResetRequestType extends AbstractType
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
                ],
            ]);
    }
}