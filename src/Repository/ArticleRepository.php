<?php

namespace App\Repository;

use App\Repository\base\BaseRepository;

/**
 * AdvertRepository.
 */
class ArticleRepository extends BaseRepository
{
    public function getArticlesWithComment()
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->orderBy('a.createAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
