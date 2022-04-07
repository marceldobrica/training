<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function add(Room $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Room $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getRoomForProgramme(
        \DateTime $startDate,
        \DateTime $endDate,
        bool $isOnline,
        int $maxParticipants
    ): ?Room {
        $expressionBuilder = $this->getEntityManager()->getExpressionBuilder();
        $notOnline = $isOnline ? '' : 'not ';

        return $this->createQueryBuilder('r')
            ->where("r.building is $notOnline null")
            ->andWhere('r.capacity >= :maxParticipants')
            ->andWhere(
                $expressionBuilder->notIn(
                    'r.id',
                    $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('rp.id')
                        ->from('App:Programme', 'p')
                        ->leftJoin('p.room', 'rp')
                        ->where('p.startDate < :startDate and :startDate <= p.endDate')
                        ->orWhere('p.startDate <= :endDate and :endDate < p.endDate')
                        ->orWhere(':startDate  <= p.startDate and p.endDate <= :endDate')
                        ->getDQL()
                )
            )
            ->setParameter(':maxParticipants', $maxParticipants)
            ->setParameter(':startDate', $startDate)
            ->setParameter(':endDate', $endDate)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
