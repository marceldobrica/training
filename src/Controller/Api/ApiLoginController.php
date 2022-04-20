<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

/**
 * @Route ("/api")
 */
class ApiLoginController extends AbstractController
{
    private Security $security;

    private UserRepository $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function loginAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = Uuid::v4();
        $user->setToken($token);
        $this->userRepository->add($user);

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
