<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminPageController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $this->render('admin/admin_page/index.html.twig', [
            'user' => $user,
        ]);
    }
}
