<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();

            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword($this->passwordEncoder->encodePassword($user, '123'));

            $manager->persist($user);
        }

        $user = new User();

        $user->setUsername('admin');
        $user->setEmail('admin@example.com');
        $user->addRole(User::ROLE_ADMIN);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123'));

        $manager->persist($user);
        $manager->flush();
    }
}
