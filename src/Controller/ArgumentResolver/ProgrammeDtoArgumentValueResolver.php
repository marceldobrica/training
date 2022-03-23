<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\ProgrammeDto;
use Doctrine\Common\Collections\ArrayCollection;
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
        $programeeDto = new ProgrammeDto();
        $programeeDto->name = $decodedData['name'];
        $programeeDto->description = $decodedData['description'];
        $programeeDto->startDate = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['startDate']);
        $programeeDto->endDate = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['endDate']);
        $programeeDto->isOnline = $decodedData['isOnline'];
        $programeeDto->customers = new ArrayCollection();
        $programeeDto->maxParticipants = $decodedData['maxParticipants'];
//        if (isset($decodedData['trainer_id'])) {
//            $programeeDto->trainer = $this->saveProgramme->resolveTrainer($decodedData['trainer_id']);
//        } else {
//            $programeeDto->trainer = $this->saveProgramme->resolveTrainer(null);
//        }
//        $programeeDto->room = $this->saveProgramme->resolveRoom();

        yield $programeeDto;
    }
}
