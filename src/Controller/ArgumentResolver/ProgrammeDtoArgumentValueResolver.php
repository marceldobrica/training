<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\Room;
use App\Entity\User;
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

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ProgrammeDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        $programeeDto = new ProgrammeDto();
        $programeeDto->name = $decodedData['name'];
        $programeeDto->description = $decodedData['description'];
        $programeeDto->startDate = new \DateTime($decodedData['startDate']);
        $programeeDto->endDate = new \DateTime($decodedData['endDate']);
        $roomRepository = $this->entityManager->getRepository(Room::class);
        $room = $roomRepository->find($decodedData['room']);
        $programeeDto->room = $room;
        $userRepository = $this->entityManager->getRepository(User::class);
        $trainer = $userRepository->find($decodedData['trainer']);
        $programeeDto->trainer = $trainer;
        $programeeDto->isOnline = $decodedData['isOnline'];
//      $programeeDto->customers = $decodedData['customers']; //Collection ?? json ???

        yield $programeeDto;
    }
}
