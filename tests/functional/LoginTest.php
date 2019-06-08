<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class LoginTest extends PantherTestCase
{
    public function testWrongUsernameOrPassword()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Log in')->form();

        $form['login[username]'] = 'bob';
        $form['login[password]'] = 'password';

        $crawler = $client->submit($form);

        $this->assertContains('User or password could not be found', $crawler->filter('.login-message')->text());
    }

    public function testLoginSuccessfully()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Log in')->form();

        $form['login[username]'] = 'johnDoe';
        $form['login[password]'] = 'password';

        $crawler = $client->submit($form);

        $client->waitFor('.notification-container');
        $this->assertContains('You are now log in', $crawler->filter('.notification-container')->text());
    }
}
