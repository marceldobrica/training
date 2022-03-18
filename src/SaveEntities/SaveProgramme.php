<?php

declare(strict_types=1);

namespace App\SaveEntities;

use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SaveProgramme implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function saveProgrammeFromArray(array $programmeArray): void
    {
        $programme = new Programme();
        $programme->name = $programmeArray['name'];
        $programme->description = $programmeArray['description'];
        $programme->setStartDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']));
        $programme->setEndDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['endDate']));
        $programme->isOnline = $programmeArray['isOnline'];
        $programme->maxParticipants = $programmeArray['maxParticipants'];
        $programme->setTrainer($this->resolveTrainer(null));
        if ($this->resolveRoom()) {
            $programme->setRoom($this->resolveRoom());
        } else {
            $this->logger->warning(
                'Not able to assign a room to programme',
                ['program' => json_encode($programmeArray)]
            );
        }

        $errors = $this->validator->validate($programme);

        if (count($errors) > 0) {
            $this->logger->warning(
                'Not valid programme entry',
                ['program' => json_encode($programmeArray)]
            );
        }

        $this->entityManager->persist($programme);
        $this->entityManager->flush();
    }

    public function getRoomForProgram(): ?Room
    {
        //TODO - correct code ... return null if no room available...
        return $this->entityManager->getRepository(Room::class)->find(1);
    }

    public function getTrainerForProgram(?int $trainer_id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($trainer_id);
    }
}
