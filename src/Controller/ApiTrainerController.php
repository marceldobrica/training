<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTrainerController
{
    /**
     * @Route("/api/trainer", methods={"GET"})
     */
    public function verifyTrainerAccessAction(): Response
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller available only for users with ROLE_TRAINER!'
        ]);
    }
}
