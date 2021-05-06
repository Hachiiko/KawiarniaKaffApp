<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(string $email, string $plainPassword, string $firstName, string $lastName, string $phone): User
    {
        $user = new User();

        $user->setEmail($email);
        $user->setUsername($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhone($phone);

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $plainPassword)
        );

        return $user;
    }
}