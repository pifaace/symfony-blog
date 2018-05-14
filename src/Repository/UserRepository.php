<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * UserRepository.
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getByValidToken($token)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.resetPasswordToken = :token');
        $qb->setParameter(':token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
