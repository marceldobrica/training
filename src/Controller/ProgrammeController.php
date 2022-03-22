<?php

namespace App\Controller;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route (path="/api/programme")
 */
class ProgrammeController
{
    use ReturnValidationErrorsTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    private ProgrammeRepository $programmeRepository;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
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

        $this->programmeRepository->add($programme);
        $savedProgrammeDto = ProgrammeDto::createFromProgramme($programme);

        return new JsonResponse($savedProgrammeDto, Response::HTTP_CREATED);
    }

    /**
     * @Route(methods={"GET"})
     */
    public function show(): Response
    {
        //TODO ask more about format in postman... if no groups a lot of magic ... __cloner__ __isInitialized__ ??

        $serializedProgrammes = $this->serializer->serialize(
            $this->programmeRepository->findAll(),
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($serializedProgrammes, Response::HTTP_OK);
    }
}
