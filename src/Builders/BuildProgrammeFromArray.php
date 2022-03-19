<?php

declare(strict_types=1);

namespace App\Builders;

use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BuildProgrammeFromArray
{
    use LoggerAwareTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws \Exception
     */
    public function build(array $programmeArray): ?Programme
    {
        $programme = new Programme();
        $programme->name = $programmeArray['name'];
        $programme->description = $programmeArray['description'];
        $programme->setStartDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']));
        $programme->setEndDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['endDate']));
        $programme->isOnline = $programmeArray['isOnline'];
        $programme->maxParticipants = $programmeArray['maxParticipants'];

        $programme->setTrainer($this->getTrainerForProgram(0));

        if (!$this->getRoomForProgram()) {
            $message = 'Not able to assign a room to programme';
            $this->logger->warning($message, ['program' => json_encode($programmeArray)]);

            throw new \Exception($message);
        }

        $programme->setRoom($this->getRoomForProgram());

        $errors = $this->validator->validate($programme);

        if (count($errors) > 0) {
            $message = 'Not valid programme entry';
            $this->logger->warning($message, ['program' => json_encode($programmeArray)]);

            throw new \Exception($message);
        }

        return $programme;
    }

    private function getRoomForProgram(): ?Room
    {
        //TODO - correct code ... return null if no room available...

//        $vector = $this->programmeRepository->findOcupiedRooms(
//            \DateTime::createFromFormat('d.m.Y H:i', '15.03.2022'),
//            \DateTime::createFromFormat('d.m.Y H:i', '15.03.2022')
//        );

        return $this->entityManager->getRepository(Room::class)->find(1);
    }

    private function getTrainerForProgram(int $trainerId): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($trainerId);
    }
}
