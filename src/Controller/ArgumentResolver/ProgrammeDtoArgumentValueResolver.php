<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\ProgrammeDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ProgrammeDtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === ProgrammeDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        $programmeDto = new ProgrammeDto();
        $programmeDto->name = $decodedData['name'];
        $programmeDto->description = $decodedData['description'];
        $programmeDto->startDate = new \DateTime($decodedData['startDate']);
        $programmeDto->endDate = new \DateTime($decodedData['endDate']);
        $programmeDto->isOnline = $decodedData['isOnline'];

        yield $programmeDto;
    }
}
