<?php

namespace App\Tests\Controller;

use App\DataFixtures\ORM\LoadUserData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{
    public function test(){
    }

    public function testPostComment()
    {
        $references = $this->loadFixtures([LoadUserData::class])->getReferenceRepository();
        $user = $references->getReference('user1');
        $this->loginAs($user, 'main');
        $client = $this->makeClient();
        $client->request('GET', '/register');
        $this->assertStatusCode('200', $client);
    }
}
