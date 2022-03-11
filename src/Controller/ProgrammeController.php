<?php

namespace App\Controller;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route (path="/api/programme")
 */
class ProgrammeController
{
    use ReturnValidationErrorsTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(ProgrammeDto $programmeDto): Response
    {
        $programme = Programme::createFromDto($programmeDto);

        $errors = $this->validator->validate($programme);

        if (count($errors) > 0) {
            return $this->returnValidationErrors($errors);
        }

        $this->entityManager->persist($programme);
        $this->entityManager->flush();
        $this->entityManager->refresh($programme);
        $savedProgrammeDto = ProgrammeDto::createFromProgramme($programme);

        return new JsonResponse($savedProgrammeDto, Response::HTTP_CREATED);
    }
}
