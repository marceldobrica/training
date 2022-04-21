<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasicAuthController
{
    /**
     * @Route("/api/basic", methods={"GET"})
     */
    public function verifyBasicAuthAccessAction(): Response
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller visible if you are authentificated with Basic Auth!'
        ]);
    }
}
