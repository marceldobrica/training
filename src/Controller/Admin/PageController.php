<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class PageController extends AbstractController
{
    /**
     * @Route(name="admin", methods={"GET"})
     */
    public function showUserAction(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('admin/main_page/index.html.twig');
    }
}
