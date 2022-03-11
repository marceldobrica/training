<?php

namespace App\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController implements LoggerAwareInterface
{
    protected LoggerInterface $logger;

    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/animals", name="animals", methods={"GET"})
     */
    public function getAnimal(Request $request): Response
    {
        $this->logger->info('An enimal has been get');

        return new Response('Wooof wooof, im a dog', RESPONSE::HTTP_OK, []);
    }


}