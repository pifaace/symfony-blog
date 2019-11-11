<?php

namespace App\Tests;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class BaseTestCase extends PantherTestCase
{
    public function loginAs(Client $client, Crawler $crawler, string $username, string $password)
    {
        $link = $crawler->selectLink('Log in')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Log in')->form();

        $form['login[username]'] = $username;
        $form['login[password]'] = $password;

        return $client->submit($form);
    }

    public function logout(Client $client)
    {
        $client->request('GET', '/logout');
    }
}
