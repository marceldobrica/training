<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\User;
use App\Repository\ProgrammeRepository;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ProgrammeDtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private Security $security;

    private RoomRepository $roomRepository;

    private ProgrammeRepository $programmeRepository;

    public function __construct(
        Security $security,
        RoomRepository $roomRepository,
        ProgrammeRepository $programmeRepository
    ) {
        $this->security = $security;
        $this->roomRepository = $roomRepository;
        $this->programmeRepository = $programmeRepository;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === ProgrammeDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        if (!$this->security->isGranted('ROLE_TRAINER')) {
            throw new AccessDeniedException('Programmes might be created only by trainers or admins');
        }
        /** @var  User */
        $currentUser = $this->security->getUser();
        $data = $request->getContent();
        $decodedData = \json_decode($data, true);
        $programeeDto = new ProgrammeDto();
        $programeeDto->name = $decodedData['name'];
        $programeeDto->description = $decodedData['description'];
        $programeeDto->startDate = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['startDate']);
        $programeeDto->endDate = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['endDate']);
        $programeeDto->isOnline = $decodedData['isOnline'];
        $programeeDto->customers = new ArrayCollection();
        $programeeDto->maxParticipants = $decodedData['maxParticipants'];
        $programeeDto->trainer = null;
        if (
            empty(
                $this->programmeRepository->isUserOcupiedAsTrainer(
                    $programeeDto->startDate,
                    $programeeDto->endDate,
                    $currentUser->getId()
                )
            ) &&
            empty(
                $this->programmeRepository->isUserOcupiedAsCustomer(
                    $programeeDto->startDate,
                    $programeeDto->endDate,
                    $currentUser->getId()
                )
            )
        ) {
            $programeeDto->trainer = $currentUser;
        }

        $programeeDto->room = $this->roomRepository->getRoomForProgramme(
            $programeeDto->startDate,
            $programeeDto->endDate,
            $programeeDto->isOnline,
            $programeeDto->maxParticipants
        );

        yield $programeeDto;
    }
}
