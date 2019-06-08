<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class RouteTest extends PantherTestCase
{
    public function testIndexPage()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Training Symfony-blog', $crawler->filter('a')->text());
    }

    public function testLogInPage()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/login');

        $this->assertContains('Log in', $crawler->filter('h1')->text());
    }

    public function testSignInPage()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $this->assertContains('Sign in', $crawler->filter('h1')->text());
    }
}
