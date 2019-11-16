<?php

namespace App\Tests\Functional;

use App\Tests\BaseTestCase;

class LoginTest extends BaseTestCase
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
}
