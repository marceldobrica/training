<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllPaginated($currentPage, $pageSize): array
    {
        $currentPosition = ($currentPage - 1) * $pageSize;
        $query = $this->_em
            ->createQueryBuilder()
            ->select('u')
            ->from('App:User', 'u')
            ->getQuery()
            ->setFirstResult($currentPosition)
            ->setMaxResults($pageSize);

        return $query->getResult();
    }

    public function countUser(): int
    {
        $query = $this->_em
            ->createQueryBuilder()
            ->select('count(u.id)')
            ->from('App:User', 'u')
            ->getQuery();

        return intval($query->getScalarResult()[0][1]);
    }
}
