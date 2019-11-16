<?php

namespace App\Tests\Functional;

use App\Tests\BaseTestCase;

class SigninTest extends BaseTestCase
{
    public function testUserNameAlreadyUsed()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'johnDoe';
        $form['registration[email]'] = 'johnDoe@gmail.com';
        $form['registration[plainPassword][first]'] = 'password';
        $form['registration[plainPassword][second]'] = 'password';

        $crawler = $client->submit($form);
        $this->assertContains('username is already used', $crawler->filter('ul li')->text());
    }

    public function testUserNameIsTooShort()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'jo';
        $form['registration[email]'] = 'johnDoe@gmail.com';
        $form['registration[plainPassword][first]'] = 'password';
        $form['registration[plainPassword][second]'] = 'password';

        $crawler = $client->submit($form);
        $this->assertContains('username must have 3 characters at least', $crawler->filter('ul li')->text());
    }

    public function testUserNameHasWrongChar()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'jo*$$$';
        $form['registration[email]'] = 'johnDoe@gmail.com';
        $form['registration[plainPassword][first]'] = 'password';
        $form['registration[plainPassword][second]'] = 'password';

        $crawler = $client->submit($form);
        $this->assertContains('username has wrong characters', $crawler->filter('ul li')->text());
    }

    public function testEmailAlreadyUsed()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'anakin';
        $form['registration[email]'] = 'john@gmail.com';
        $form['registration[plainPassword][first]'] = 'password';
        $form['registration[plainPassword][second]'] = 'password';

        $crawler = $client->submit($form);
        $this->assertContains('email is already used', $crawler->filter('ul li')->text());
    }

    public function testPasswordsDontMatch()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'anakin';
        $form['registration[email]'] = 'anakin@gmail.com';
        $form['registration[plainPassword][first]'] = 'azerty';
        $form['registration[plainPassword][second]'] = 'password';

        $crawler = $client->submit($form);
        $this->assertContains('passwords must be the same', $crawler->filter('ul li')->text());
    }

    public function testPasswordsHasLessThanSixChars()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'anakin';
        $form['registration[email]'] = 'anakin@gmail.com';
        $form['registration[plainPassword][first]'] = 'aqzs';
        $form['registration[plainPassword][second]'] = 'aqzs';

        $crawler = $client->submit($form);
        $this->assertContains('password must have 6 characters at least', $crawler->filter('ul li')->text());
    }

    public function testUserSuccessfullyCreated()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/registration');

        $form = $crawler->selectButton('Sign in')->form();
        $form['registration[username]'] = 'anakin';
        $form['registration[email]'] = 'anakin@gmail.com';
        $form['registration[plainPassword][first]'] = 'darkside';
        $form['registration[plainPassword][second]'] = 'darkside';

        $crawler = $client->submit($form);

        $this->assertContains('Log in', $crawler->filter('h1')->text());
    }
}
