<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PageController extends AbstractController
{
    private UserRepository $userRepository;

    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function showUserAction(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->security->getUser();

        return $this->render('admin/admin_page/index.html.twig', [
            'user' => $user,
        ]);
    }
}
