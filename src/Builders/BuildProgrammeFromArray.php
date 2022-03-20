<?php

declare(strict_types=1);

namespace App\Builders;

use App\Entity\Programme;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BuildProgrammeFromArray implements LoggerAwareInterface
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

        $programme->setTrainer(null);

        $room = $this->getRoomForProgram(
            \DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']),
            \DateTime::createFromFormat('d.m.Y H:i', $programmeArray['endDate']),
            $programmeArray['isOnline'],
            $programmeArray['maxParticipants']
        );

        if (!$room) {
            $message = 'Not able to assign a room to programme';
            $this->logger->warning($message, ['program' => json_encode($programmeArray)]);

            throw new \Exception($message);
        }

        $programme->setRoom($room);

        $errors = $this->validator->validate($programme);

        if (count($errors) > 0) {
            $message = 'Not valid programme entry';
            $this->logger->warning($message, ['program' => json_encode($programmeArray)]);

            throw new \Exception($message);
        }

        return $programme;
    }

    private function getRoomForProgram(
        \DateTime $startDate,
        \DateTime $endDate,
        bool $isOnline,
        int $maxParticipants
    ): ?Room {
        //TODO - refactor to use QueryBuilder
        //TODO - add column programme to room for better management and to be able to view programmes on room...
        //TODO - ... alte verificari... date in trecut etc... de preluat din feature csv...

        $dql = "SELECT DISTINCT r.id FROM App\Entity\Programme p LEFT JOIN p.room r " .
            "where ((p.startDate <= :startDate and :startDate <= p.endDate) " .
                "OR (p.startDate <= :endDate and :endDate <= p.endDate)" .
                "OR (:startDate  <= p.startDate and p.endDate <= :endDate))";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);

        $ocupiedRoomsId = $query->getResult();

        $dqlOnline = "SELECT r FROM App\Entity\Room r where (r.building is null)";
        $dqlNotOnline = "SELECT r FROM App\Entity\Room r where (r.building is not null)";

        $dql = $isOnline ? $dqlOnline : $dqlNotOnline;
        $dql = $dql . " AND (r.capacity > :maxParticipants)";
        $occupiedForQuery = $this->getOcupiedForQuery($ocupiedRoomsId);

        if (!empty($ocupiedRoomsId)) {
            $dql = $dql . " AND r.id NOT IN (:occupiedRooms)";
        }
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('maxParticipants', $maxParticipants);

        if (!empty($ocupiedRoomsId)) {
            $query->setParameter('occupiedRooms', $occupiedForQuery);
        }

        var_dump($query->getSQL());

        $availableRooms = $query->getResult();

        return array_shift($availableRooms);
    }

    private function getOcupiedForQuery(array $ocupiedRoomsId): array
    {
        $notAvailable = [];
        foreach ($ocupiedRoomsId as $item) {
            $notAvailable[] = $item['id'];
        }

        return $notAvailable;
    }
}
