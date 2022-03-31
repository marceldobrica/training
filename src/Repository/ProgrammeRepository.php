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

    public function findAllPaginated($currentPage, $articlesOnPage): array
    {
        $currentPosition = ($currentPage - 1) * $articlesOnPage;
        $query = $this->_em
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->getQuery()
            ->setFirstResult($currentPosition)
            ->setMaxResults($articlesOnPage);

        return $query->getResult();
    }
}
