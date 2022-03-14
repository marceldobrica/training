<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route (path="/api/user")
 */
class UserController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
        $errorsDto = $this->validator->validate($userDto);
        if (count($errorsDto) > 0) {
            return $this->returnValidationErrors($errorsDto);
        }

        $this->logger->info('An userDto was validated');

        $user = User::createFromDto($userDto);
        $errorsUser = $this->validator->validate($user);

        if (count($errorsUser) > 0) {
            return $this->returnValidationErrors($errorsUser);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);

        $this->logger->info('An user was registered and saved in DB');

        $savedUserDto = UserDto::createFromUser($user);


        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
    }
}
