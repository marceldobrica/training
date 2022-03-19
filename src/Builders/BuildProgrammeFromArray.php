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
        $startDate = \DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']);
        $programme->setStartDate($startDate);
        $endDate = \DateTime::createFromFormat('d.m.Y H:i', $programmeArray['endDate']);
        $programme->setEndDate($endDate);
        $programme->isOnline = $programmeArray['isOnline'];
        $programme->maxParticipants = $programmeArray['maxParticipants'];

        $programme->setTrainer(null);

        $room = $this->getRoomForProgram($startDate, $endDate, $programmeArray['isOnline']);

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

    private function getRoomForProgram(\DateTime $startDate, \DateTime $endDate, bool $isOnline): ?Room
    {
        //TODO - refactor to use QueryBuilder
        //TODO - add column programme to room for better management and to be able to view programmes on room...
        //TODO - ... alte verificari... date in trecut etc... de preluat din feature csv...

        $dql = "SELECT DISTINCT r.id FROM App\Entity\Programme p LEFT JOIN p.room r " .
            "where ((p.startDate <= :startDate and :startDate <= p.endDate) " .
                "OR (p.startDate <= :endDate and :endDate <= p.endDate)" .
                "OR (:startDate <= p.startDate and p.endDate <= :endDate)" .
                "OR (p.startDate <= :startDate and :endDate <= p.endDate))" .
            "AND (p.isOnline = :isOnline) AND (p.maxParticipants <= r.capacity)";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);
        $query->setParameter('isOnline', $isOnline);

        $ocupiedRoomsId = $query->getResult();

        $occupiedForQuery = $this->getOcupiedForQuery($ocupiedRoomsId);

        $dqlOnline = "SELECT r FROM App\Entity\Room r LEFT JOIN r.building b " .
            "where (r.building is null) " .
            "AND r.id NOT IN (:occupiedRooms)";
        $dqlNotOnline = "SELECT r FROM App\Entity\Room r LEFT JOIN r.building b " .
            "where (r.building is not null) " .
            "AND r.id NOT IN (:occupiedRooms)";

        $dql = $isOnline ? $dqlOnline : $dqlNotOnline;
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('occupiedRooms', $occupiedForQuery);

        $availableRooms = $query->getResult();

        return array_shift($availableRooms);
    }

    private function getOcupiedForQuery(array $ocupiedRoomsId): string
    {
        $notAvailable = [];
        foreach ($ocupiedRoomsId as $item) {
            $notAvailable[] = $item['id'];
        }

        return implode(',', $notAvailable);
    }
}
