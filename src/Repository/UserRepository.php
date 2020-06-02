<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getByValidToken(string $token): ?User
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.resetPasswordToken = :token');
        $qb->setParameter(':token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getByProviderId(string $providerId): ?User
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

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function saveNewPassword()
    {
        $this->_em->flush();
    }
}
