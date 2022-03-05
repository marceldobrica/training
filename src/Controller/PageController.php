<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(): Response
    {
        return new Response('Some response');
    }

    /**
     * @Route("/cars", methods={"POST"})
     */
    public function cars(Request $request): Response
    {
        return new JsonResponse($request->getContent(), Response::HTTP_ACCEPTED, []);
    }
}
