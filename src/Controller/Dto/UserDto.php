<?php

namespace App\Controller\Dto;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as MyAssert;

class UserDto
{
    public int $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Z][a-z]+$/")
     */
    public string $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Z][a-z]+$/")
     */
    public string $lastName;

    /**
     * @Assert\Email
     */
    public string $email;

    /**
     * @MyAssert\Password
     */
    public string $password;

    /**
     * @Assert\IdenticalTo(propertyPath="password", message="Entered passwords are not identical")
     */
    public string $confirmedPassword;

    public string $cnp;

    public array $roles = [];

    public static function createFromUser(User $user): self
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->password = $user->getPassword();
        $dto->firstName = $user->firstName;
        $dto->lastName = $user->lastName;
        $dto->cnp = $user->cnp;
        $dto->email = $user->email;
        $dto->roles = $user->getRoles();

        return $dto;
    }
}
