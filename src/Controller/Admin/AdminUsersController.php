<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/users", name="app_admin_users")
     */
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/admin_users/index.html.twig', [
            'users' => $users
        ]);
    }
}
