<?php

namespace App\Tests\Functional;

use App\Tests\BaseTestCase;

class CommentTest extends BaseTestCase
{
    public function testCommentWithUserNotLogged()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Read more')->link();
        $crawler = $client->click($link);

        $this->assertContains('You must be login to leave a comment', $crawler->filter('.notification')->text());
    }

    public function testCommentAnArticle()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');

        $crawler = $this->loginAs($client, $crawler, 'johnDoe', 'password');

        $link = $crawler->selectLink('Read more')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Leave a comment')->form();

        $form['App_comment[content]'] = 'Hi ! I am a new comment !';
        $crawler = $client->submit($form);

        $this->assertContains('Hi ! I am a new comment !', $crawler->filter('.content')->text());
    }
}
