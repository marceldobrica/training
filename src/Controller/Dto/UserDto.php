<?php

namespace App\Controller\Dto;

use App\Entity\User;

class UserDto
{
    public int $id;

    public string $firstName;

    public string $lastName;

    public string $email;

    public string $password;

    public string $confirmedPassword;

    public string $cnp;

    public static function createFromUser(User $user): self
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->password = $user->getPassword();
        $dto->firstName = $user->firstName;
        $dto->lastName = $user->lastName;
        $dto->cnp = $user->cnp;
        $dto->email = $user->email;

        return $dto;
    }
}