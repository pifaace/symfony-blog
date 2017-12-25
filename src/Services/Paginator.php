<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    private $itemPerPage;

    private $requestStack;

    public function __construct(RequestStack $requestStack, $itemPerPage)
    {
        $this->itemPerPage = $itemPerPage;
        $this->requestStack = $requestStack;
    }

    public function getItemList($repository, $page)
    {
        $items = $repository->paginator($page, $this->itemPerPage);

        return $items;
    }

    public function countPage($items)
    {
        $nbPage = ceil(count($items) / $this->itemPerPage);
        return $nbPage;
    }

    public function getPage()
    {
        $request = $this->requestStack->getCurrentRequest();

        $page = $request->query->get('page');

        if ($page < 1) {
            $page = 1;
        }

        return $page;
    }
}
