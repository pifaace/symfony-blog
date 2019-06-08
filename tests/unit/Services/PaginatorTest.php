<?php

namespace App\Tests\Services;

use App\Entity\Article;
use App\Services\Paginator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorTest extends TestCase
{
    public function testCountPage()
    {
        $articleArray = [
            $this->createArticle(),
            $this->createArticle(),
            $this->createArticle(),
            $this->createArticle(),
            $this->createArticle(),
            $this->createArticle(),
        ];

        $this->assertEquals($this->getMockedPaginator()->countPage($articleArray), '2');
    }

    private function getMockedPaginator()
    {
        $requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new Paginator($requestStack, '5');
    }

    private function createArticle()
    {
        $article = new Article();

        return $article;
    }
}
