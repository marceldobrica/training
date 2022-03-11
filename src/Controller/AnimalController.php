<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController
{
    /**
     * @Route("/animals", name="animals", methods={"GET"})
     */
    public function getAnimal(Request $request): Response
    {
        return new Response('Wooof wooof, im a dog', RESPONSE::HTTP_OK, []);
    }
}