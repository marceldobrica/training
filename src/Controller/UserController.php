<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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

    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordHasher;

    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
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
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
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

    /**
     * @Route(path="/{id}", methods={"DELETE"})
     */
    public function deleteUserAction($id): Response
    {
        $user = $this->userRepository->findUserById($id);

        if (null === $user) {
            return new JsonResponse('No user found', Response::HTTP_BAD_REQUEST);
        }
        $this->userRepository->remove($user);
        $this->userRepository->add($user);
        $this->logger->info('An user was soft-deleted');

        return new JsonResponse(['User with id' . $id . ' was soft-deleted.'], Response::HTTP_OK);
    }

    /**
     * @Route(path="/recover", methods={"POST"})
     */
    public function unDeleteUserAction(Request $request): Response
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        if (!isset($decodedData['email'])) {
            $this->logger->warning('An atempt to post on recover without email');

            return new JsonResponse(
                'You should provide an email',
                Response::HTTP_BAD_REQUEST
            );
        }
        $this->entityManager->getFilters()->disable('softdeleteable');
        $user = $this->userRepository->findOneBy(['email' => $decodedData['email']]);
        if (null === $user) {
            $this->logger->warning('No user for recover', ['email' => $decodedData['email']]);

            return new JsonResponse(
                'No user with provided email',
                Response::HTTP_BAD_REQUEST
            );
        }
        $user->setDeletedAt(null);
        $this->userRepository->add($user);
        $this->logger->info('An user was recovered', ['user' => $user]);
        $this->entityManager->getFilters()->enable('softdeleteable');

        return new JsonResponse(
            'User with email ' . $decodedData['email'] . ' was un-deleted.',
            Response::HTTP_OK
        );
    }

//
//    /**
//     * @Route(path="/{id}" methods={"PUT"})
//     */
//    public function update($id): Response
//    {
//        $user = $this->userRepository->findUserById($id);
//
//        if ($user) {
//            $this->userRepository->remove($user);
//            $this->logger->info('An user was deleted and removed from DB');
//            return new JsonResponse();
//        }
//
//        $savedUserDto = UserDto::createFromUser($user);
//
//        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
//    }
}
