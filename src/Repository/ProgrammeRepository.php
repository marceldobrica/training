<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Programme;
use App\Entity\User;
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

    public function findAll(): array
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->getQuery()
            ->getResult();
    }

    public function showAllPaginatedSortedFiltered(
        array $pager,
        array $filters,
        ?string $sorter,
        ?string $direction
    ): array {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->setFirstResult($pager['size'] * ($pager['page'] - 1))
            ->setMaxResults($pager['size']);

        foreach ($filters as $key => $value) {
            if (null !== $value) {
                $query = $query->where("p.$key = :$key");
                $query->setParameter(":$key", $value);
            }
        }

        if (null !== $sorter) {
            $direction = $direction ?? 'ASC';
            $direction = strtoupper($direction);
            if (!in_array($direction, ['ASC', 'DESC'])) {
                $direction = 'ASC';
            }
            $query = $query->orderBy("p.$sorter", $direction);
        }

        return $query->getQuery()->getResult();
    }

    public function findAllPaginated(int $currentPage, int $articlesOnPage): array
    {
        $currentPosition = ($currentPage - 1) * $articlesOnPage;
        $query = $this->_em
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->getQuery();

        return $query->setFirstResult($currentPosition)->setMaxResults($articlesOnPage)->getResult();
    }

    public function countProgrammes(): int
    {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('count(p.id)')
            ->from('App:Programme', 'p')
            ->getQuery();

        return (int)$query->getSingleScalarResult();
    }

    public function removeTrainerWithIdFromProgrammes(int $id): void
    {
        $this->_em
            ->createQueryBuilder()
            ->update('App:Programme', 'p')
            ->set('p.trainer', 'NULL')
            ->where('p.trainer = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function returnBusiestDate(): array
    {
        $conn = $this->_em->getConnection();
        $sql = '
                SELECT 
                       src.day, 
                       src.hour, 
                       src.participants
                FROM
                    (SELECT 
                        DATE(p.start_date) as day,
                        HOUR(p.start_date) as hour,
                        count(pc.user_id)  as participants,
                        RANK() OVER (
                            PARTITION BY DATE(p.start_date) ORDER BY COUNT(pc.user_id) DESC
                            ) as position
                    FROM programme p
                        LEFT JOIN programmes_customers pc ON p.id = pc.programme_id
                    GROUP BY day, hour) as src
                WHERE src.position = 1
                AND src.participants > 0
                ORDER BY src.participants DESC
                LIMIT 5
                ';
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function isUserOcupiedAsTrainer(\DateTime $startDate, \DateTime $endDate, int $userId): array
    {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('pt.id as trainerid, p.id as programmeid')
            ->from('App:Programme', 'p')
            ->join('p.trainer', 'pt')
            ->where('p.startDate <= :startDate and :startDate < p.endDate')
            ->orWhere('p.startDate < :endDate and :endDate <= p.endDate')
            ->orWhere(':startDate  <= p.startDate and p.endDate <= :endDate')
            ->andWhere('pt.id = :id')
            ->setParameter(':startDate', $startDate)
            ->setParameter(':endDate', $endDate)
            ->setParameter(':id', $userId)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function isUserOcupiedAsCustomer(\DateTime $startDate, \DateTime $endDate, int $userId): array
    {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('pc.id as customerid, p.id as programmeid')
            ->from('App:Programme', 'p')
            ->join('p.customers', 'pc')
            ->where('p.startDate <= :startDate and :startDate < p.endDate')
            ->orWhere('p.startDate < :endDate and :endDate <= p.endDate')
            ->orWhere(':startDate  <= p.startDate and p.endDate <= :endDate')
            ->andWhere('pc.id = :id')
            ->setParameter(':startDate', $startDate)
            ->setParameter(':endDate', $endDate)
            ->setParameter(':id', $userId)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function isRoomOccupied(\DateTime $startDate, \DateTime $endDate, int $roomId): array
    {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('pr.id as roomid, p.id as programmeid')
            ->from('App:Programme', 'p')
            ->join('p.room', 'pr')
            ->where('p.startDate <= :startDate and :startDate < p.endDate')
            ->orWhere('p.startDate < :endDate and :endDate <= p.endDate')
            ->orWhere(':startDate  <= p.startDate and p.endDate <= :endDate')
            ->andWhere('pr.id = :roomid')
            ->setParameter(':startDate', $startDate)
            ->setParameter(':endDate', $endDate)
            ->setParameter(':roomid', $roomId)
            ->getQuery();

        return $query->getArrayResult();
    }
}
