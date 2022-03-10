<?php

namespace App\Controller;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (path="/api/programme")
 */
class ProgrammeController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(ProgrammeDto $programmeDto): Response
    {
        $programme = Programme::createFromDto($programmeDto); //never receive customers collection in request...
        $this->entityManager->persist($programme);
        $this->entityManager->flush();
        $this->entityManager->refresh($programme);
        $savedProgrammeDto = ProgrammeDto::createFromProgramme($programme);

        return new JsonResponse($savedProgrammeDto, Response::HTTP_CREATED);
    }
}
