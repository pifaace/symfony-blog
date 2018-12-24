<?php

namespace App\Repository;

use App\Entity\ChildComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChildComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChildComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChildComment[]    findAll()
 * @method ChildComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChildComment::class);
    }

    // /**
    //  * @return ChildComment[] Returns an array of ChildComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChildComment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
