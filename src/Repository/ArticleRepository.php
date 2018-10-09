<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * List all object with paginator.
     */
    public function paginator(int $page, int $maxResults): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->setFirstResult(($page - 1) * $maxResults)
            ->setMaxResults($maxResults)
            ->orderBy('p.createAt', 'DESC');

        return new Paginator($qb);
    }

    public function getArticlesWithComment(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->orderBy('a.createAt', 'DESC')
            ->getQuery()->getResult();
    }

    public function countArticles(): string
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
