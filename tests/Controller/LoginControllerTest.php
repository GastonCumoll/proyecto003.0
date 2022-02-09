<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class LoginControllerTest extends WebTestCase
{
    public function testUserLogin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_0@gmail.com');
        $client->loginUser($testUser);
        
        $client->request('GET', '/login');
        //$crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/logout');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testNotUserLogin(): void
    {
        $client = static::createClient();
    
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('gastoncsssssumoll@gmail.com');//no existe
        $this->assertNull($testUser);
    }
}