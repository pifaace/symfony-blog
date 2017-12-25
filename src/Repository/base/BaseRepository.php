<?php

namespace App\Repository\base;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BaseRepository extends EntityRepository
{

    /**
     * List all object with paginator
     *
     * @param int $page
     * @param int $maxResults
     * @return Paginator
     */
    public function paginator($page, int $maxResults)
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->setFirstResult(($page - 1) * $maxResults)
            ->setMaxResults($maxResults);

        $paginator = new Paginator($qb);

        return $paginator;
    }
}
