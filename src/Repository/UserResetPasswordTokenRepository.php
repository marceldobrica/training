<?php

namespace App\Repository;

use App\Entity\UserResetPasswordToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserResetPasswordToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserResetPasswordToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserResetPasswordToken[]    findAll()
 * @method UserResetPasswordToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserResetPasswordTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResetPasswordToken::class);
    }

    public function add(UserResetPasswordToken $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(UserResetPasswordToken $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
