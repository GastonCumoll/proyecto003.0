<?php

namespace App\Tests\Controller;

use DateTime;
use App\Repository\UserRepository;
use App\Repository\EdicionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class EdicionControllerTest extends WebTestCase
{
    public function testEdicion(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        $client->loginUser($testUser);

        //INDEX TEST
        $crawler = $client->request('GET', '/edicion');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //NEW TEST
        $crawler = $client->request('GET', '/edicion/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $form['edicion[cantidadImpresiones]']='300';
        $form['edicion[publicacion]']='1';//busca por id
        $form['edicion[usuarioCreador]']='2';

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //CREACION CORRECTA TEST
        $edicionRepository = static::getContainer()->get(EdicionRepository::class);
        $edicion1 = $edicionRepository->findOneById(1);//busca el id que se crea
        
        $this->assertNotNull($edicion1);

        //EDIT TEST
        $crawler = $client->request('GET', '/edicion/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Update');
        $form =$buttonCrwalerNode->form();

        $form['edicion[cantidadImpresiones]']='250';
        $form['edicion[publicacion]']='2';//busca por id
        $form['edicion[usuarioCreador]']='1';//edito el id del user creador 

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //EDICION CORRECTA TEST
        $edicion = $edicionRepository->findOneByCantidadImpresiones(250);//findOneBy... lo que estoy editando

        $this->assertNotNull($edicion);
        
        //DELETE TEST
        $crawler = $client->request('GET', '/edicion/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Delete');
        $form =$buttonCrwalerNode->form();
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ELIMINACION CORRECTA TEST
        $edicion2 = $edicionRepository->findOneById(1);

        $this->assertNull($edicion2);

    }

    public function testNotEdicion(): void 
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/edicion/new');

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $var=$form['edicion[cantidadImpresiones]']='cuarenta';
        $this->assertIsNotInt($var);
    }

    public function testNotADMINEdicion(): void 
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_2@gmail.com');//UserPrueba_2@gmail.com->No Admin
        
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/edicion/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}