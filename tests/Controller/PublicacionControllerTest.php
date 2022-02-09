<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Repository\PublicacionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PublicacionControllerTest extends WebTestCase
{
    public function testPublicacion(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        $client->loginUser($testUser);
        
        //INDEX TEST
        $crawler = $client->request('GET', '/publicacion');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        //NEW TEST
        $crawler = $client->request('GET', '/publicacion/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $form['publicacion[titulo]']='Publicacion de Prueba_6';
        $form['publicacion[tipoPublicacion]']='5';//busca por id
        $form['publicacion[usuarioCreador]']='2';
        $form['publicacion[cantidadImpresiones]']='1000';

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        //CREACION CORRECTA TEST
        $publicacionRepository = static::getContainer()->get(PublicacionRepository::class);
        $publicacion1 = $publicacionRepository->findOneByTitulo('Publicacion de Prueba_6');//busca el titulo que se crea
        
        $this->assertNotNull($publicacion1);

        $pId=$publicacion1->getId();
        
        //EDIT TEST
        $crawler = $client->request('GET', '/publicacion/6/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Update');
        $form =$buttonCrwalerNode->form();

        $form['publicacion[titulo]']='Publicacion de prueba_6_Edit';
        $form['publicacion[tipoPublicacion]']='5';//busca por id
        $form['publicacion[usuarioCreador]']='2';
        $form['publicacion[cantidadImpresiones]']='1001';

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //EDICION CORRECTA TEST
        $publicacion = $publicacionRepository->findOneByTitulo('Publicacion de prueba_6_Edit');//findOneBy... lo que estoy editando

        $this->assertNotNull($publicacion);
        
        
        //DELETE TEST
        //hay que tener cuidado con las FK
        /*
            $crawler = $client->request('GET', '/publicacion/13/edit');
            $this->assertEquals(200, $client->getResponse()->getStatusCode());

            $buttonCrwalerNode = $crawler->selectButton('Delete');
            $form =$buttonCrwalerNode->form();
        
            $client->submit($form);
            $crawler = $client->followRedirect();
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        */

    }
    
    public function testNotPublicacion(): void 
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        
        $client->loginUser($testUser);
        

        
        //index
        $crawler = $client->request('GET', '/publicacion/new');
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $var=$form['publicacion[titulo]']=5000;
        $this->assertIsNotString($var);

        $var=$form['publicacion[cantidadImpresiones]']='muchos';
        $this->assertIsNotInt($var);
        $var=$form['publicacion[cantidadImpresiones]']=-5;
        $this->assertGreaterThan($var,0);
        // $form['publicacion[usuarioCreador]']='1';
        // $client->submit($form);
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    //testea si entra un No Administrador, que no lo deje entrar(error 403)
    public function testNotADMINPublicacion(): void 
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_2@gmail.com');//UserPrueba_2@gmail.com->No Admin
        
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/publicacion/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}