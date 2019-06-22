<?php

namespace App\Tests\Functional;

use App\Tests\BaseTestCase;

class ArticleActionTest extends BaseTestCase
{
    public function testCreateAnArticle()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');
        $crawler = $this->loginAs($client, $crawler, 'admin', 'azerty');

        $crawler = $client->click($crawler->selectLink('Dashboard')->link());
        $crawler = $client->click($crawler->selectLink('ARTICLES')->link());
        $crawler = $client->click($crawler->selectLink('New article')->link());

        $form = $crawler->selectButton('Publish')->form();

        $form['App_article[title]'] = 'An awesome new article';
        $form['App_article[content]'] = 'This is an article wrote by panthere';
        $form['app_tag_input'] = 'symfony,panthere,';
        $crawler = $client->submit($form);

        $client->waitFor('.notification');
        $this->assertContains('The article has been successfully created', $crawler->filter('.notification')->text());
    }

    public function testEditAnArticle()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');

        $crawler = $this->loginAs($client, $crawler, 'admin', 'azerty');

        $crawler = $client->click($crawler->selectLink('Dashboard')->link());
        $crawler = $client->click($crawler->selectLink('ARTICLES')->link());

        $crawler = $client->click($crawler->filter('a.is-warning')->eq(3)->link());

        $form = $crawler->selectButton('Publish')->form();

        $form['App_article[title]'] = 'Edited by panthere';

        $crawler = $client->submit($form);

        $client->waitFor('.notification');
        $this->assertContains('The article has been successfully edited', $crawler->filter('.notification')->text());
    }

    // Todo: https://github.com/symfony/panther/issues/203
//    public function testDeleteAnArticle()
//    {
//        $client = static::createPantherClient();
//
//        $crawler = $client->request('GET', '/');
//
//        $crawler = $this->loginAs($client, $crawler, 'admin', 'azerty');
//
//        $crawler = $client->click($crawler->selectLink('Dashboard')->link());
//        $crawler = $client->click($crawler->selectLink('ARTICLES')->link());
//
//        $client->getWebDriver()->switchTo()->alert()->accept();
//
//        $crawler = $client->click($crawler->filter('a.is-danger')->eq(5)->link());
//
//        $client->waitFor('.notification');
//
//        $this->assertContains('The article has been successfully deleted', $crawler->filter('.notification')->text());
//    }
}
