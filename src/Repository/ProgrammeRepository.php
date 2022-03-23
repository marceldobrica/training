<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

    public function add(Programme $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Programme $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOcupiedRooms($startDate, $endDate): array
    {
        $a = $this->createQueryBuilder('p')
            ->innerJoin('p.room', 'r')
            ->Where('p.startDate < :startdate AND p.endDate > :startdate')
            ->orWhere('p.startDate < :enddate AND p.endDate > :enddate')
            ->setParameter(':startdate', $startDate)
            ->setParameter(':enddate', $endDate)
            ->getQuery();

        var_dump($a->getSQL());

        return $a->getResult();
    }
}
