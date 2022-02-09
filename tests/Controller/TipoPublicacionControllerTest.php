<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Repository\TipoPublicacionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TipoPublicacionControllerTest extends WebTestCase
{
    public function testTipoPublicacionNew(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        
        $client->loginUser($testUser);
        
        //INDEX TEST
        $crawler = $client->request('GET', '/tipo/publicacion');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // $crawler = $client->followRedirect();

        //NEW TEST 
        $crawler = $client->request('GET', '/tipo/publicacion/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $form['tipo_publicacion[nombre]']='Periodico de Prueba_7';
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();

        //CREACION CORRECTA TEST
        $tipoPublicacionRepository = static::getContainer()->get(TipoPublicacionRepository::class);
        $tPublicacion = $tipoPublicacionRepository->findOneByNombre('Periodico de Prueba_7');//busca el nombre que se crea
        
        $this->assertNotNull($tPublicacion);

        //EDIT TEST 
        $crawler = $client->request('GET', '/tipo/publicacion/7/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Update');
        $form =$buttonCrwalerNode->form();

        $form['tipo_publicacion[nombre]']='Periodico de Prueba_7_Editado';

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //EDICION CORRECTA TEST
        $tPublicacion1 = $tipoPublicacionRepository->findOneByNombre('Periodico de Prueba_7_Editado');//findOneBy... lo que estoy editando

        $this->assertNotNull($tPublicacion1);
        
        //DELETE TEST
        $crawler = $client->request('GET', '/tipo/publicacion/7/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Delete');
        $form =$buttonCrwalerNode->form();
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ELIMINACION CORRECTA TEST
        $tPublicacion2 = $tipoPublicacionRepository->findOneByNombre('Periodico de Prueba_7_Editado');

        $this->assertNull($tPublicacion2);
    }
    public function testNotTipoPublicacion() : void 
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tipo/publicacion/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $var=$form['tipo_publicacion[nombre]']=123;     
        $this->assertIsNotString($var);
    }
    public function testNotADMINTipoPublicacion(): void 
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_2@gmail.com');//UserPrueba_2@gmail.com->No Admin
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tipo/publicacion/new');
        
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}