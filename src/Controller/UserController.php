<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route (path="/api/user")
 */
class UserController
{
    use ReturnValidationErrorsTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(UserDto $userDto): Response
    {
        $errorDto = $this->validator->validate($userDto);
        if (count($errorDto) > 0) {
            return $this->returnValidationErrors($errorDto);
        }

        $user = User::createFromDto($userDto);
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return $this->returnValidationErrors($errors);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $savedUserDto = UserDto::createFromUser($user);


        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
    }
}
