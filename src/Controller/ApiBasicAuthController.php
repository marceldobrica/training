<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiBasicAuthController
{
    /**
     * @Route("/api/basic", methods={"GET"})
     */
    public function index(): Response
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller visible if you are authentificated with Basic Auth!'
        ]);
    }
}
