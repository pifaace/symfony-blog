<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    /**
     * @var int
     */
    private $itemPerPage;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack, $itemPerPage)
    {
        $this->itemPerPage = $itemPerPage;
        $this->requestStack = $requestStack;
    }

    public function getItemList($repository, $page)
    {
        return $repository->paginator($page, $this->itemPerPage);
    }

    public function countPage($items): int
    {
        return ceil(\count($items) / $this->itemPerPage);
    }

    public function getPage(): int
    {
        $request = $this->requestStack->getCurrentRequest();

        $page = $request->query->get('page');

        if ($page < 1) {
            $page = 1;
        }

        return $page;
    }
}
