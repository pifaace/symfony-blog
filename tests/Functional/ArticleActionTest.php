<?php

namespace App\Tests\Functional;

use App\Tests\BaseTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ArticleActionTest extends BaseTestCase
{
    use ReloadDatabaseTrait;

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
        $form['App_article[content]'] = 'This is an article wrote by panther';
        $form['app_tag_input'] = 'symfony,panther,';
        $crawler = $client->submit($form);

//        $this->assertEquals('An awesome new article', $crawler->filter('.table > tbody > tr > td')->first()->text());

        $this->logout($client);
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

        $form['App_article[title]'] = 'Edited by panther';

        $crawler = $client->submit($form);

        $form = $crawler->selectButton('Publish')->form();
        $formValues = $form->getValues();

        $this->assertEquals('Edited by panther', $formValues['App_article[title]']);

        $this->logout($client);
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
