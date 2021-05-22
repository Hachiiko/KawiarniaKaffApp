<?php

declare(strict_types=1);

namespace App\Form\Data;

use App\Entity\User;

class UserEditData
{
    public string $email;
    public string $firstName;
    public string $lastName;
    public string $phone;

    public static function createFromUser(User $user): self
    {
        $self = new self();

        $self->email = $user->getEmail() ?? '';
        $self->firstName = $user->getFirstName() ?? '';
        $self->lastName = $user->getLastName() ?? '';
        $self->phone = $user->getPhone() ?? '';

        return $self;
    }
}