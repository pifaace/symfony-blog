<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getByValidToken($token): ?User
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.resetPasswordToken = :token');
        $qb->setParameter(':token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getByProviderId($providerId): ?User
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where('u.providerId = :providerId')
            ->setParameter(':providerId', $providerId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function countUsers(): string
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
