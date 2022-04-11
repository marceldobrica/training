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
    /**
     * @Route("/admin", name="app_admin")
     */
    public function showUserAction(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('admin/main_page/index.html.twig', []);
    }
}
